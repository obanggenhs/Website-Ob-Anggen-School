<?php

namespace App\Models;

use App\Support\Modules;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Menu Utama (parent_id null) & Submenu/Fitur website.
 */
class Menu extends Model
{
    /** Jenis latar header halaman (juga dipakai hero Beranda). */
    public const BANNER_TYPES = [
        'image'   => ['label' => 'Foto', 'icon' => 'bi-image'],
        'video'   => ['label' => 'Video lokal', 'icon' => 'bi-camera-video'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'bi-youtube'],
        'slider'  => ['label' => 'Slide foto', 'icon' => 'bi-collection-play'],
    ];

    /**
     * Lapisan warna di atas latar header & hero Beranda.
     * Tulisan selalu diberi bayangan halus sehingga tetap jelas di ketiga pilihan.
     */
    public const OVERLAYS = [
        'tipis'  => ['label' => 'Tipis', 'desc' => 'Latar lebih terlihat'],
        'sedang' => ['label' => 'Sedang', 'desc' => 'Seimbang'],
        'pekat'  => ['label' => 'Pekat', 'desc' => 'Tulisan paling mudah dibaca'],
    ];

    protected $fillable = [
        'parent_id', 'title', 'slug', 'type', 'param', 'url', 'icon', 'subtitle',
        'banner', 'banner_type', 'banner_video', 'banner_youtube', 'banner_slides', 'banner_overlay', 'intro', 'show_sidebar', 'page_options',
        'is_active', 'is_highlight', 'open_new_tab', 'position', 'access_publikasi', 'access_pendaftaran', 'updated_by',
    ];

    protected $casts = [
        'show_sidebar'       => 'boolean',
        'is_active'          => 'boolean',
        'is_highlight'       => 'boolean',
        'open_new_tab'       => 'boolean',
        'access_publikasi'   => 'boolean',
        'access_pendaftaran' => 'boolean',
        'banner_slides'      => 'array',
        'page_options'       => 'array',
    ];

    protected static ?Collection $all = null;

    protected static function booted(): void
    {
        static::saving(function (Menu $menu) {
            if (! $menu->slug) {
                $menu->slug = static::uniqueSlug($menu->title, $menu->id);
            }
        });

        static::saved(fn () => static::$all = null);
        static::deleted(fn () => static::$all = null);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'menu';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /* ---------- Relasi ---------- */

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('position');
    }

    public function blocks()
    {
        return $this->hasMany(Block::class)->orderBy('position');
    }

