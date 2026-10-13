<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GalleryAlbum;
use App\Models\Menu;
use App\Support\PageOptions;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->filled('kategori')
            ? Category::where('type', 'gallery')->where('slug', $request->kategori)->first()
            : null;

        $menu = Menu::forModule('galeri');

        // Fitur bantuan pengunjung (pencarian, filter, urutan, dll.) yang dipilih admin
        $opt = PageOptions::for($menu, 'galeri');
        $in = PageOptions::input($request, $opt, 'galeri');

        $albums = GalleryAlbum::where('is_active', true)
            ->with(['photos', 'category'])->withCount('photos')
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->when($in['cari'] !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', '%'.$in['cari'].'%')
                ->orWhere('description', 'like', '%'.$in['cari'].'%')))
            ->when($in['tahun'] !== '', fn ($q) => $q->whereYear('event_date', $in['tahun']));

        match ($in['urut']) {
            'terlama' => $albums->orderBy('event_date')->oldest(),
            'az'      => $albums->orderBy('title'),
            'za'      => $albums->orderByDesc('title'),
            default   => $albums->orderByDesc('event_date')->latest(),
        };

        $albums = $albums->paginate($opt['per_page'])->withQueryString();

        $categories = Category::where('type', 'gallery')->has('albums')->orderBy('name')->get();

        $years = GalleryAlbum::where('is_active', true)->whereNotNull('event_date')->pluck('event_date')
            ->map(fn ($date) => $date->format('Y'))->unique()->sortDesc()->values();

        return view('web.gallery.index', compact('albums', 'categories', 'category', 'menu', 'opt', 'in', 'years'));
    }

    public function show(string $slug)
    {
        $album = GalleryAlbum::with(['photos', 'category'])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $others = GalleryAlbum::where('is_active', true)->where('id', '!=', $album->id)
            ->with('photos')->latest('event_date')->take(4)->get();

        $menu = Menu::forModule('galeri');

        return view('web.gallery.show', compact('album', 'others', 'menu'));
    }
}
