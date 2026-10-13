<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Support\Upload;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        return view('dashboard.branches.index', [
            'branches' => Branch::withCount(['staff', 'facilities'])->orderBy('position')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.branches.form', ['branch' => new Branch(['is_active' => true, 'position' => (int) Branch::max('position') + 1])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::store($request->file('image'), 'branches');
        $branch = Branch::create($data);
        activity_log('menambah kampus', $branch->name);

        return redirect()->route('dashboard.branches.index')->with('success', 'Kampus ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('dashboard.branches.form', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::replace($request->file('image'), $branch->image, 'branches');
        $branch->update($data);
        activity_log('mengubah kampus', $branch->name);

        return redirect()->route('dashboard.branches.index')->with('success', 'Kampus diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        Upload::delete($branch->image);
        $branch->delete();
        activity_log('menghapus kampus', $branch->name);

        return back()->with('success', 'Kampus dihapus. Guru & fasilitas yang terhubung menjadi tanpa kampus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'regency'     => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'position'    => ['nullable', 'integer', 'min:0'],
            'image'       => ['nullable', 'image'],
        ], [], ['name' => 'nama kampus']);

        unset($data['image']);
        $data['position'] = $data['position'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
