<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Blok isi halaman. Satu halaman tersusun dari beberapa blok berurutan.
 */
class Block extends Model
{
    public const TYPES = [
        'teks'        => ['label' => 'Teks', 'icon' => 'bi-text-paragraph', 'desc' => 'Judul dan paragraf bebas.'],
        'gambar_teks' => ['label' => 'Media + Teks', 'icon' => 'bi-layout-text-sidebar-reverse', 'desc' => 'Foto, video, YouTube, atau PDF di kiri/kanan dengan teks di sampingnya.'],
        'video'       => ['label' => 'Video', 'icon' => 'bi-play-btn', 'desc' => 'Video YouTube atau video dari komputer, dengan keterangan.'],
        'daftar'      => ['label' => 'Daftar Poin', 'icon' => 'bi-list-ol', 'desc' => 'Daftar bernomor, mis. misi sekolah.'],
        'kartu'       => ['label' => 'Kartu / Program', 'icon' => 'bi-grid-3x2-gap', 'desc' => 'Beberapa kartu berikon, mis. nilai-nilai sekolah.'],
        'galeri'      => ['label' => 'Galeri Foto & Video', 'icon' => 'bi-images', 'desc' => 'Kumpulan foto dan video yang bisa diperbesar.'],
        'dokumen'     => ['label' => 'Dokumen / PDF', 'icon' => 'bi-file-earmark-pdf', 'desc' => 'PDF yang bisa dibaca langsung di website dan diunduh (mis. Year Book, brosur, laporan), atau file Word/Excel/PowerPoint untuk diunduh.'],
        'kutipan'     => ['label' => 'Kutipan', 'icon' => 'bi-quote', 'desc' => 'Kalimat penting atau ayat yang disorot.'],
        'tombol'      => ['label' => 'Ajakan / Tombol', 'icon' => 'bi-hand-index', 'desc' => 'Kotak ajakan dengan tombol menuju halaman lain.'],
        'koleksi'     => ['label' => 'Koleksi Kartu', 'icon' => 'bi-grid-3x3-gap', 'desc' => 'Kumpulan kartu (mis. alumni, program, prestasi). Setiap kartu bisa diklik untuk membuka detailnya.'],
    ];

    /** Pengaturan bawaan blok Koleksi Kartu. */
    public const CARD_DEFAULTS = [
        'columns' => 4,              // kartu per baris di layar lebar
        'shape'   => 'landscape',    // landscape | square | portrait | none
        'excerpt' => 1,              // tampilkan ringkasan di kartu
        'button'  => 'Lihat detail', // teks tautan di kartu
    ];

    /** Jenis media untuk blok Media + Teks. */
    public const MEDIA_TYPES = [
        'image'   => ['label' => 'Foto', 'icon' => 'bi-image'],
        'video'   => ['label' => 'Video lokal', 'icon' => 'bi-camera-video'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'bi-youtube'],
        'pdf'     => ['label' => 'PDF', 'icon' => 'bi-file-earmark-pdf'],
    ];

    /** Sumber video untuk blok Video. */
    public const VIDEO_SOURCES = [
        'youtube' => ['label' => 'Link YouTube', 'icon' => 'bi-youtube'],
        'upload'  => ['label' => 'Video dari komputer', 'icon' => 'bi-upload'],
    ];

    /** Cara menampilkan dokumen PDF di website. */
    public const DOC_DISPLAYS = [
        'reader' => 'Langsung tampil (pembaca PDF di halaman)',
        'card'   => 'Kartu dokumen (dibuka saat diklik)',
    ];

    public const CARD_SHAPES = [
        'landscape' => 'Melebar (16:10)',
        'square'    => 'Kotak (1:1)',
        'portrait'  => 'Potret (4:5) — cocok untuk foto orang',
        'none'      => 'Tanpa gambar',
    ];

    /* ---------------- Tampilan blok ---------------- */

    /** Lebar blok (dari 12 kolom halaman). */
    public const WIDTHS = [
        12 => ['label' => 'Penuh', 'short' => 'Penuh'],
        8  => ['label' => 'Dua pertiga', 'short' => '2/3'],
        6  => ['label' => 'Setengah', 'short' => '1/2'],
        4  => ['label' => 'Sepertiga', 'short' => '1/3'],
        3  => ['label' => 'Seperempat', 'short' => '1/4'],
    ];