    /** Fitur bantuan pengunjung (pencarian, filter, urutkan, dst.) untuk halaman ini. */
    public function pageOptions(): array
    {
        // Koleksi kartu di Beranda memakai pilihan yang sama seperti halaman biasa
        return \App\Support\PageOptions::for($this, $this->type === 'beranda' ? 'halaman' : $this->type);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* ---------- Query ---------- */

    public function scopeRoots($q)
    {
        return $q->whereNull('parent_id')->orderBy('position');
    }

    /** Pohon navbar untuk website (hanya yang aktif). */
    public static function navTree(): Collection
    {
        return static::roots()->where('is_active', true)
            ->with(['children' => fn ($q) => $q->where('is_active', true)])
            ->get();
    }

    protected static function everything(): Collection
    {
        return static::$all ??= static::with('parent')->get();
    }

    /** Menu yang mewakili sebuah modul (dipakai untuk judul & banner halaman modul). */
    public static function forModule(string $type, ?string $param = null): ?Menu
    {
        $items = static::everything()->where('type', $type)->sortBy('position');

        return $items->first(fn ($m) => (string) $m->param === (string) $param && $m->isVisible())
            ?? $items->first(fn ($m) => $m->isVisible())
            ?? $items->first();
    }

    /** Modul tampil di website bila ada minimal satu menu aktif bertipe itu. */
    public static function moduleEnabled(string $type): bool
    {
        return static::everything()->where('type', $type)->contains(fn ($m) => $m->isVisible());
    }

    public function isVisible(): bool
    {
        return $this->is_active && (! $this->parent || $this->parent->is_active);
    }

    /* ---------- Atribut ---------- */

    public function getTypeLabelAttribute(): string
    {
        return Modules::label($this->type);
    }

    public function getTypeIconAttribute(): string
    {
        return $this->icon ?: Modules::icon($this->type);
    }

    public function getParamLabelAttribute(): ?string
    {
        if ($this->param === null || $this->param === '') {
            return null;
        }

        return Modules::paramOptions($this->type)[$this->param] ?? $this->param;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? media_url($this->banner) : null;
    }

    /**
     * Latar header yang aktif untuk ditampilkan di website.
     * Mengembalikan null bila jenis yang dipilih belum punya isi
     * (website lalu memakai gambar header bawaan dari Pengaturan).
     *
     * Hasil: ['type' => image|video|youtube|slider, 'poster' => url gambar diam, ...]
     */
    public function headerMedia(): ?array
    {
        $image = $this->banner ? media_url($this->banner) : null;
        $slides = array_values(array_filter((array) $this->banner_slides));

        switch ($this->banner_type) {
            case 'video':
                if ($this->banner_video) {
                    return ['type' => 'video', 'src' => media_url($this->banner_video), 'poster' => $image];
                }
                break;

            case 'youtube':
                if ($id = youtube_id($this->banner_youtube)) {
                    $query = http_build_query([
                        'autoplay' => 1, 'mute' => 1, 'loop' => 1, 'playlist' => $id, 'controls' => 0,
                        'disablekb' => 1, 'modestbranding' => 1, 'playsinline' => 1, 'rel' => 0, 'iv_load_policy' => 3,
                    ]);

                    return [
                        'type'   => 'youtube',
                        'src'    => 'https://www.youtube.com/embed/'.$id.'?'.$query,
                        'poster' => $image ?? 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg',
                    ];
                }
                break;

            case 'slider':
                if ($slides) {
                    $urls = array_map(fn ($path) => media_url($path), $slides);

                    return ['type' => 'slider', 'slides' => $urls, 'poster' => $urls[0]];
                }
                break;
        }

        return $image ? ['type' => 'image', 'src' => $image, 'poster' => $image] : null;
    }

    /** Lapisan warna bawaan semua halaman (Studio › Header halaman › "Pakai untuk semua halaman"). */
    public static function defaultOverlay(): string
    {
        $value = (string) setting('page_header_overlay');

        return array_key_exists($value, self::OVERLAYS) ? $value : 'sedang';
    }

    /** Lapisan warna header halaman ini: pilihan sendiri, atau ikut pengaturan semua halaman. */
    public function headerOverlay(): string
    {
        return array_key_exists((string) $this->banner_overlay, self::OVERLAYS) ? $this->banner_overlay : static::defaultOverlay();
    }

    /** Semua file latar header milik menu ini (untuk dihapus saat menu dihapus). */
    public function headerFiles(): array
    {
        return array_values(array_filter(array_merge(
            [$this->banner, $this->banner_video],
            (array) $this->banner_slides
        )));
    }

    public function getTargetAttribute(): string
    {
        return $this->open_new_tab ? '_blank' : '_self';
    }

    public function isPage(): bool
    {
        return Modules::isPageType($this->type);
    }

    public function isGroup(): bool
    {
        return $this->type === 'dropdown';
    }

    /**
     * Menu yang bisa diberi blok isi di Struktur & Konten.
     * Halaman konten & Beranda: blok adalah isi utamanya.
     * Halaman modul (Berita, Galeri, Kelulusan, dll.): blok menjadi "isi tambahan" di atas/bawah daftar.
     */
    public function supportsBlocks(): bool
    {
        return ! in_array($this->type, ['dropdown', 'tautan'], true);
    }

    /** Letak isi tambahan di halaman modul: atas | bawah */
    public function blocksAt(): string
    {
        return (($this->page_options ?? [])['blocks_at'] ?? 'bawah') === 'atas' ? 'atas' : 'bawah';
    }

    /** Alamat di website sesuai tipe isinya. */
    public function getLinkAttribute(): string
    {
        $p = $this->param;

        return match ($this->type) {
            'beranda'     => route('home'),
            'halaman'     => route('page.show', $this->slug),
            'berita'      => route('news.index', match (true) {
                $p === 'pendaftaran'                   => ['kanal' => 'pendaftaran'],
                Str::startsWith((string) $p, 'kategori:') => ['kategori' => Str::after($p, 'kategori:')],
                default                                => [],
            }),
            'galeri'      => route('gallery.index'),
            'kelulusan'   => route('graduates.index', $p ? ['jenjang' => $p] : []),
            'kalender'    => route('calendar.index', $p ? ['jenjang' => $p] : []),
            'guru_staf'   => route('staff.index'),
            'keunikan'    => route('uniqueness.index'),
            'fasilitas'   => route('facilities.index', $p ?: 'gedung'),
            'pendaftaran' => route('registration.index', $p ? ['untuk' => $p] : []),
            'cek_status'  => route('registration.status'),
            'tautan'      => $this->url ?: '#',
            default       => '#',
        };
    }

    /** Apakah link menu ini sedang dibuka pengunjung. */
    public function isCurrent(): bool
    {
        if ($this->isGroup()) {
            return $this->children->contains(fn ($c) => $c->isCurrent());
        }

        $link = $this->link;
        if ($link === '#' || $this->type === 'tautan') {
            return false;
        }

        $target = parse_url($link);
        $path = trim($target['path'] ?? '/', '/');
        $current = trim(request()->path(), '/');

        if ($path === '' || $this->type === 'beranda') {
            return $current === '';
        }

        if (! empty($target['query'])) {
            parse_str($target['query'], $query);
            foreach ($query as $k => $v) {
                if ((string) request()->query($k) !== (string) $v) {
                    return false;
                }
            }

            return $current === $path;
        }

        return $current === $path || Str::startsWith($current, $path.'/');
    }

    /** Alamat ruang kerja menu ini di dashboard (Studio > Isi Konten). */
    public function dashboardUrl(): string
    {
        return route('dashboard.studio.show', $this);
    }

    public function roleBadges(): array
    {
        return array_keys(array_filter([
            'publikasi'   => $this->access_publikasi,
            'pendaftaran' => $this->access_pendaftaran,
        ]));
    }
}