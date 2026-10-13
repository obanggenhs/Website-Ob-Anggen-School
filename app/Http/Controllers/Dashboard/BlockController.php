<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Menu;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Blok isi halaman.
 * - Halaman Konten & Beranda: blok adalah isi utama halaman.
 * - Halaman modul (Berita, Galeri, Kelulusan, dll.): blok menjadi isi tambahan di atas/bawah daftar.
 * Setiap blok bisa memuat foto, video lokal, link YouTube, PDF, atau dokumen lain sesuai jenisnya.
 */
class BlockController extends Controller
{
    public function create(Request $request, Menu $menu)
    {
        $this->authorizeMenu($request, $menu);

        $types = $this->typesFor($menu);
        $type = array_key_exists((string) $request->query('jenis'), $types) ? $request->query('jenis') : 'teks';
        $block = new Block([
            'type' => $type, 'width' => 12, 'placement' => 'left', 'pairing' => 'auto', 'style' => 'plain', 'align' => 'left', 'title_align' => null,
            'image_position' => 'right', 'media_type' => $type === 'video' ? 'youtube' : 'image', 'is_active' => true,
        ]);
        $previous = $block->previousVisible($menu);   // blok sebelumnya, untuk pilihan blok berpasangan

        return view('dashboard.studio.block-form', compact('menu', 'block', 'previous'));
    }

    public function store(Request $request, Menu $menu)
    {
        $this->authorizeMenu($request, $menu);

        $block = new Block(['type' => $request->input('type')]);
        $data = $this->validated($request, $menu, null);
        $data['position'] = (int) $menu->blocks()->max('position') + 1;
        $data['image'] = Upload::store($request->file('image'), 'blocks');
        $data['images'] = $this->storeImages($request, []);
        $data = array_merge($data, $this->storeMedia($request, $block));

        $block = $menu->blocks()->create($data);
        $menu->forceFill(['updated_by' => $request->user()->id])->touch();
        activity_log('menambah blok', $block->type_label.' di '.$menu->title);

        // Koleksi Kartu: langsung lanjut menambahkan kartu-kartunya
        if ($block->type === 'koleksi') {
            return redirect()->route('dashboard.cards.create', [$menu, $block])
                ->with('success', 'Blok Koleksi Kartu dibuat. Sekarang tambahkan kartu pertama.');
        }

        return redirect()->to(route('dashboard.studio.show', $menu).'#blok-'.$block->id)
            ->with('success', 'Blok '.$block->type_label.' ditambahkan.');
    }

    public function edit(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeMenu($request, $menu);
        $previous = $block->previousVisible($menu);   // blok sebelumnya, untuk pilihan blok berpasangan

        return view('dashboard.studio.block-form', compact('menu', 'block', 'previous'));
    }

    public function update(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeMenu($request, $menu);

        $data = $this->validated($request, $menu, $block);

        if ($request->boolean('remove_image')) {
            Upload::delete($block->image);
            $block->image = null;
        }
        $data['image'] = Upload::replace($request->file('image'), $block->image, 'blocks');

        // Foto/video galeri yang dicentang untuk dihapus
        $images = collect($block->images ?? []);
        foreach ((array) $request->input('remove_images', []) as $index) {
            Upload::delete($images->get((int) $index));
            $images->forget((int) $index);
        }
        $data['images'] = $this->storeImages($request, $images->values()->all());
        $data = array_merge($data, $this->storeMedia($request, $block));

        $block->update($data);
        $menu->forceFill(['updated_by' => $request->user()->id])->touch();
        activity_log('mengubah blok', $block->type_label.' di '.$menu->title);

        return redirect()->to(route('dashboard.studio.show', $menu).'#blok-'.$block->id)->with('success', 'Blok disimpan.');
    }

    public function toggle(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeMenu($request, $menu);
        $block->update(['is_active' => ! $block->is_active]);

        return redirect()->to(route('dashboard.studio.show', $menu).'#blok-'.$block->id)
            ->with('success', 'Blok '.($block->is_active ? 'ditampilkan' : 'disembunyikan').'.');
    }

    public function move(Request $request, Menu $menu, Block $block, string $direction)
    {
        $this->authorizeMenu($request, $menu);

        $blocks = $menu->blocks()->get()->values();
        $index = $blocks->search(fn ($b) => $b->id === $block->id);
        $target = $direction === 'naik' ? $index - 1 : $index + 1;

        if ($blocks->has($target)) {
            $moved = $blocks->pull($index);
            $blocks->splice($target, 0, [$moved]);
            $blocks->values()->each(fn ($b, $i) => $b->update(['position' => $i + 1]));
        }

        return redirect()->to(route('dashboard.studio.show', $menu).'#blok-'.$block->id);
    }

