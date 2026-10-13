<?php

namespace App\Support;

use App\Models\Block;
use App\Models\BlockItem;
use App\Models\Menu;
use App\Models\MenuContent;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Menyimpan isi menu buatan admin ke tabel menu_contents, tersusun menurut
 * nama Menu Utama › Submenu › Halaman beserta seluruh isinya (teks, kartu, file, link).
 *
 * Menu bawaan (hasil data awal) tidak ikut. Tabel diperbarui otomatis setiap kali admin
 * menyimpan perubahan menu, blok, atau kartu (lihat middleware SyncMenuArchive),
 * dan bisa dibangun ulang kapan saja lewat MenuArchive::rebuild().
 */
class MenuArchive
{
    /** Perbarui data satu menu beserta submenunya (nama Menu Utama dipakai juga oleh submenu). */
    public static function sync(Menu $menu): void
    {
        $menu = Menu::with(['parent', 'children'])->find($menu->id);
        if (! $menu) {
            return;
        }

        static::syncOne($menu);
        foreach ($menu->children as $child) {
            static::syncOne($child->setRelation('parent', $menu));
        }
    }

    /** Bangun ulang seluruh data menu buatan admin. */
    public static function rebuild(): void
    {
        MenuContent::whereIn('menu_id', Menu::where('is_custom', false)->select('id'))->delete();

        Menu::with('parent')->where('is_custom', true)->orderBy('id')->get()
            ->each(fn (Menu $menu) => static::syncOne($menu));
    }

    /* ------------------------------------------------------------------ */

    protected static function syncOne(Menu $menu): void
    {
        if (! $menu->is_custom) {
            MenuContent::where('menu_id', $menu->id)->delete();

            return;
        }

        $now = now();
        $keep = [];

        foreach (static::rows($menu) as $row) {
            $record = MenuContent::updateOrCreate(
                ['menu_id' => $menu->id, 'source' => $row['source'], 'source_id' => $row['source_id']],
                $row + ['synced_at' => $now],
            );
            $keep[] = $record->id;
        }

        MenuContent::where('menu_id', $menu->id)->whereNotIn('id', $keep)->delete();
    }

    /** Susun baris isi: keterangan halaman, lalu setiap blok dan kartunya sesuai urutan. */
    protected static function rows(Menu $menu): array
    {
        $parent = $menu->parent;
        $base = [
            'main_menu'  => $parent ? $parent->title : $menu->title,
            'submenu'    => $parent ? $menu->title : null,
            'page_title' => $menu->title,
            'page_type'  => $menu->type_label,
            'page_url'   => $menu->type === 'dropdown' ? null : rescue(fn () => $menu->link, null, false),
            'created_by' => static::userName($menu->created_by),
            'updated_by' => static::userName($menu->updated_by),
        ];

        $rows = [static::menuRow($menu, $base)];
        $position = 0;

        $blocks = Block::with('cards')->where('menu_id', $menu->id)->orderBy('position')->orderBy('id')->get();
        foreach ($blocks as $block) {
            $rows[] = static::blockRow($block, $base, ++$position);

            if ($block->type === 'koleksi') {
                foreach ($block->cards as $card) {
                    $rows[] = static::cardRow($card, $block, $base, ++$position);
                }
            }
        }

        return $rows;
    }

    /** Baris keterangan halaman: sub judul, teks pembuka, dan latar header. */
    protected static function menuRow(Menu $menu, array $base): array
    {
        $files = [];
        static::addFile($files, $menu->banner, null, 'Foto header');
        static::addFile($files, $menu->banner_video, null, 'Video header');
        foreach (array_values((array) $menu->banner_slides) as $i => $slide) {
            static::addFile($files, $slide, null, 'Slide header '.($i + 1));
        }

        $text = static::plain($menu->intro);
        $type = 'Keterangan halaman';
        $link = $menu->banner_youtube ?: null;

        if ($menu->type === 'dropdown') {
            $type = 'Menu Utama (grup submenu)';
            $names = $menu->children->pluck('title')->implode(', ');
            $text = trim($text."\n".($names !== '' ? 'Submenu: '.$names : 'Belum ada submenu'));
        } elseif ($menu->type === 'tautan') {
            $type = 'Tautan';
            $link = $menu->url;
        }

        return $base + [
            'position'      => 0,
            'content_type'  => $type,
            'content_title' => $menu->subtitle,
            'content_text'  => $text !== '' ? $text : null,
            'link'          => $link,
            'files'         => $files ?: null,
            'file_names'    => static::fileNames($files),
            'is_active'     => (bool) $menu->is_active,
            'source'        => 'menu',
            'source_id'     => $menu->id,
            'created_at'    => static::time($menu->created_at),
            'updated_at'    => static::time($menu->updated_at),
        ];
    }

