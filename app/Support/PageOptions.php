<?php

namespace App\Support;

use App\Models\Menu;
use Illuminate\Http\Request;

/**
 * Fitur bantuan pengunjung per halaman (diatur di Studio > [menu] > Isi Konten).
 * Contoh: halaman Alumni bisa diberi kotak pencarian, filter angkatan, filter tahun, urutkan, dll.
 *
 * Nilai bawaan setiap jenis halaman dibuat sama dengan tampilan sebelumnya,
 * jadi tidak ada yang berubah sampai admin mengubahnya sendiri.
 */
class PageOptions
{
    /** Daftar fitur. 'per_page' berupa pilihan angka, sisanya sakelar ya/tidak. */
    public const OPTIONS = [
        'search'   => ['label' => 'Kotak pencarian', 'icon' => 'bi-search', 'desc' => 'Pengunjung bisa mencari berdasarkan nama/judul, kategori, tahun, dan isi lainnya.'],
        'filter'   => ['label' => 'Filter kategori', 'icon' => 'bi-funnel', 'desc' => 'Tombol pilihan kategori di atas daftar.'],
        'year'     => ['label' => 'Filter tahun', 'icon' => 'bi-calendar3', 'desc' => 'Pilihan tahun untuk menyaring data.'],
        'sort'     => ['label' => 'Urutkan', 'icon' => 'bi-sort-down', 'desc' => 'Terbaru, terlama, nama A–Z, atau Z–A.'],
        'view'     => ['label' => 'Ganti tampilan kartu / daftar', 'icon' => 'bi-grid-3x2-gap', 'desc' => 'Pengunjung bisa memilih tampilan kotak-kotak atau daftar memanjang.'],
        'count'    => ['label' => 'Jumlah hasil', 'icon' => 'bi-123', 'desc' => 'Menampilkan keterangan mis. "Menampilkan 1–12 dari 40 data".'],
        'share'    => ['label' => 'Tombol bagikan', 'icon' => 'bi-share', 'desc' => 'Tombol WhatsApp, Facebook, dan salin link di halaman detail.'],
        'per_page' => ['label' => 'Jumlah per halaman', 'icon' => 'bi-collection', 'desc' => 'Banyaknya kartu sebelum pindah ke halaman berikutnya.'],
    ];

    public const PER_PAGE = [6, 9, 12, 18, 24, 36];

    /** Fitur yang tersedia untuk setiap jenis halaman. */
    public const SUPPORT = [
        'halaman'   => ['search', 'filter', 'year', 'sort', 'view', 'count', 'share', 'per_page'],
        'berita'    => ['search', 'filter', 'year', 'sort', 'view', 'count', 'share', 'per_page'],
        'galeri'    => ['search', 'filter', 'year', 'sort', 'count', 'per_page'],
        'kelulusan' => ['search', 'filter', 'year', 'sort', 'view', 'count', 'per_page'],
        'guru_staf' => ['search', 'filter', 'sort', 'count'],
        'fasilitas' => ['search', 'filter', 'sort', 'count'],
        'keunikan'  => ['search', 'sort', 'count'],
    ];

    /** Nilai bawaan (sama dengan tampilan sebelum fitur ini ada). */
    public const DEFAULTS = [
        'halaman'   => ['search' => 1, 'filter' => 1, 'year' => 0, 'sort' => 0, 'view' => 0, 'count' => 1, 'share' => 1, 'per_page' => 12],
        'berita'    => ['search' => 1, 'filter' => 1, 'year' => 0, 'sort' => 0, 'view' => 0, 'count' => 0, 'share' => 1, 'per_page' => 9],
        'galeri'    => ['search' => 0, 'filter' => 1, 'year' => 0, 'sort' => 0, 'count' => 0, 'per_page' => 18],
        'kelulusan' => ['search' => 1, 'filter' => 1, 'year' => 1, 'sort' => 0, 'view' => 0, 'count' => 0, 'per_page' => 18],
        'guru_staf' => ['search' => 1, 'filter' => 1, 'sort' => 0, 'count' => 0],
        'fasilitas' => ['search' => 0, 'filter' => 1, 'sort' => 0, 'count' => 0],
        'keunikan'  => ['search' => 0, 'sort' => 0, 'count' => 0],
    ];

