<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Berita umum (Admin Publikasi) dan Berita Pendaftaran (Admin Pendaftaran).
 * Keduanya memakai tabel yang sama, dibedakan oleh kolom "channel".
 */
class NewsController extends Controller
{
    public function index(Request $request)
    {
        $channels = $this->channels($request);
        $channel = in_array($request->kanal, $channels, true) ? $request->kanal : (count($channels) === 1 ? $channels[0] : null);

        $news = News::with(['category', 'author'])
            ->whereIn('channel', $channel ? [$channel] : $channels)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_id', $request->kategori))
            ->when($request->filled('cari'), fn ($q) => $q->where('title', 'like', '%'.$request->cari.'%'))
            ->latest()
            ->paginate(12)->withQueryString();

        $categories = Category::where('type', 'news')->orderBy('name')->get();
        $counts = [
            'published' => News::whereIn('channel', $channel ? [$channel] : $channels)->where('status', 'published')->count(),
            'draft'     => News::whereIn('channel', $channel ? [$channel] : $channels)->where('status', 'draft')->count(),
        ];

        return view('dashboard.news.index', compact('news', 'categories', 'channels', 'channel', 'counts'));
    }

    public function create(Request $request)
    {
        $channels = $this->channels($request);
        $post = new News([
            'channel'      => in_array($request->kanal, $channels, true) ? $request->kanal : $channels[0],
            'status'       => 'published',
            'published_at' => now(),
        ]);

        return view('dashboard.news.form', [
            'post'       => $post,
            'channels'   => $channels,
            'categories' => Category::where('type', 'news')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->slug($data['title']);
        $data['cover'] = Upload::store($request->file('cover'), 'news');

        $post = News::create($data);
        activity_log('menulis berita', $post->title);

        return redirect()->route('dashboard.news.index', ['kanal' => $post->channel])
            ->with('success', $post->status === 'published' ? 'Berita diterbitkan.' : 'Berita disimpan sebagai draf.');
    }

    public function edit(Request $request, News $news)
    {
        $this->authorizeChannel($request, $news);

        return view('dashboard.news.form', [
            'post'       => $news,
            'channels'   => $this->channels($request),
            'categories' => Category::where('type', 'news')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, News $news)
    {
        $this->authorizeChannel($request, $news);

        $data = $this->validated($request);
        if ($request->boolean('remove_cover')) {
            Upload::delete($news->cover);
            $news->cover = null;
        }
        $data['cover'] = Upload::replace($request->file('cover'), $news->cover, 'news');

        $news->update($data);
        activity_log('mengubah berita', $news->title);

        return redirect()->route('dashboard.news.index', ['kanal' => $news->channel])->with('success', 'Berita diperbarui.');
    }

    /** Terbitkan ↔ jadikan draf dari daftar. */
    public function status(Request $request, News $news)
    {
        $this->authorizeChannel($request, $news);

        $published = $news->status !== 'published';
        $news->update([
            'status'       => $published ? 'published' : 'draft',
            'published_at' => $published ? ($news->published_at ?? now()) : $news->published_at,
        ]);
        activity_log($published ? 'menerbitkan berita' : 'menarik berita', $news->title);

        return back()->with('success', $published ? 'Berita diterbitkan.' : 'Berita dijadikan draf.');
    }

    public function destroy(Request $request, News $news)
    {
        $this->authorizeChannel($request, $news);

        Upload::delete($news->cover);
        $news->delete();
        activity_log('menghapus berita', $news->title);

        return back()->with('success', 'Berita dihapus.');
    }

    /* ------------------------------------------------------------------ */

    /** Kanal yang boleh dikelola user ini. */
    protected function channels(Request $request): array
    {
        $user = $request->user();
        $channels = [];

        if ($user->canManage('berita')) {
            $channels[] = 'umum';
        }
        if ($user->canManage('berita_pendaftaran')) {
            $channels[] = 'pendaftaran';
        }

        return $channels;
    }

    protected function authorizeChannel(Request $request, News $news): void
    {
        abort_unless(in_array($news->channel, $this->channels($request), true), 403, 'Berita ini dikelola oleh admin lain.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'channel'      => ['required', Rule::in($this->channels($request))],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'cover'        => ['nullable', 'image'],
            'excerpt'      => ['nullable', 'string', 'max:300'],
            'body'         => ['required', 'string'],
            'status'       => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ], [], ['title' => 'judul', 'body' => 'isi berita', 'channel' => 'jenis berita', 'excerpt' => 'ringkasan']);

        unset($data['cover']);
        $data['body'] = clean_html($data['body']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? now() : null);

        return $data;
    }

    protected function slug(string $title): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        $i = 2;
        while (News::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
