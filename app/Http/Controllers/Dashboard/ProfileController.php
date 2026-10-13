<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('dashboard.profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:100'],
            'email'  => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'  => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image'],
        ], [], ['name' => 'nama']);

        $data['avatar'] = Upload::replace($request->file('avatar'), $user->avatar, 'avatars');
        $user->update($data);

        return back()->with('success', 'Profil disimpan.');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [], ['current_password' => 'kata sandi lama', 'password' => 'kata sandi baru']);

        $request->user()->update(['password' => Hash::make($request->password)]);
        activity_log('mengganti kata sandi');

        return back()->with('success', 'Kata sandi berhasil diganti.');
    }
}
