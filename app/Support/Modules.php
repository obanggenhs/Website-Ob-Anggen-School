<?php

namespace App\Support;

use App\Models\Category;

/**
 * Daftar jenis isi (tipe) untuk Menu Utama & Submenu website,
 * serta daftar hak kelola (modul) yang bisa dibagikan ke dashboard role lain.
 */
class Modules
{
    /**
     * Jenis isi menu.
     * - permission : modul dashboard yang ikut terbuka bila menu ini ditugaskan ke role lain
     * - params     : pilihan filter tambahan (mis. jenjang kalender)
     * - root_only  : hanya boleh sebagai Menu Utama
     * - single     : hanya boleh ada satu di seluruh website
     */
    public static function types(): array
    {
        return [
            'beranda' => [
                'label' => 'Beranda', 'icon' => 'bi-house-door',
                'desc' => 'Halaman depan: hero, sambutan, statistik, dan blok tambahan.',
                'permission' => null, 'root_only' => true, 'single' => true,
            ],
            'halaman' => [
                'label' => 'Halaman Konten', 'icon' => 'bi-file-earmark-richtext',
                'desc' => 'Halaman bebas yang disusun dari blok teks, gambar, video, daftar, galeri, dan kartu.',
                'permission' => null,
            ],
            'dropdown' => [
                'label' => 'Grup Dropdown', 'icon' => 'bi-menu-button-wide',
                'desc' => 'Menu utama yang hanya menampung submenu (tidak punya halaman sendiri).',
                'permission' => null, 'root_only' => true,
            ],
            'berita' => [
                'label' => 'Berita & Pengumuman', 'icon' => 'bi-newspaper',
                'desc' => 'Daftar berita. Bisa difilter per kategori atau khusus info pendaftaran.',
                'permission' => 'berita',
                'params' => ['' => 'Semua berita', 'pendaftaran' => 'Khusus info pendaftaran'],
            ],
            'galeri' => [
                'label' => 'Galeri Foto & Video', 'icon' => 'bi-images',
                'desc' => 'Album foto dan video kegiatan sekolah.',
                'permission' => 'galeri',
            ],
            'kelulusan' => [
                'label' => 'Kelulusan (Graduation)', 'icon' => 'bi-mortarboard',
                'desc' => 'Profil lulusan per tahun dan jenjang.',
                'permission' => 'kelulusan',
                'params' => ['' => 'Semua jenjang', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'],
            ],
            'kalender' => [
                'label' => 'Kalender Akademik', 'icon' => 'bi-calendar3',
                'desc' => 'Agenda akademik, ujian, libur, dan kegiatan.',
                'permission' => 'kalender',
                'params' => ['' => 'Semua jenjang', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'],
            ],
            'guru_staf' => [
                'label' => 'Guru & Staf', 'icon' => 'bi-people',
                'desc' => 'Profil pimpinan, guru, pembina asrama, dan staf.',
                'permission' => 'guru_staf',
            ],
            'keunikan' => [
                'label' => 'Keunikan Sekolah', 'icon' => 'bi-stars',
                'desc' => 'Program khas sekolah beserta ulasan/testimoni.',
                'permission' => 'keunikan',
            ],
            'fasilitas' => [
                'label' => 'Fasilitas', 'icon' => 'bi-building',
                'desc' => 'Gedung sekolah atau asrama per kampus.',
                'permission' => 'fasilitas',
                'params' => ['gedung' => 'Gedung sekolah', 'asrama' => 'Asrama'],
            ],
            'pendaftaran' => [
                'label' => 'Pendaftaran Online', 'icon' => 'bi-pencil-square',
                'desc' => 'Daftar jalur pendaftaran yang sedang dibuka beserta formulirnya.',
                'permission' => 'pendaftaran',
                'params' => ['' => 'Semua jalur', 'siswa' => 'Pendaftaran siswa', 'staf' => 'Rekrutmen guru & staf'],
            ],
            'cek_status' => [
                'label' => 'Cek Status Pendaftaran', 'icon' => 'bi-search',
                'desc' => 'Formulir bagi pendaftar untuk melihat status & jadwal seleksi.',
                'permission' => 'pendaftaran', 'single' => true,
            ],
            'tautan' => [
                'label' => 'Tautan Luar', 'icon' => 'bi-link-45deg',
                'desc' => 'Mengarah ke alamat lain, mis. e-learning atau media sosial.',
                'permission' => null,
            ],
        ];
    }

    public static function type(?string $key): array
    {
        return static::types()[$key] ?? static::types()['halaman'];
    }

    public static function label(?string $key): string
    {
        return static::type($key)['label'];
    }

    public static function icon(?string $key): string
    {
        return static::type($key)['icon'];
    }

    /** Pilihan filter untuk tipe tertentu (berita ditambah kategori). */
    public static function paramOptions(string $type): array
    {
        $options = static::type($type)['params'] ?? [];

        if ($type === 'berita') {
            try {
                foreach (Category::where('type', 'news')->orderBy('name')->get() as $cat) {
                    $options['kategori:'.$cat->slug] = 'Kategori: '.$cat->name;
                }
            } catch (\Throwable $e) {
                // tabel belum ada saat instalasi awal
            }
        }

        return $options;
    }

    /** Tipe yang punya halaman/isi sendiri untuk diedit lewat Studio. */
    public static function isPageType(?string $type): bool
    {
        return in_array($type, ['beranda', 'halaman'], true);
    }

    /**
     * Modul data di dashboard (hak kelola).
     */
    public static function permissions(): array
    {
        return [
            'berita'             => ['label' => 'Berita & Pengumuman', 'icon' => 'bi-newspaper', 'route' => ['dashboard.news.index', []]],
            'berita_pendaftaran' => ['label' => 'Berita Pendaftaran', 'icon' => 'bi-megaphone', 'route' => ['dashboard.news.index', ['kanal' => 'pendaftaran']]],
            'galeri'             => ['label' => 'Galeri', 'icon' => 'bi-images', 'route' => ['dashboard.gallery.index', []]],
            'kelulusan'          => ['label' => 'Kelulusan', 'icon' => 'bi-mortarboard', 'route' => ['dashboard.graduates.index', []]],
            'kalender'           => ['label' => 'Kalender Akademik', 'icon' => 'bi-calendar3', 'route' => ['dashboard.calendar.index', []]],
            'guru_staf'          => ['label' => 'Guru & Staf', 'icon' => 'bi-people', 'route' => ['dashboard.staff.index', []]],
            'keunikan'           => ['label' => 'Keunikan Sekolah', 'icon' => 'bi-stars', 'route' => ['dashboard.uniqueness.index', []]],
            'testimoni'          => ['label' => 'Ulasan / Testimoni', 'icon' => 'bi-chat-quote', 'route' => ['dashboard.testimonials.index', []]],
            'kampus'             => ['label' => 'Kampus / Cabang', 'icon' => 'bi-geo-alt', 'route' => ['dashboard.branches.index', []]],
            'fasilitas'          => ['label' => 'Fasilitas', 'icon' => 'bi-building', 'route' => ['dashboard.facilities.index', []]],
            'pendaftaran'        => ['label' => 'Data Pendaftar', 'icon' => 'bi-person-lines-fill', 'route' => ['dashboard.applicants.index', []]],
        ];
    }

    /** Hak kelola bawaan setiap role (tidak bisa dicabut). */
    public static function corePermissions(string $role): array
    {
        return match ($role) {
            'master'      => array_keys(static::permissions()),
            'publikasi'   => ['berita', 'galeri', 'kelulusan', 'kalender'],
            'pendaftaran' => ['pendaftaran', 'berita_pendaftaran'],
            default       => [],
        };
    }

    /** Modul dashboard yang terbuka bila sebuah menu bertipe $type ditugaskan. */
    public static function permissionForMenu(string $type, ?string $param = null): ?string
    {
        if ($type === 'berita' && $param === 'pendaftaran') {
            return 'berita_pendaftaran';
        }

        return static::type($type)['permission'] ?? null;
    }
}
