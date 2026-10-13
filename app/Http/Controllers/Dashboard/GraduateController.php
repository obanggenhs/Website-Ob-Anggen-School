<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Graduate;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GraduateController extends Controller
{
    public function index(Request $request)
    {
        $graduates = Graduate::query()
            ->when($request->filled('tahun'), fn ($q) => $q->where('graduation_year', $request->tahun))
            ->when($request->filled('jenjang'), fn ($q) => $q->where('level', $request->jenjang))
            ->when($request->filled('cari'), fn ($q) => $q->where('name', 'like', '%'.$request->cari.'%'))
            ->orderByDesc('graduation_year')->orderBy('name')
            ->paginate(15)->withQueryString();

        $years = Graduate::distinct()->orderByDesc('graduation_year')->pluck('graduation_year');

        return view('dashboard.graduates.index', compact('graduates', 'years'));
    }

    public function create()
    {
        return view('dashboard.graduates.form', ['graduate' => new Graduate(['graduation_year' => date('Y'), 'level' => 'sma', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data['name'].' '.$data['graduation_year']);
        $data['photo'] = Upload::store($request->file('photo'), 'graduates');

        $graduate = Graduate::create($data);
        activity_log('menambah lulusan', $graduate->name);

        return redirect()->route('dashboard.graduates.index')->with('success', 'Data lulusan ditambahkan.');
    }

    public function edit(Graduate $graduate)
    {
        return view('dashboard.graduates.form', compact('graduate'));
    }

    public function update(Request $request, Graduate $graduate)
    {
        $data = $this->validated($request);
        $data['photo'] = Upload::replace($request->file('photo'), $graduate->photo, 'graduates');
        $graduate->update($data);
        activity_log('mengubah lulusan', $graduate->name);

        return redirect()->route('dashboard.graduates.index')->with('success', 'Data lulusan diperbarui.');
    }

    public function destroy(Graduate $graduate)
    {
        Upload::delete($graduate->photo);
        $graduate->delete();
        activity_log('menghapus lulusan', $graduate->name);

        return back()->with('success', 'Data lulusan dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:120'],
            'level'           => ['required', Rule::in(array_keys(Graduate::LEVELS))],
            'graduation_year' => ['required', 'integer', 'min:1990', 'max:'.(date('Y') + 1)],
            'class_name'      => ['nullable', 'string', 'max:60'],
            'predicate'       => ['nullable', 'string', 'max:60'],
            'continue_to'     => ['nullable', 'string', 'max:150'],
            'testimony'       => ['nullable', 'string', 'max:2000'],
            'photo'           => ['nullable', 'image'],
        ], [], ['name' => 'nama', 'graduation_year' => 'tahun lulus']);

        unset($data['photo']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    protected function slug(string $text): string
    {
        $base = Str::slug($text) ?: 'lulusan';
        $slug = $base;
        $i = 2;
        while (Graduate::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
