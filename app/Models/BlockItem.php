<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Satu kartu di dalam blok "Koleksi Kartu".
 * Tampil sebagai kartu di halaman, dan punya halaman detail sendiri:
 * /halaman/{slug-halaman}/{slug-kartu}
 */
class BlockItem extends Model
{
    protected $fillable = [
        'block_id', 'title', 'slug', 'subtitle', 'category', 'year', 'image', 'photos', 'video_url', 'video_file',
        'document', 'document_name', 'document_size', 'document_download', 'excerpt', 'body', 'meta', 'position', 'is_active',
    ];

    protected $casts = [
        'photos'            => 'array',
        'document_download' => 'boolean',
        'is_active'         => 'boolean',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'kartu';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image);
    }

    public function getSummaryAttribute(): string
    {
        return $this->excerpt ?: Str::limit(trim(strip_tags((string) $this->body)), 140);
    }

    /** Sumber video kartu: upload | youtube | none */
    public function videoSource(): string
    {
        return $this->video_file ? 'upload' : ($this->video_url ? 'youtube' : 'none');
    }

    /** Alamat video yang diputar (file lokal atau link YouTube). */
    public function videoLink(): ?string
    {
        return $this->video_file ? media_url($this->video_file) : $this->video_url;
    }

    /** Semua file milik kartu (untuk dihapus bersama kartu). */
    public function files(): array
    {
        return array_values(array_filter(array_merge([$this->image, $this->video_file, $this->document], (array) $this->photos)));
    }

    /** Alamat halaman detail kartu di website. */
    public function getUrlAttribute(): string
    {
        return route('page.item', [$this->block->menu->slug, $this->slug]);
    }

    /** Info tambahan "Label: Nilai" per baris → [['label' => .., 'value' => ..], ...] */
    public function metaList(): array
    {
        $list = [];
        foreach (lines($this->meta) as $line) {
            [$label, $value] = array_pad(array_map('trim', explode(':', $line, 2)), 2, '');
            if ($value === '') {
                [$label, $value] = ['', $label];
            }
            $list[] = ['label' => $label, 'value' => $value];
        }

        return $list;
    }
}