    /** Posisi blok di halaman (berlaku bila lebar tidak penuh). */
    public const PLACEMENTS = [
        'left'   => ['label' => 'Kiri', 'icon' => 'bi-align-start'],
        'center' => ['label' => 'Tengah', 'icon' => 'bi-align-center'],
        'right'  => ['label' => 'Kanan', 'icon' => 'bi-align-end'],
    ];

    public const STYLES = [
        'plain'  => 'Polos',
        'card'   => 'Kartu putih',
        'accent' => 'Warna utama (disorot)',
    ];

    /** Perataan isi / paragraf. */
    public const ALIGNS = [
        'left'    => ['label' => 'Rata kiri', 'icon' => 'bi-text-left'],
        'center'  => ['label' => 'Tengah', 'icon' => 'bi-text-center'],
        'right'   => ['label' => 'Rata kanan', 'icon' => 'bi-text-right'],
        'justify' => ['label' => 'Rata kiri-kanan', 'icon' => 'bi-justify'],
    ];

    /** Susunan blok terhadap blok sebelumnya (blok berpasangan). */
    public const PAIRINGS = [
        'auto'  => ['label' => 'Otomatis', 'icon' => 'bi-magic', 'desc' => 'Mengikuti lebar & posisi blok'],
        'right' => ['label' => 'Di kanan', 'icon' => 'bi-arrow-right-square', 'desc' => 'Bersebelahan, di kanan blok sebelumnya'],
        'left'  => ['label' => 'Di kiri', 'icon' => 'bi-arrow-left-square', 'desc' => 'Bersebelahan, di depan (kiri) blok sebelumnya'],
        'below' => ['label' => 'Di bawah', 'icon' => 'bi-arrow-down-square', 'desc' => 'Atas-bawah, di baris baru di bawah blok sebelumnya'],
    ];

    /** Perataan judul blok. "ikut" = mengikuti perataan isi (disimpan sebagai null). */
    public const TITLE_ALIGNS = [
        'ikut'   => ['label' => 'Ikuti isi', 'icon' => 'bi-link-45deg'],
        'left'   => ['label' => 'Kiri', 'icon' => 'bi-text-left'],
        'center' => ['label' => 'Tengah', 'icon' => 'bi-text-center'],
        'right'  => ['label' => 'Kanan', 'icon' => 'bi-text-right'],
    ];

    protected $fillable = [
        'menu_id', 'type', 'title', 'body', 'image', 'images', 'video_url', 'video_file', 'document', 'document_name', 'document_size',
        'items', 'caption', 'button_label', 'button_url', 'image_position', 'media_type', 'width', 'placement', 'pairing', 'style', 'align', 'title_align',
        'settings', 'position', 'is_active',
    ];

    /** Lebar & posisi saat tampil di website (diisi Block::arrange untuk blok berpasangan). */
    public ?int $layoutWidth = null;

    public ?string $layoutPlace = null;