    protected static function blockRow(Block $block, array $base, int $position): array
    {
        $files = [];
        static::addFile($files, $block->image, null, 'Foto');
        foreach (array_values((array) $block->images) as $i => $image) {
            static::addFile($files, $image, null, (Block::isVideoPath($image) ? 'Video galeri ' : 'Foto galeri ').($i + 1));
        }
        static::addFile($files, $block->video_file, null, 'Video');
        static::addFile($files, $block->document, $block->document_name, 'Dokumen');

        $parts = [static::plain($block->body)];
        if (in_array($block->type, ['daftar', 'kartu'], true)) {
            $parts[] = implode("\n", lines($block->items));
        }
        $parts[] = (string) $block->caption;
        if ($block->type === 'tombol') {
            $parts[] = 'Tombol: '.$block->button_label;
        }
        if ($block->type === 'koleksi') {
            $parts[] = $block->cards->count().' kartu';
        }

        $link = $block->type === 'tombol' ? $block->button_url : ($block->video_url ?: null);

        return $base + [
            'position'      => $position,
            'content_type'  => $block->type_label,
            'content_title' => $block->title,
            'content_text'  => static::join($parts),
            'link'          => $link,
            'files'         => $files ?: null,
            'file_names'    => static::fileNames($files),
            'is_active'     => (bool) $block->is_active,
            'source'        => 'block',
            'source_id'     => $block->id,
            'created_at'    => static::time($block->created_at),
            'updated_at'    => static::time($block->updated_at),
        ];
    }

    protected static function cardRow(BlockItem $card, Block $block, array $base, int $position): array
    {
        $files = [];
        static::addFile($files, $card->image, null, 'Foto kartu');
        foreach (array_values((array) $card->photos) as $i => $photo) {
            static::addFile($files, $photo, null, 'Foto dokumentasi '.($i + 1));
        }
        static::addFile($files, $card->video_file, null, 'Video');
        static::addFile($files, $card->document, $card->document_name, 'Dokumen');

        $info = array_filter([
            $card->subtitle,
            $card->category ? 'Kategori: '.$card->category : null,
            $card->year ? 'Tahun: '.$card->year : null,
        ]);

        return $base + [
            'position'      => $position,
            'content_type'  => 'Kartu · '.($block->title ?: 'Koleksi Kartu'),
            'content_title' => $card->title,
            'content_text'  => static::join([implode(' · ', $info), (string) $card->excerpt, static::plain($card->body), (string) $card->meta]),
            'link'          => $card->video_url ?: null,
            'files'         => $files ?: null,
            'file_names'    => static::fileNames($files),
            'is_active'     => (bool) $card->is_active && (bool) $block->is_active,
            'source'        => 'card',
            'source_id'     => $card->id,
            'created_at'    => static::time($card->created_at),
            'updated_at'    => static::time($card->updated_at),
        ];
    }

    /* ------------------------------------------------------------------ */

    /** Ubah HTML editor menjadi teks biasa, baris per paragraf. */
    public static function plain(?string $html): string
    {
        $html = (string) $html;
        if ($html === '') {
            return '';
        }

        $html = preg_replace('#<(br|/p|/div|/li|/h[1-6]|/tr|/blockquote)\s*/?>#i', "\n", $html);
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/[ \t\x{00A0}]+/u", ' ', $text);
        $text = preg_replace("/\n\s*\n+/", "\n", $text);

        return trim($text);
    }

    protected static function join(array $parts): ?string
    {
        $text = trim(implode("\n", array_filter(array_map('trim', $parts), fn ($p) => $p !== '')));

        return $text !== '' ? $text : null;
    }

    /** Catat file: nama asli bila ada, selain itu keterangannya, mis. "Foto kartu (jpg)". Path asli tetap disimpan. */
    protected static function addFile(array &$files, ?string $path, ?string $name = null, ?string $label = null): void
    {
        if (! $path) {
            return;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $files[] = [
            'name' => $name ?: ($label ? $label.($ext !== '' ? ' ('.$ext.')' : '') : basename($path)),
            'path' => $path,
        ];
    }

    protected static function fileNames(array $files): ?string
    {
        return $files ? implode(', ', array_column($files, 'name')) : null;
    }

    protected static function userName(?int $id): ?string
    {
        static $names = [];

        if (! $id) {
            return null;
        }

        return $names[$id] ??= User::whereKey($id)->value('name');
    }

    protected static function time($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }
}