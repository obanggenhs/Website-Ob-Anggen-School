<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\BlockItem;
use App\Models\Menu;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

/**
 * Kelola kartu di dalam blok "Koleksi Kartu".
 * Dipakai Master Admin dan admin yang ditugaskan ke halaman tersebut (mis. Admin Publikasi).
 * Setiap kartu bisa memuat foto, video (YouTube / dari komputer), dan dokumen PDF (mis. Year Book).
 */
class BlockItemController extends Controller
{
    public function index(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeBlock($request, $menu, $block);

        $cards = $block->cards()
            ->when($request->filled('cari'), fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', '%'.$request->cari.'%')
                ->orWhere('category', 'like', '%'.$request->cari.'%')
                ->orWhere('year', 'like', '%'.$request->cari.'%')
                ->orWhere('subtitle', 'like', '%'.$request->cari.'%')))
            ->paginate(30)->withQueryString();

        $total = $block->cards()->count();
        $active = $block->cards()->where('is_active', true)->count();

        return view('dashboard.studio.cards', compact('menu', 'block', 'cards', 'total', 'active'));
    }

    public function create(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeBlock($request, $menu, $block);

        $card = new BlockItem(['is_active' => true, 'document_download' => true, 'year' => $request->query('tahun')]);

        return view('dashboard.studio.card-form', $this->formData($menu, $block, $card));
    }

    public function store(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeBlock($request, $menu, $block);

        $card = new BlockItem();
        $data = $this->validated($request, $card);
        $data['slug'] = BlockItem::uniqueSlug($data['title']);
        $data['image'] = Upload::store($request->file('image'), 'cards');
        $data['photos'] = $this->storePhotos($request, []);
        $data['position'] = (int) $block->cards()->max('position') + 1;
        $data = array_merge($data, $this->storeMedia($request, $card));

        $card = $block->cards()->create($data);
        $menu->forceFill(['updated_by' => $request->user()->id])->touch();
        activity_log('menambah kartu', $card->title.' di '.$menu->title);

        if ($request->input('after') === 'lagi') {
            return redirect()->route('dashboard.cards.create', [$menu, $block, 'tahun' => $card->year])
                ->with('success', 'Kartu "'.$card->title.'" disimpan. Silakan tambah kartu berikutnya.');
        }

        return redirect()->route('dashboard.cards.index', [$menu, $block])->with('success', 'Kartu "'.$card->title.'" ditambahkan.');
    }

    public function edit(Request $request, Menu $menu, Block $block, BlockItem $card)
    {
        $this->authorizeBlock($request, $menu, $block);

        return view('dashboard.studio.card-form', $this->formData($menu, $block, $card));
    }

    public function update(Request $request, Menu $menu, Block $block, BlockItem $card)
    {
        $this->authorizeBlock($request, $menu, $block);

        $data = $this->validated($request, $card);
        if ($data['title'] !== $card->title) {
            $data['slug'] = BlockItem::uniqueSlug($data['title'], $card->id);
        }

        if ($request->boolean('remove_image')) {
            Upload::delete($card->image);
            $card->image = null;
        }
        $data['image'] = Upload::replace($request->file('image'), $card->image, 'cards');

        $photos = collect($card->photos ?? []);
        foreach ((array) $request->input('remove_photos', []) as $index) {
            Upload::delete($photos->get((int) $index));
            $photos->forget((int) $index);
        }
        $data['photos'] = $this->storePhotos($request, $photos->values()->all());
        $data = array_merge($data, $this->storeMedia($request, $card));

        $card->update($data);
        $menu->forceFill(['updated_by' => $request->user()->id])->touch();
        activity_log('mengubah kartu', $card->title.' di '.$menu->title);

        return redirect()->route('dashboard.cards.index', [$menu, $block])->with('success', 'Kartu "'.$card->title.'" disimpan.');
    }

    public function toggle(Request $request, Menu $menu, Block $block, BlockItem $card)
    {
        $this->authorizeBlock($request, $menu, $block);
        $card->update(['is_active' => ! $card->is_active]);

        return back()->with('success', 'Kartu "'.$card->title.'" '.($card->is_active ? 'ditampilkan' : 'disembunyikan').'.');
    }

    public function move(Request $request, Menu $menu, Block $block, BlockItem $card, string $direction)
    {
        $this->authorizeBlock($request, $menu, $block);

        $cards = $block->cards()->get(['id', 'position'])->values();
        $index = $cards->search(fn ($c) => $c->id === $card->id);
        $target = $direction === 'naik' ? $index - 1 : $index + 1;

        if ($cards->has($target)) {
            $moved = $cards->pull($index);
            $cards->splice($target, 0, [$moved]);
            $cards->values()->each(fn ($c, $i) => BlockItem::whereKey($c->id)->update(['position' => $i + 1]));
        }

        return back();
    }

    /** Simpan urutan hasil seret. ?offset= dipakai bila daftar terbagi beberapa halaman. */
    public function reorder(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeBlock($request, $menu, $block);
        $ids = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']])['ids'];
        $offset = max(0, (int) $request->query('offset'));

        foreach ($ids as $i => $id) {
            $block->cards()->whereKey($id)->update(['position' => $offset + $i + 1]);
        }

        return response()->json(['message' => 'Urutan kartu disimpan.']);
    }

    public function destroy(Request $request, Menu $menu, Block $block, BlockItem $card)
    {
        $this->authorizeBlock($request, $menu, $block);

        foreach ($card->files() as $file) {
            Upload::delete($file);
        }
        $title = $card->title;
        $card->delete();
        activity_log('menghapus kartu', $title.' di '.$menu->title);

        return back()->with('success', 'Kartu "'.$title.'" dihapus.');
    }

    /* ------------------------------------------------------------------ */

    protected function authorizeBlock(Request $request, Menu $menu, Block $block): void
    {
        abort_unless($menu->isPage() && $block->type === 'koleksi', 404);
        abort_unless($request->user()->canEditMenu($menu), 403, 'Halaman ini belum ditugaskan untuk akun Anda.');
    }

    protected function formData(Menu $menu, Block $block, BlockItem $card): array
    {
        return [
            'menu'       => $menu,
            'block'      => $block,
            'card'       => $card,
            'categories' => $block->cards()->reorder()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
        ];
    }

    protected function validated(Request $request, BlockItem $card): array
    {
        $source = (string) $request->input('video_source', 'none');
        $keepVideo = $card->video_file && ! $request->boolean('remove_video');

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:150'],
            'subtitle'     => ['nullable', 'string', 'max:200'],
            'category'     => ['nullable', 'string', 'max:100'],
            'year'         => ['nullable', 'regex:/^\d{4}$/'],
            // File tanpa batas ukuran & jumlah (file besar dikirim bertahap dari dashboard)
            'image'        => ['nullable', 'image'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'body'         => ['nullable', 'string'],
            'meta'         => ['nullable', 'string', 'max:3000'],
            'video_source' => ['nullable', 'in:none,youtube,upload'],
            'video_url'    => [$source === 'youtube' ? 'required' : 'nullable', 'url', 'max:255'],
            'video_file'   => [$source === 'upload' && ! $keepVideo ? 'required' : 'nullable', 'file', 'mimes:'.Media::VIDEO_MIMES],
            'document'     => ['nullable', ...Media::documentRules()],
            'photos'       => ['nullable', 'array'],
            'photos.*'     => ['image'],
        ], [
            'year.regex'          => 'Tahun diisi 4 angka, mis. 2024.',
            'video_url.required'  => 'Tempel link YouTube, atau pilih "Tidak ada video".',
            'video_file.required' => 'Pilih file video dari komputer.',
            'video_file.mimes'    => 'Video harus berformat MP4, WEBM, OGG, atau MOV agar bisa diputar di browser.',
        ], [
            'title' => 'nama / judul', 'subtitle' => 'keterangan singkat', 'category' => 'kategori', 'year' => 'tahun',
            'image' => 'foto utama', 'excerpt' => 'ringkasan', 'body' => 'isi detail', 'meta' => 'info tambahan',
            'video_url' => 'link YouTube', 'video_file' => 'file video', 'document' => 'dokumen', 'photos.*' => 'foto dokumentasi',
        ]);

        unset($data['image'], $data['photos'], $data['video_file'], $data['document'], $data['video_source']);
        $data['category'] = trim((string) ($data['category'] ?? '')) ?: null;
        $data['body'] = clean_html($data['body'] ?? '');
        $data['video_url'] = $source === 'youtube' ? ($data['video_url'] ?? null) : null;
        $data['document_download'] = $request->boolean('document_download');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    /** Video (YouTube / dari komputer) dan dokumen kartu. */
    protected function storeMedia(Request $request, BlockItem $card): array
    {
        $data = [];
        $source = (string) $request->input('video_source', 'none');

        // Video dari komputer dihapus bila diganti, dicentang hapus, atau sumber video diganti
        if ($request->hasFile('video_file') || $request->boolean('remove_video') || $source !== 'upload') {
            Upload::delete($card->video_file);
            $data['video_file'] = $source === 'upload' ? Upload::store($request->file('video_file'), 'cards/video') : null;
        }

        if ($request->hasFile('document') || $request->boolean('remove_document')) {
            Upload::delete($card->document);
            $data = array_merge($data, ['document' => null, 'document_name' => null, 'document_size' => null],
                Media::storeDocument($request->file('document'), 'cards/dokumen') ?? []);
        }

        return $data;
    }

    protected function storePhotos(Request $request, array $existing): array
    {
        foreach ((array) $request->file('photos', []) as $file) {
            if ($path = Upload::store($file, 'cards')) {
                $existing[] = $path;
            }
        }

        return array_values($existing);
    }
}