    protected $casts = [
        'images'    => 'array',
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /** Kartu-kartu milik blok Koleksi Kartu. */
    public function cards()
    {
        return $this->hasMany(BlockItem::class)->orderBy('position')->orderBy('id');
    }

    /** Pengaturan blok Koleksi Kartu (kolom, bentuk gambar, dll.). */
    public function cardSetting(string $key): mixed
    {
        return ($this->settings ?? [])[$key] ?? self::CARD_DEFAULTS[$key] ?? null;
    }

    /* ---------------- Tampilan blok ---------------- */

    public function widthKey(): int
    {
        return array_key_exists((int) $this->width, self::WIDTHS) ? (int) $this->width : 12;
    }

    /** Lebar yang dipakai di website (blok berpasangan bisa disesuaikan agar muat satu baris). */
    public function displayWidth(): int
    {
        return $this->layoutWidth ?? $this->widthKey();
    }

    /** Posisi blok; blok selebar penuh selalu "left". */
    public function placementKey(): string
    {
        if ($this->layoutPlace) {
            return $this->layoutPlace;
        }
        if ($this->widthKey() === 12) {
            return 'left';
        }

        return array_key_exists((string) $this->placement, self::PLACEMENTS) ? $this->placement : 'left';
    }

    /** Perataan isi: left | center | right | justify */
    public function contentAlign(): string
    {
        return array_key_exists((string) $this->align, self::ALIGNS) ? $this->align : 'left';
    }

    /** Perataan judul: left | center | right (mengikuti isi bila tidak diatur). */
    public function titleAlign(): string
    {
        if (in_array($this->title_align, ['left', 'center', 'right'], true)) {
            return $this->title_align;
        }

        return $this->contentAlign() === 'justify' ? 'left' : $this->contentAlign();
    }

    /** Susunan terhadap blok sebelumnya: auto | right | left | below */
    public function pairingKey(): string
    {
        return array_key_exists((string) $this->pairing, self::PAIRINGS) ? $this->pairing : 'auto';
    }

    /**
     * Susun blok-blok satu halaman menjadi baris untuk website.
     * - Blok "Di kanan" / "Di kiri" bergabung satu baris dengan blok sebelumnya (berpasangan).
     *   Bila total lebarnya lebih dari penuh, semua blok di baris itu dibuat sama lebar.
     *   Letak baris (kiri/tengah/kanan) mengikuti posisi blok pertama di baris itu.
     * - Blok "Di bawah" selalu mulai di baris baru.
     * - Blok "Otomatis" tampil seperti biasa mengikuti lebar & posisinya.
     *
     * @return array<int, array{group: bool, blocks: array<int, self>, justify: string}>
     */
    public static function arrange(iterable $blocks): array
    {
        $rows = [];

        foreach ($blocks as $block) {
            $block->layoutWidth = null;
            $block->layoutPlace = null;
            $join = $block->pairingKey();

            if (in_array($join, ['right', 'left'], true) && $rows) {
                $i = count($rows) - 1;
                $at = $join === 'right' ? $rows[$i]['last'] + 1 : $rows[$i]['last'];
                array_splice($rows[$i]['blocks'], $at, 0, [$block]);
                $rows[$i]['group'] = true;
                $rows[$i]['last'] = $at;
                continue;
            }

            $rows[] = ['group' => $join === 'below', 'blocks' => [$block], 'last' => 0, 'leader' => $block, 'justify' => 'start'];
        }

        foreach ($rows as &$row) {
            if (! $row['group']) {
                continue;
            }
            $row['justify'] = ['left' => 'start', 'center' => 'center', 'right' => 'end'][$row['leader']->placementKey()];
            $count = count($row['blocks']);
            $total = array_sum(array_map(fn (self $b) => $b->widthKey(), $row['blocks']));
            $equal = $total > 12 ? ([1 => 12, 2 => 6, 3 => 4][$count] ?? 3) : null;

            foreach ($row['blocks'] as $b) {
                $b->layoutWidth = $equal ?? $b->widthKey();
                $b->layoutPlace = 'left';
            }
        }
        unset($row);

        return array_map(fn ($row) => ['group' => $row['group'], 'blocks' => $row['blocks'], 'justify' => $row['justify']], $rows);
    }

    /** Kelas kolom Bootstrap sesuai lebar blok. */
    public function columnClass(): string
    {
        return match ($this->displayWidth()) {
            8 => 'col-lg-8',
            6 => 'col-lg-6',
            4 => 'col-lg-4',
            3 => 'col-md-6 col-lg-3',
            default => 'col-lg-12',
        };
    }

    /** Kelas blok di website: gaya, perataan isi, dan perataan judul. */
    public function layoutClass(): string
    {
        $align = $this->contentAlign();

        return implode(' ', array_filter([
            'oa-block',
            'oa-block--'.(array_key_exists((string) $this->style, self::STYLES) ? $this->style : 'plain'),
            'oa-align--'.$align,
            'oa-title--'.$this->titleAlign(),
            match ($align) {
                'center'  => 'text-center',
                'right'   => 'text-end',
                'justify' => 'oa-block--justify',
                default   => null,
            },
        ]));
    }

    /** Ringkasan tampilan untuk daftar blok di dashboard, mis. "1/2 · Kanan". */
    public function layoutLabel(): string
    {
        $label = self::WIDTHS[$this->widthKey()]['short'];

        return $this->placementKey() !== 'left' ? $label.' · '.self::PLACEMENTS[$this->placementKey()]['label'] : $label;
    }

    /** Blok sebelumnya yang tampil di website (untuk pilihan susunan berpasangan di formulir). */
    public function previousVisible(Menu $menu): ?self
    {
        return $menu->blocks()
            ->where('is_active', true)
            ->when(! $menu->isPage(), fn ($q) => $q->where('type', '!=', 'koleksi'))
            ->when($this->exists, fn ($q) => $q->where('position', '<', $this->position)->whereKeyNot($this->id))
            ->reorder()->orderByDesc('position')->orderByDesc('id')
            ->first();
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type]['label'] ?? ucfirst($this->type);
    }

