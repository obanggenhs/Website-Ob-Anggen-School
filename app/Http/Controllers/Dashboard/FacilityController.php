<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Facility;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $facilities = Facility::with('branch')
            ->when($request->filled('jenis'), fn ($q) => $q->where('type', $request->jenis))
            ->when($request->filled('kampus'), fn ($q) => $q->where('branch_id', $request->kampus))
            ->when($request->filled('cari'), fn ($q) => $q->where('name', 'like', '%'.$request->cari.'%'))
            ->orderBy('branch_id')->orderBy('type')->orderBy('position')
            ->paginate(20)->withQueryString();

        return view('dashboard.facilities.index', ['facilities' => $facilities, 'branches' => Branch::orderBy('position')->get()]);
    }

    public function create(Request $request)
    {
        return view('dashboard.facilities.form', [
            'facility' => new Facility([
                'type' => $request->query('jenis', 'gedung'), 'level' => 'umum', 'is_active' => true,
                'branch_id' => $request->query('kampus'),
            ]),
            'branches' => Branch::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data['name'], $data['branch_id'] ?? null);
        $data['image'] = Upload::store($request->file('image'), 'facilities');
        $data['photos'] = $this->photos($request, []);
        $data['position'] = $data['position'] ?: (int) Facility::where('type', $data['type'])->max('position') + 1;

        $facility = Facility::create($data);
        activity_log('menambah fasilitas', $facility->name);

        return redirect()->route('dashboard.facilities.index', ['jenis' => $facility->type])->with('success', 'Fasilitas ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        return view('dashboard.facilities.form', ['facility' => $facility, 'branches' => Branch::orderBy('position')->get()]);
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::replace($request->file('image'), $facility->image, 'facilities');

        $photos = collect($facility->photos ?? []);
        foreach ((array) $request->input('remove_photos', []) as $index) {
            Upload::delete($photos->get((int) $index));
            $photos->forget((int) $index);
        }
        $data['photos'] = $this->photos($request, $photos->values()->all());

        $facility->update($data);
        activity_log('mengubah fasilitas', $facility->name);

        return redirect()->route('dashboard.facilities.index', ['jenis' => $facility->type])->with('success', 'Fasilitas diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        Upload::delete($facility->image);
        Upload::deleteMany($facility->photos);
        $facility->delete();
        activity_log('menghapus fasilitas', $facility->name);

        return back()->with('success', 'Fasilitas dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'type'        => ['required', Rule::in(array_keys(Facility::TYPES))],
            'branch_id'   => ['nullable', 'exists:branches,id'],
            'level'       => ['required', Rule::in(array_keys(Facility::LEVELS))],
            'label'       => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:600'],
            'detail'      => ['nullable', 'string'],
            'capacity'    => ['nullable', 'string', 'max:60'],
            'area'        => ['nullable', 'string', 'max:60'],
            'used_for'    => ['nullable', 'string', 'max:150'],
            'position'    => ['nullable', 'integer', 'min:0'],
            'image'       => ['nullable', 'image'],
            'photos'      => ['nullable', 'array'],   // tanpa batas jumlah & ukuran
            'photos.*'    => ['image'],
        ], [], ['name' => 'nama fasilitas']);

        unset($data['image'], $data['photos']);
        $data['detail'] = clean_html($data['detail'] ?? '');
        $data['position'] = $data['position'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    protected function photos(Request $request, array $existing): array
    {
        foreach ((array) $request->file('photos', []) as $file) {
            if ($path = Upload::store($file, 'facilities')) {
                $existing[] = $path;
            }
        }

        return $existing;
    }

    protected function slug(string $name, ?int $branchId): string
    {
        $branch = $branchId ? Branch::find($branchId)?->name : null;
        $base = Str::slug(trim($branch.' '.$name)) ?: 'fasilitas';
        $slug = $base;
        $i = 2;
        while (Facility::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
