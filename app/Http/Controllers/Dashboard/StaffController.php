<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Staff;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = Staff::with('branch')
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->kategori))
            ->when($request->filled('kampus'), fn ($q) => $q->where('branch_id', $request->kampus))
            ->when($request->filled('cari'), fn ($q) => $q->where('name', 'like', '%'.$request->cari.'%'))
            ->orderBy('position')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('dashboard.staff.index', ['staff' => $staff, 'branches' => Branch::orderBy('position')->get()]);
    }

    public function create()
    {
        return view('dashboard.staff.form', [
            'person'   => new Staff(['category' => 'guru_sd', 'is_active' => true, 'position' => (int) Staff::max('position') + 1]),
            'branches' => Branch::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data['name']);
        $data['photo'] = Upload::store($request->file('photo'), 'staff');

        $person = Staff::create($data);
        activity_log('menambah guru/staf', $person->name);

        return redirect()->route('dashboard.staff.index')->with('success', 'Data '.$person->name.' ditambahkan.');
    }

    public function edit(Staff $staff)
    {
        return view('dashboard.staff.form', ['person' => $staff, 'branches' => Branch::orderBy('position')->get()]);
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $this->validated($request);
        $data['photo'] = Upload::replace($request->file('photo'), $staff->photo, 'staff');
        $staff->update($data);
        activity_log('mengubah guru/staf', $staff->name);

        return redirect()->route('dashboard.staff.index')->with('success', 'Data '.$staff->name.' diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        Upload::delete($staff->photo);
        $staff->delete();
        activity_log('menghapus guru/staf', $staff->name);

        return back()->with('success', 'Data dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:120'],
            'category'       => ['required', Rule::in(array_keys(Staff::CATEGORIES))],
            'branch_id'      => ['nullable', 'exists:branches,id'],
            'position_title' => ['nullable', 'string', 'max:150'],
            'subject'        => ['nullable', 'string', 'max:120'],
            'education'      => ['nullable', 'string', 'max:120'],
            'email'          => ['nullable', 'email', 'max:150'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'joined_year'    => ['nullable', 'integer', 'min:1980', 'max:'.date('Y')],
            'about'          => ['nullable', 'string', 'max:3000'],
            'duties'         => ['nullable', 'string', 'max:2000'],
            'position'       => ['nullable', 'integer', 'min:0'],
            'photo'          => ['nullable', 'image'],
        ], [], ['name' => 'nama', 'category' => 'kelompok']);

        unset($data['photo']);
        $data['position'] = $data['position'] ?? 0;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    protected function slug(string $name): string
    {
        $base = Str::slug($name) ?: 'staf';
        $slug = $base;
        $i = 2;
        while (Staff::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