    public function getTypeIconAttribute(): string
    {
        return self::TYPES[$this->type]['icon'] ?? 'bi-square';
    }

    /** Media yang dipakai blok Media + Teks (image | video | youtube | pdf). */
    public function mediaKind(): string
    {
        return array_key_exists((string) $this->media_type, self::MEDIA_TYPES) ? $this->media_type : 'image';
    }

    /** Sumber video blok Video: upload bila ada file video dan dipilih, selain itu YouTube. */
    public function videoSource(): string
    {
        return $this->media_type === 'upload' && $this->video_file ? 'upload' : 'youtube';
    }

    /** Alamat video yang diputar (file lokal atau link YouTube). */
    public function videoLink(): ?string
    {
        return $this->videoSource() === 'upload' ? media_url($this->video_file) : $this->video_url;
    }

    /** Pengaturan dokumen: tampilan (reader/card) dan izin unduh. */
    public function docSetting(string $key): mixed
    {
        $defaults = ['display' => 'reader', 'download' => 1];

        return ($this->settings ?? [])['doc_'.$key] ?? $defaults[$key] ?? null;
    }

    /** File di folder galeri bisa berupa foto atau video. */
    public static function isVideoPath(?string $path): bool
    {
        return \App\Support\Media::isVideo($path);
    }

    /** Item untuk blok kartu: "Judul | Keterangan | bi-ikon" per baris. */
    public function cardItems(): array
    {
        return array_map(function ($line) {
            $parts = array_map('trim', explode('|', $line));

            return ['title' => $parts[0] ?? '', 'text' => $parts[1] ?? '', 'icon' => $parts[2] ?? 'bi-check2-circle'];
        }, lines($this->items));
    }

    /** Jumlah foto & video di blok galeri, mis. "8 foto, 2 video". */
    public function galleryCount(): string
    {
        $files = collect($this->images ?? []);
        $videos = $files->filter(fn ($f) => self::isVideoPath($f))->count();
        $text = ($files->count() - $videos).' foto';

        return $videos ? $text.', '.$videos.' video' : $text;
    }

    /** Semua file milik blok (untuk dihapus bersama blok). */
    public function files(): array
    {
        return array_values(array_filter(array_merge([$this->image, $this->video_file, $this->document], (array) $this->images)));
    }

    /** Ringkasan singkat untuk daftar blok di dashboard. */
    public function getPreviewAttribute(): string
    {
        return match ($this->type) {
            'video'  => $this->videoSource() === 'upload' ? 'Video dari komputer' : (string) $this->video_url,
            'galeri' => $this->galleryCount(),
            'dokumen' => $this->document
                ? trim(($this->document_name ?: basename($this->document)).' · '.\App\Support\Media::sizeLabel($this->document_size), ' ·')
                : 'Belum ada dokumen',
            'gambar_teks' => self::MEDIA_TYPES[$this->mediaKind()]['label'].' · '.\Illuminate\Support\Str::limit($this->plainBody(), 90),
            'koleksi' => $this->cards()->count().' kartu',
            'daftar', 'kartu' => count(lines($this->items)).' item',
            'tombol' => trim(($this->button_label ?: '').' → '.($this->button_url ?: '')),
            default  => \Illuminate\Support\Str::limit($this->plainBody(), 110),
        };
    }

    /** Isi tanpa tag HTML; judul & paragraf dipisah spasi agar tidak menempel. */
    public function plainBody(): string
    {
        $text = preg_replace('#<(br|/p|/h[1-6]|/li|/div|/blockquote|/td|/th)\b[^>]*>#i', ' ', (string) $this->body);

        return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }
}
