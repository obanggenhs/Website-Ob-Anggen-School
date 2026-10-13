<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\News;
use App\Support\PageOptions;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $channel = $request->query('kanal') === 'pendaftaran' ? 'pendaftaran' : null;
        $category = $request->filled('kategori')
            ? Category::where('type', 'news')->where('slug', $request->kategori)->first()
            : null;

        $menu = Menu::forModule('berita', $channel ?: ($category ? 'kategori:'.$category->slug : null));

        // Fitur bantuan pengunjung (pencarian, filter, urutan, dll.) yang dipilih admin
        $opt = PageOptions::for($menu, 'berita');
        $in = PageOptions::input($request, $opt, 'berita');

        $news = News::published()->with('category')
            ->when($channel, fn ($q) => $q->where('channel', $channel))
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->when($in['cari'] !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', '%'.$in['cari'].'%')
                ->orWhere('excerpt', 'like', '%'.$in['cari'].'%')))
            ->when($in['tahun'] !== '', fn ($q) => $q->whereYear('published_at', $in['tahun']));

        match ($in['urut']) {
            'terlama' => $news->oldest('published_at'),
            'populer' => $news->orderByDesc('views')->latest('published_at'),
            'az'      => $news->orderBy('title'),
            'za'      => $news->orderByDesc('title'),
            default   => $news->orderByDesc('is_featured')->latest('published_at'),
        };

        $news = $news->paginate($opt['per_page'])->withQueryString();

        $categories = Category::where('type', 'news')->withCount(['news' => fn ($q) => $q->published()])->orderBy('name')->get();

        $years = News::published()
            ->when($channel, fn ($q) => $q->where('channel', $channel))
            ->whereNotNull('published_at')->pluck('published_at')
            ->map(fn ($date) => $date->format('Y'))->unique()->sortDesc()->values();

        return view('web.news.index', compact('news', 'categories', 'category', 'channel', 'menu', 'opt', 'in', 'years'));
    }

    public function show(string $slug)
    {
        $post = News::with(['category', 'author'])->where('slug', $slug)->firstOrFail();

        abort_unless($post->status === 'published' || auth()->check(), 404);

        $post->increment('views');

        $related = News::published()->where('id', '!=', $post->id)
            ->where(fn ($q) => $q->where('category_id', $post->category_id)->orWhere('channel', $post->channel))
            ->latest('published_at')->take(3)->get();

        $menu = Menu::forModule('berita', $post->channel === 'pendaftaran' ? 'pendaftaran' : null);

        return view('web.news.show', compact('post', 'related', 'menu'));
    }
}
