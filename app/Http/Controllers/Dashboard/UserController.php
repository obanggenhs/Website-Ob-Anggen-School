<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use App\Support\Modules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Master Admin membuat & mengatur akun Admin Publikasi dan Admin Pendaftaran.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('creator')
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->orderByRaw("FIELD(role, 'master', 'publikasi', 'pendaftaran')")->orderBy('name')
            ->get();

        // Ringkasan hak kelola setiap role untuk ditampilkan di kartu
        $access = collect(['publikasi', 'pendaftaran'])->mapWithKeys(fn ($role) => [$role => [
            'core'  => collect(Modules::corePermissions($role))->map(fn ($k) => Modules::permissions()[$k]['label']),
            'menus' => Menu::where('access_'.$role, true)->orderBy('title')->get(),
        ]]);

        return view('dashboard.users.index', compact('users', 'access'));
    }

    public function create(Request $request)
    {
        return view('dashboard.users.form', ['user' => new User([
            'role' => in_array($request->role, ['publikasi', 'pendaftaran', 'master'], true) ? $request->role : 'publikasi',
            'is_active' => true,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], ['name' => 'nama', 'password' => 'kata sandi']);

        $user = User::create($data + ['is_active' => $request->boolean('is_active'), 'created_by' => $request->user()->id]);
        activity_log('membuat akun', $user->name.' ('.$user->role_label.')');

        return redirect()->route('dashboard.users.index')
            ->with('success', 'Akun '.$user->role_label.' untuk '.$user->name.' dibuat. Berikan email & kata sandinya kepada yang bersangkutan.');
    }

    public function edit(User $user)
    {
        return view('dashboard.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [], ['name' => 'nama', 'password' => 'kata sandi baru']);

        if ($user->is($request->user()) && $data['role'] !== 'master') {
            return back()->with('error', 'Anda tidak bisa menurunkan role akun Anda sendiri.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data + ['is_active' => $user->is($request->user()) ? true : $request->boolean('is_active')]);
        activity_log('mengubah akun', $user->name);

        return redirect()->route('dashboard.users.index')->with('success', 'Akun '.$user->name.' diperbarui.');
    }

    public function toggle(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        activity_log($user->is_active ? 'mengaktifkan akun' : 'menonaktifkan akun', $user->name);

        return back()->with('success', 'Akun '.$user->name.' '.($user->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }
        if ($user->isMaster() && User::where('role', 'master')->count() <= 1) {
            return back()->with('error', 'Minimal harus ada satu Master Admin.');
        }

        $user->delete();
        activity_log('menghapus akun', $user->name);

        return back()->with('success', 'Akun dihapus.');
    }
}
