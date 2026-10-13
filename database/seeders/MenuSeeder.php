<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Struktur website awal: Menu Utama → Submenu, beserta isi halaman (blok).
 * Contoh pembagian tugas:
 *  - Informasi (Berita, Galeri, Prestasi) & Graduations  → juga dikelola Admin Publikasi
 *  - Seluruh isi menu Pendaftaran                        → juga dikelola Admin Pendaftaran
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::query()->delete();

        $p = 'publikasi';
        $d = 'pendaftaran';

        $tree = [
            ['Beranda', 'beranda', ['icon' => 'bi-house-door']],
            ['Tentang Kami', 'dropdown', [], [
                ['Profil & Sejarah', 'halaman', ['slug' => 'sejarah', 'subtitle' => 'Perjalanan sekolah pemuridan di Papua Pegunungan']],
                ['Visi dan Misi', 'halaman', ['slug' => 'visi-misi', 'subtitle' => 'Arah dan nilai yang kami pegang']],
                ['Keunikan Ob Anggen', 'keunikan', ['subtitle' => 'Program khas yang membentuk karakter siswa']],
                ['Guru & Staf', 'guru_staf', ['subtitle' => 'Pimpinan, guru, dan pembina yang melayani']],
            ]],
            ['Akademik', 'dropdown', [], [
                ['Kalender Akademik SD', 'kalender', ['param' => 'sd', 'subtitle' => 'Jadwal kegiatan jenjang SD']],
                ['Kalender Akademik SMP', 'kalender', ['param' => 'smp', 'subtitle' => 'Jadwal kegiatan jenjang SMP']],
                ['Kalender Akademik SMA', 'kalender', ['param' => 'sma', 'subtitle' => 'Jadwal kegiatan jenjang SMA']],
                ['Graduations', 'kelulusan', ['subtitle' => 'Kenali para lulusan Ob Anggen', 'access' => [$p]]],
            ]],
            ['Informasi', 'dropdown', ['access' => [$p]], [
                ['Berita', 'berita', ['subtitle' => 'Kabar terbaru dari kampus Ob Anggen', 'access' => [$p]]],
                ['Galeri', 'galeri', ['subtitle' => 'Potret kegiatan siswa, guru, dan asrama', 'access' => [$p]]],
                ['Prestasi Siswa', 'halaman', ['slug' => 'prestasi-siswa', 'subtitle' => 'Capaian siswa di bidang akademik dan non-akademik', 'access' => [$p]]],
            ]],
            ['Fasilitas', 'dropdown', [], [
                ['Gedung Sekolah', 'fasilitas', ['param' => 'gedung', 'subtitle' => 'Ruang kelas, laboratorium, dan perpustakaan tiap kampus']],
                ['Asrama', 'fasilitas', ['param' => 'asrama', 'subtitle' => 'Rumah kedua bagi siswa Ob Anggen']],
            ]],
            ['Pendaftaran', 'dropdown', ['is_highlight' => true, 'icon' => 'bi-pencil-square', 'access' => [$d]], [
                ['Pendaftaran Siswa', 'pendaftaran', ['param' => 'siswa', 'subtitle' => 'Penerimaan siswa baru SD, SMP, dan SMA', 'access' => [$d]]],
                ['Rekrutmen Guru & Staf', 'pendaftaran', ['param' => 'staf', 'subtitle' => 'Bergabung melayani bersama kami', 'access' => [$d]]],
                ['Info Pendaftaran', 'berita', ['param' => 'pendaftaran', 'subtitle' => 'Pengumuman resmi panitia pendaftaran', 'access' => [$d]]],
                ['Panduan Pendaftaran', 'halaman', ['slug' => 'panduan-pendaftaran', 'subtitle' => 'Langkah mudah mendaftar secara online', 'access' => [$d]]],
                ['Cek Status Pendaftaran', 'cek_status', ['subtitle' => 'Pantau hasil seleksi dengan nomor pendaftaran', 'access' => [$d]]],
            ]],
        ];

        foreach ($tree as $i => $node) {
            $parent = $this->make($node, null, $i + 1);
            foreach ($node[3] ?? [] as $j => $child) {
                $this->make($child, $parent->id, $j + 1);
            }
        }

        $this->pages();
    }

    protected function make(array $node, ?int $parentId, int $position): Menu
    {
        [$title, $type, $opt] = $node;
        $access = $opt['access'] ?? [];
        unset($opt['access']);

        return Menu::create($opt + [
            'title'              => $title,
            'type'               => $type,
            'parent_id'          => $parentId,
            'position'           => $position,
            'is_active'          => true,
            'show_sidebar'       => true,
            'access_publikasi'   => in_array('publikasi', $access, true),
            'access_pendaftaran' => in_array('pendaftaran', $access, true),
        ]);
    }

    /** Isi awal halaman (blok). */
    protected function pages(): void
    {
        $text = 'Ob Anggen berawal dari kerinduan keluarga-keluarga di Bokondini agar anak-anak mereka mendapat pendidikan yang baik tanpa harus meninggalkan kampung halaman. Bersama gereja setempat, sekolah kecil ini tumbuh menjadi tiga kampus yang melayani ratusan siswa.';

        Menu::where('slug', 'sejarah')->first()?->blocks()->createMany([
            ['type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U', 'caption' => 'Cerita singkat perjalanan Sekolah Ob Anggen', 'width' => 12, 'position' => 1],
            ['type' => 'teks', 'title' => 'Awal mula', 'body' => "<p>{$text}</p><p>Hingga hari ini, Ob Anggen tetap memegang prinsip bahwa keluarga adalah mitra utama sekolah dalam mendidik anak.</p>", 'align' => 'justify', 'width' => 12, 'position' => 2],
            ['type' => 'gambar_teks', 'title' => 'Tumbuh bersama masyarakat', 'body' => '<p>Sekolah dibangun bersama warga kampung: orang tua ikut mengangkat kayu, menyiapkan kebun, dan menjaga asrama. Kebersamaan inilah yang menjadi ciri khas Ob Anggen.</p>', 'image' => 'assets/img/seed/family.jpg', 'image_position' => 'right', 'width' => 12, 'position' => 3],
            ['type' => 'galeri', 'title' => 'Potret masa kini', 'images' => ['assets/img/seed/students.jpg', 'assets/img/seed/classroom.jpg', 'assets/img/seed/group.jpg', 'assets/img/seed/landscape.jpg'], 'width' => 12, 'position' => 4],
        ]);

        Menu::where('slug', 'visi-misi')->first()?->blocks()->createMany([
            ['type' => 'kutipan', 'title' => 'Visi', 'body' => 'Menjadi sekolah pemuridan yang melahirkan generasi Papua Pegunungan yang takut akan Tuhan, cerdas, dan berdampak bagi keluarga serta masyarakat.', 'width' => 6, 'style' => 'card', 'position' => 1],
            ['type' => 'daftar', 'title' => 'Misi', 'items' => "Menyelenggarakan pendidikan berkualitas berbasis nilai Kristiani\nMemberdayakan keluarga sebagai mitra utama pendidikan\nMengembangkan keterampilan hidup yang relevan dengan budaya lokal\nMembangun keterlibatan gereja dan masyarakat", 'width' => 6, 'style' => 'card', 'position' => 2],
            ['type' => 'kartu', 'title' => 'Nilai-nilai dasar', 'items' => "Iman | Bertumbuh dalam pengenalan akan Tuhan setiap hari | bi-heart\nKeluarga | Orang tua dan sekolah berjalan bersama | bi-people\nKetekunan | Tidak mudah menyerah menghadapi tantangan | bi-graph-up-arrow\nPelayanan | Belajar untuk memberi dampak bagi sesama | bi-hand-thumbs-up", 'width' => 12, 'position' => 3],
        ]);

        Menu::where('slug', 'prestasi-siswa')->first()?->blocks()->createMany([
            ['type' => 'teks', 'title' => 'Bangga dengan capaian siswa', 'body' => '<p>Halaman ini dikelola oleh Admin Publikasi. Setiap prestasi siswa, baik akademik, olahraga, seni, maupun pelayanan, dapat ditambahkan sebagai blok baru.</p>', 'width' => 12, 'position' => 1],
            ['type' => 'kartu', 'title' => 'Prestasi tahun ini', 'items' => "Juara 1 Cerdas Cermat | Tingkat Kabupaten Tolikara | bi-trophy\nBeasiswa Kuliah | 8 lulusan diterima di perguruan tinggi | bi-mortarboard\nFestival Budaya | Penampilan tari terbaik se-Papua Pegunungan | bi-music-note-beamed", 'width' => 12, 'position' => 2],
        ]);

        Menu::where('slug', 'panduan-pendaftaran')->first()?->blocks()->createMany([
            ['type' => 'daftar', 'title' => 'Langkah pendaftaran online', 'items' => "Pilih jalur pendaftaran yang sedang dibuka\nBaca persyaratan, lalu klik tombol Daftar\nIsi formulir dan unggah dokumen\nSimpan nomor pendaftaran yang muncul setelah formulir terkirim\nPantau status & jadwal seleksi lewat menu Cek Status", 'width' => 8, 'style' => 'card', 'position' => 1],
            ['type' => 'kutipan', 'title' => 'Butuh bantuan?', 'body' => 'Hubungi panitia lewat WhatsApp sekolah pada hari kerja pukul 08.00–15.00 WIT.', 'width' => 4, 'style' => 'accent', 'position' => 2],
            ['type' => 'tombol', 'title' => 'Siap mendaftar?', 'body' => '<p>Pendaftaran siswa baru tahun ajaran mendatang sedang dibuka.</p>', 'button_label' => 'Lihat jalur pendaftaran', 'button_url' => '/pendaftaran', 'width' => 12, 'position' => 3],
        ]);

        Menu::where('type', 'beranda')->first()?->blocks()->create([
            'type' => 'tombol', 'title' => 'Mari bergabung bersama keluarga Ob Anggen',
            'body' => '<p>Pendaftaran siswa baru dan rekrutmen guru sedang dibuka. Kenali jalurnya dan daftar secara online.</p>',
            'button_label' => 'Lihat pendaftaran', 'button_url' => '/pendaftaran', 'width' => 12, 'style' => 'accent', 'position' => 1,
        ]);
    }
}