    /** Nama fitur disesuaikan dengan isi halamannya. */
    public const LABELS = [
        'halaman'   => ['filter' => 'Filter kategori (mis. angkatan, kelompok)', 'year' => 'Filter tahun / angkatan', 'share' => 'Tombol bagikan di halaman detail kartu'],
        'berita'    => ['filter' => 'Filter kategori berita', 'year' => 'Filter tahun terbit', 'share' => 'Tombol bagikan di halaman berita'],
        'galeri'    => ['filter' => 'Filter kategori album', 'year' => 'Filter tahun kegiatan'],
        'kelulusan' => ['filter' => 'Filter jenjang (SD / SMP / SMA)', 'year' => 'Pilihan angkatan (tahun lulus)'],
        'guru_staf' => ['filter' => 'Filter kelompok & kampus'],
        'fasilitas' => ['filter' => 'Filter jenjang'],
    ];

    public static function supports(?string $type): bool
    {
        return isset(self::SUPPORT[$type]);
    }

    public static function label(string $type, string $key): string
    {
        return self::LABELS[$type][$key] ?? self::OPTIONS[$key]['label'];
    }

    /** Pilihan fitur yang berlaku untuk sebuah halaman. */
    public static function for(?Menu $menu, string $type): array
    {
        $defaults = self::DEFAULTS[$type] ?? [];
        $saved = (array) ($menu?->page_options ?? []);
        $options = [];

        foreach (self::SUPPORT[$type] ?? [] as $key) {
            $value = array_key_exists($key, $saved) ? $saved[$key] : ($defaults[$key] ?? 0);
            $options[$key] = $key === 'per_page'
                ? (in_array((int) $value, self::PER_PAGE, true) ? (int) $value : (int) ($defaults[$key] ?? 12))
                : (bool) $value;
        }

        return $options;
    }

    /** Pilihan urutan yang tersedia. */
    public static function sorts(string $type): array
    {
        $sorts = match ($type) {
            'guru_staf', 'fasilitas', 'keunikan' => ['urutan' => 'Urutan sekolah', 'az' => 'Nama A–Z', 'za' => 'Nama Z–A'],
            'kelulusan' => ['az' => 'Nama A–Z', 'za' => 'Nama Z–A', 'terbaru' => 'Angkatan terbaru', 'terlama' => 'Angkatan terlama'],
            'berita'    => ['terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'populer' => 'Paling banyak dibaca', 'az' => 'Judul A–Z', 'za' => 'Judul Z–A'],
            'halaman'   => ['urutan' => 'Urutan', 'terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'az' => 'Nama A–Z', 'za' => 'Nama Z–A'],
            default     => ['terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'az' => 'Judul A–Z', 'za' => 'Judul Z–A'],
        };

        return $sorts;
    }

    /**
     * Ambil kata kunci, kategori, tahun, urutan, dan tampilan dari alamat halaman.
     * Nilai dari fitur yang dimatikan diabaikan.
     */
    public static function input(Request $request, array $options, string $type): array
    {
        $sorts = self::sorts($type);
        $sort = (string) $request->query('urut');

        return [
            'cari'   => ! empty($options['search']) ? trim((string) $request->query('cari')) : '',
            'tahun'  => ! empty($options['year']) && preg_match('/^\d{4}$/', (string) $request->query('tahun')) ? (string) $request->query('tahun') : '',
            'urut'   => ! empty($options['sort']) && array_key_exists($sort, $sorts) ? $sort : array_key_first($sorts),
            'tampil' => ! empty($options['view']) && $request->query('tampil') === 'daftar' ? 'daftar' : 'kartu',
        ];
    }

    /** Teks jumlah hasil, mis. "Menampilkan 1–12 dari 40 data". */
    public static function countText(mixed $result, string $noun = 'data'): string
    {
        if ($result instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            if ($result->total() === 0) {
                return 'Tidak ada '.$noun.' yang cocok';
            }

            return 'Menampilkan '.$result->firstItem().'–'.$result->lastItem().' dari '.$result->total().' '.$noun;
        }

        $total = is_countable($result) ? count($result) : (int) $result;

        return $total ? $total.' '.$noun.' ditemukan' : 'Tidak ada '.$noun.' yang cocok';
    }
}
