<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Galeri berbasis album: satu album berisi banyak foto (+ video opsional).
 */
class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $albums = GalleryAlbum::with(['category', 'photos'])->withCount('photos')
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_id', $request->kategori))
            ->when($request->filled('cari'), fn ($q) => $q->where('title', 'like', '%'.$request->cari.'%'))
            ->orderByDesc('event_date')->latest()
            ->paginate(12)->withQueryString();

        $categories = Category::where('type', 'gallery')->orderBy('name')->get();

        return view('dashboard.gallery.index', compact('albums', 'categories'));
    }

    public function create()
    {
        return view('dashboard.gallery.form', [
            'album'      => new GalleryAlbum(['event_date' => today(), 'is_active' => true]),
            'categories' => Category::where('type', 'gallery')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data['title']);
        $data['user_id'] = $request->user()->id;

        $album = GalleryAlbum::create($data);
        $added = $this->storePhotos($request, $album);
        activity_log('membuat album', $album->title.' ('.$added.' foto)');

        return redirect()->route('dashboard.gallery.edit', $album)->with('success', 'Album dibuat dengan '.$added.' foto. Tambahkan keterangan foto bila perlu.');
    }

    public function edit(GalleryAlbum $gallery)
    {
        return view('dashboard.gallery.form', [
            'album'      => $gallery->load('photos'),
            'categories' => Category::where('type', 'gallery')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, GalleryAlbum $gallery)
    {
        $gallery->update($this->validated($request));
        $added = $this->storePhotos($request, $gallery);

        // Keterangan foto yang diubah langsung dari grid
        foreach ((array) $request->input('captions', []) as $id => $caption) {
            $gallery->photos()->whereKey($id)->update(['caption' => Str::limit((string) $caption, 250, '')]);
        }

        activity_log('mengubah album', $gallery->title);

        return back()->with('success', 'Album disimpan'.($added ? ' dan '.$added.' foto baru ditambahkan' : '').'.');
    }

    public function cover(GalleryAlbum $gallery, GalleryPhoto $photo)
    {
        abort_unless($photo->gallery_album_id === $gallery->id, 404);
        $gallery->update(['cover' => $photo->image]);

        return back()->with('success', 'Sampul album diganti.');
    }

    public function destroyPhoto(GalleryAlbum $gallery, GalleryPhoto $photo)
    {
        abort_unless($photo->gallery_album_id === $gallery->id, 404);

        if ($gallery->cover === $photo->image) {
            $gallery->update(['cover' => null]);
        }
        Upload::delete($photo->image);
        $photo->delete();

        return back()->with('success', 'Foto dihapus.');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        foreach ($gallery->photos as $photo) {
            Upload::delete($photo->image);
        }
        $gallery->delete();
        activity_log('menghapus album', $gallery->title);

        return redirect()->route('dashboard.gallery.index')->with('success', 'Album beserta fotonya dihapus.');
    }

    /* ------------------------------------------------------------------ */

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'video_url'   => ['nullable', 'url', 'max:255'],
            'event_date'  => ['nullable', 'date'],
            'photos'      => ['nullable', 'array'],   // tanpa batas jumlah & ukuran
            'photos.*'    => ['image'],
        ], [], ['title' => 'judul album', 'photos.*' => 'foto']);

        unset($data['photos']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    protected function storePhotos(Request $request, GalleryAlbum $album): int
    {
        $position = (int) $album->photos()->max('position');
        $count = 0;

        foreach ((array) $request->file('photos', []) as $file) {
            if ($path = Upload::store($file, 'gallery')) {
                $album->photos()->create(['image' => $path, 'position' => ++$position]);
                $count++;
            }
        }

        return $count;
    }

    protected function slug(string $title): string
    {
        $base = Str::slug($title) ?: 'album';
        $slug = $base;
        $i = 2;
        while (GalleryAlbum::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