    public function reorder(Request $request, Menu $menu)
    {
        $this->authorizeMenu($request, $menu);
        $ids = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']])['ids'];

        foreach ($ids as $i => $id) {
            $menu->blocks()->whereKey($id)->update(['position' => $i + 1]);
        }

        return response()->json(['message' => 'Urutan blok disimpan.']);
    }

    public function destroy(Request $request, Menu $menu, Block $block)
    {
        $this->authorizeMenu($request, $menu);

        foreach ($block->files() as $file) {
            Upload::delete($file);
        }
        foreach ($block->cards as $card) {
            foreach ($card->files() as $file) {
                Upload::delete($file);
            }
        }
        $label = $block->type_label;
        $block->delete();
        activity_log('menghapus blok', $label.' di '.$menu->title);

        return redirect()->route('dashboard.studio.show', $menu)->with('success', 'Blok '.$label.' dihapus.');
    }

    /* ------------------------------------------------------------------ */

    protected function authorizeMenu(Request $request, Menu $menu): void
    {
        abort_unless($menu->supportsBlocks(), 404);
        abort_unless($request->user()->canEditMenu($menu), 403, 'Halaman ini belum ditugaskan untuk akun Anda.');
    }

    /** Jenis blok yang boleh dipakai. Koleksi Kartu hanya untuk Halaman Konten & Beranda. */
    protected function typesFor(Menu $menu): array
    {
        return $menu->isPage() ? Block::TYPES : array_diff_key(Block::TYPES, ['koleksi' => true]);
    }

