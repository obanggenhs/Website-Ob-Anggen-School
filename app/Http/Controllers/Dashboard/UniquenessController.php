<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Uniqueness;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UniquenessController extends Controller
{
    public function index()
    {
        return view('dashboard.uniqueness.index', ['items' => Uniqueness::orderBy('position')->get()]);
    }

    public function create()
    {
        return view('dashboard.uniqueness.form', ['item' => new Uniqueness([
            'is_active' => true, 'icon' => 'bi-stars', 'position' => (int) Uniqueness::max('position') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data['title']);
        $data['image'] = Upload::store($request->file('image'), 'uniqueness');
        $data['photos'] = $this->photos($request, []);

        $item = Uniqueness::create($data);
        activity_log('menambah keunikan', $item->title);

        return redirect()->route('dashboard.uniqueness.index')->with('success', 'Keunikan "'.$item->title.'" ditambahkan.');
    }

    public function edit(Uniqueness $uniqueness)
    {
        return view('dashboard.uniqueness.form', ['item' => $uniqueness]);
    }

    public function update(Request $request, Uniqueness $uniqueness)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::replace($request->file('image'), $uniqueness->image, 'uniqueness');

        $photos = collect($uniqueness->photos ?? []);
        foreach ((array) $request->input('remove_photos', []) as $index) {
            Upload::delete($photos->get((int) $index));
            $photos->forget((int) $index);
        }
        $data['photos'] = $this->photos($request, $photos->values()->all());

        $uniqueness->update($data);
        activity_log('mengubah keunikan', $uniqueness->title);

        return redirect()->route('dashboard.uniqueness.index')->with('success', 'Keunikan diperbarui.');
    }

    public function destroy(Uniqueness $uniqueness)
    {
        Upload::delete($uniqueness->image);
        Upload::deleteMany($uniqueness->photos);
        $uniqueness->delete();
        activity_log('menghapus keunikan', $uniqueness->title);

        return back()->with('success', 'Keunikan dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title'     => ['required', 'string', 'max:120'],
            'icon'      => ['nullable', 'string', 'max:60'],
            'excerpt'   => ['nullable', 'string', 'max:400'],
            'content'   => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'position'  => ['nullable', 'integer', 'min:0'],
            'image'     => ['nullable', 'image'],
            'photos'    => ['nullable', 'array'],   // tanpa batas jumlah & ukuran
            'photos.*'  => ['image'],
        ], [], ['title' => 'judul']);

        unset($data['image'], $data['photos']);
        $data['content'] = clean_html($data['content'] ?? '');
        $data['position'] = $data['position'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    protected function photos(Request $request, array $existing): array
    {
        foreach ((array) $request->file('photos', []) as $file) {
            if ($path = Upload::store($file, 'uniqueness')) {
                $existing[] = $path;
            }
        }

        return $existing;
    }

    protected function slug(string $title): string
    {
        $base = Str::slug($title) ?: 'keunikan';
        $slug = $base;
        $i = 2;
        while (Uniqueness::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