    protected function validated(Request $request, Menu $menu, ?Block $block = null): array
    {
        $type = $block?->type ?? $request->input('type');
        $media = (string) $request->input('media_type');

        // Media yang wajib diisi sesuai pilihan (file lama tetap dipakai bila tidak diganti)
        $needYoutube = ($type === 'video' && $media !== 'upload') || ($type === 'gambar_teks' && $media === 'youtube');
        $needVideo = (($type === 'video' && $media === 'upload') || ($type === 'gambar_teks' && $media === 'video'))
            && ! ($block?->video_file && ! $request->boolean('remove_video'));
        $needDocument = ($type === 'dokumen' || ($type === 'gambar_teks' && $media === 'pdf'))
            && ! ($block?->document && ! $request->boolean('remove_document'));

        $data = $request->validate([
            'type'           => [$block ? 'nullable' : 'required', Rule::in(array_keys($this->typesFor($menu)))],
            'title'          => ['nullable', 'string', 'max:200'],
            'body'           => [in_array($type, ['teks', 'kutipan'], true) ? 'required' : 'nullable', 'string'],
            // File tanpa batas ukuran & jumlah (file besar dikirim bertahap dari dashboard)
            'image'          => ['nullable', 'image'],
            'images'         => ['nullable', 'array'],
            'images.*'       => ['file', 'mimes:jpg,jpeg,png,webp,gif,'.Media::VIDEO_MIMES],
            'media_type'     => ['nullable', Rule::in(['image', 'video', 'youtube', 'pdf', 'upload'])],
            'video_url'      => [$needYoutube ? 'required' : 'nullable', 'url', 'max:255'],
            'video_file'     => [$needVideo ? 'required' : 'nullable', 'file', 'mimes:'.Media::VIDEO_MIMES],
            'document'       => [$needDocument ? 'required' : 'nullable', ...Media::documentRules($type === 'gambar_teks')],
            'doc_display'    => ['nullable', Rule::in(array_keys(Block::DOC_DISPLAYS))],
            'items'          => [in_array($type, ['daftar', 'kartu'], true) ? 'required' : 'nullable', 'string', 'max:5000'],
            'caption'        => ['nullable', 'string', 'max:255'],
            'button_label'   => [$type === 'tombol' ? 'required' : 'nullable', 'string', 'max:60'],
            'button_url'     => [$type === 'tombol' ? 'required' : 'nullable', 'string', 'max:255'],
            'image_position' => ['nullable', Rule::in(['left', 'right'])],
            // Tampilan blok
            'width'          => ['required', Rule::in(array_keys(Block::WIDTHS))],
            'placement'      => ['nullable', Rule::in(array_keys(Block::PLACEMENTS))],
            'pairing'        => ['nullable', Rule::in(array_keys(Block::PAIRINGS))],
            'style'          => ['required', Rule::in(array_keys(Block::STYLES))],
            'align'          => ['required', Rule::in(array_keys(Block::ALIGNS))],
            'title_align'    => ['nullable', Rule::in(array_keys(Block::TITLE_ALIGNS))],
            // Pengaturan blok Koleksi Kartu
            'card_columns'   => [$type === 'koleksi' ? 'required' : 'nullable', Rule::in([2, 3, 4, 6])],
            'card_shape'     => [$type === 'koleksi' ? 'required' : 'nullable', Rule::in(array_keys(Block::CARD_SHAPES))],
            'card_button'    => ['nullable', 'string', 'max:40'],
        ], [
            'video_url.required'  => 'Tempel link YouTube untuk video ini.',
            'video_file.required' => 'Pilih file video dari komputer.',
            'video_file.mimes'    => 'Video harus berformat MP4, WEBM, OGG, atau MOV agar bisa diputar di browser.',
            'document.required'   => $type === 'gambar_teks' ? 'Pilih file PDF untuk ditampilkan.' : 'Pilih file dokumen yang akan ditampilkan.',
            'document.mimes'      => 'File harus berformat PDF.',
            'images.*.mimes'      => 'Galeri hanya menerima foto (JPG/PNG/WEBP/GIF) dan video (MP4/WEBM/OGG/MOV).',
        ], [
            'body' => 'isi', 'video_url' => 'link YouTube', 'video_file' => 'file video', 'document' => 'dokumen',
            'items' => 'daftar item', 'button_label' => 'teks tombol', 'button_url' => 'link tombol', 'images.*' => 'file galeri',
        ]);

        unset($data['image'], $data['images'], $data['video_file'], $data['document']);
        if ($block) {
            unset($data['type']);
        }

        $data['body'] = clean_html($data['body'] ?? '');
        $data['image_position'] ??= 'right';
        $data['placement'] ??= 'left';   // hanya berlaku bila lebar blok tidak penuh
        $data['pairing'] ??= 'auto';     // susunan terhadap blok sebelumnya
        $data['title_align'] = ($data['title_align'] ?? 'ikut') === 'ikut' ? null : $data['title_align'];
        $data['is_active'] = $request->boolean('is_active');
        $data['media_type'] = match ($type) {
            'video'       => $media === 'upload' ? 'upload' : 'youtube',
            'gambar_teks' => array_key_exists($media, Block::MEDIA_TYPES) ? $media : 'image',
            default       => null,
        };

        $settings = $block?->settings ?? [];
        if ($type === 'koleksi') {
            $settings = array_merge($settings, [
                'columns' => (int) $data['card_columns'],
                'shape'   => $data['card_shape'],
                'excerpt' => $request->boolean('card_excerpt') ? 1 : 0,
                'button'  => trim((string) ($data['card_button'] ?? '')) ?: Block::CARD_DEFAULTS['button'],
            ]);
        }
        if (in_array($type, ['dokumen', 'gambar_teks'], true)) {
            $settings = array_merge($settings, [
                'doc_display'  => $data['doc_display'] ?? 'reader',
                'doc_download' => $request->boolean('doc_download') ? 1 : 0,
            ]);
        }
        $data['settings'] = $settings ?: null;
        unset($data['card_columns'], $data['card_shape'], $data['card_button'], $data['doc_display']);

        return $data;
    }

    /** Simpan / ganti / hapus file video lokal dan dokumen. */
    protected function storeMedia(Request $request, Block $block): array
    {
        $data = [];

        if ($request->boolean('remove_video') || $request->hasFile('video_file')) {
            Upload::delete($block->video_file);
            $data['video_file'] = Upload::store($request->file('video_file'), 'blocks/video');
        }

        if ($request->boolean('remove_document') || $request->hasFile('document')) {
            Upload::delete($block->document);
            $data = array_merge($data, ['document' => null, 'document_name' => null, 'document_size' => null],
                Media::storeDocument($request->file('document'), 'blocks/dokumen') ?? []);
        }

        return $data;
    }

    protected function storeImages(Request $request, array $existing): array
    {
        foreach ((array) $request->file('images', []) as $file) {
            if ($path = Upload::store($file, 'blocks')) {
                $existing[] = $path;
            }
        }

        return array_values($existing);
    }
}
