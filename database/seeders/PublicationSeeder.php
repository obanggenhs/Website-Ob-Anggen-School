<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Category;
use App\Models\GalleryAlbum;
use App\Models\Graduate;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        $newsCats = collect(['Pengumuman', 'Kegiatan Sekolah', 'Prestasi'])
            ->mapWithKeys(fn ($n) => [$n => Category::firstOrCreate(['slug' => Str::slug($n), 'type' => 'news'], ['name' => $n])]);
        $galleryCats = collect(['Kegiatan Belajar', 'Asrama', 'Life Skill', 'OutTreat'])
            ->mapWithKeys(fn ($n) => [$n => Category::firstOrCreate(['slug' => Str::slug($n), 'type' => 'gallery'], ['name' => $n])]);

        $publikasi = User::where('role', 'publikasi')->first();
        $pendaftaran = User::where('role', 'pendaftaran')->first();

        $posts = [
            ['Tenaga pendidik Ob Anggen mengikuti pelatihan kurikulum', 'umum', 'Kegiatan Sekolah', 'assets/img/seed/group.jpg', false, 2],
            ['Siswa SMA mengikuti kegiatan OutTreat di Eragayam', 'umum', 'Kegiatan Sekolah', 'assets/img/seed/students.jpg', false, 6],
            ['Wisuda angkatan 2026: delapan lulusan melanjutkan kuliah', 'umum', 'Prestasi', 'assets/img/seed/graduate-boy.jpg', true, 9],
            ['Panen kebun Life Skill siswa asrama Bokondini', 'umum', 'Kegiatan Sekolah', 'assets/img/seed/family.jpg', false, 14],
            ['Pendaftaran siswa baru tahun ajaran 2027/2028 dibuka', 'pendaftaran', 'Pengumuman', 'assets/img/seed/students.jpg', true, 1],
            ['Jadwal wawancara calon siswa gelombang pertama', 'pendaftaran', 'Pengumuman', 'assets/img/seed/classroom.jpg', false, 4],
            ['Ob Anggen membuka lowongan guru dan pembina asrama', 'pendaftaran', 'Pengumuman', 'assets/img/seed/teacher.jpg', false, 7],
        ];

        foreach ($posts as [$title, $channel, $cat, $cover, $featured, $daysAgo]) {
            News::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title, 'channel' => $channel, 'category_id' => $newsCats[$cat]->id,
                'user_id' => ($channel === 'pendaftaran' ? $pendaftaran : $publikasi)?->id,
                'cover' => $cover, 'is_featured' => $featured, 'status' => 'published', 'published_at' => now()->subDays($daysAgo),
                'excerpt' => 'Kabar terbaru dari Ob Anggen School: '.Str::lower($title).'.',
                'body' => '<p>Kegiatan ini menjadi bagian dari komitmen Ob Anggen School untuk menghadirkan pendidikan yang berkualitas bagi anak-anak di Papua Pegunungan.</p><p>Para guru, pembina asrama, dan orang tua bekerja sama agar setiap siswa bertumbuh dalam iman, pengetahuan, dan keterampilan hidup.</p><blockquote>Pendidikan yang baik dimulai dari keluarga dan diteguhkan bersama sekolah.</blockquote><p>Informasi lebih lanjut dapat ditanyakan langsung ke kantor sekolah atau melalui WhatsApp resmi.</p>',
                'views' => random_int(20, 300),
            ]);
        }

        $albums = [
            ['Belajar di kelas Bokondini', 'Kegiatan Belajar', ['classroom.jpg', 'students.jpg', 'students.jpg', 'group.jpg']],
            ['Kehidupan asrama', 'Asrama', ['family.jpg', 'students.jpg', 'landscape.jpg']],
            ['Berkebun bersama', 'Life Skill', ['family.jpg', 'group.jpg', 'landscape.jpg']],
            ['OutTreat 2026', 'OutTreat', ['landscape.jpg', 'group.jpg', 'students.jpg', 'students.jpg']],
        ];
        foreach ($albums as $i => [$title, $cat, $photos]) {
            $album = GalleryAlbum::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title, 'category_id' => $galleryCats[$cat]->id, 'user_id' => $publikasi?->id,
                'description' => 'Dokumentasi '.Str::lower($title).' bersama siswa dan guru Ob Anggen.',
                'event_date' => now()->subDays(($i + 1) * 12), 'is_active' => true,
                'video_url' => $i === 3 ? 'https://www.youtube.com/watch?v=ysz5S6PUM-U' : null,
            ]);
            if ($album->photos()->count() === 0) {
                foreach ($photos as $p => $file) {
                    $album->photos()->create(['image' => 'assets/img/seed/'.$file, 'caption' => 'Foto '.($p + 1), 'position' => $p + 1]);
                }
            }
        }

        foreach (['Ruben Penggu', 'Naomi Kogoya', 'Yosua Wenda', 'Debora Tabuni', 'Samuel Yikwa', 'Rahel Karoba', 'Markus Gire', 'Ribka Enembe'] as $i => $name) {
            Graduate::updateOrCreate(['slug' => Str::slug($name.' 2026')], [
                'name' => $name, 'level' => 'sma', 'graduation_year' => 2026, 'class_name' => 'XII IPA',
                'predicate' => $i < 3 ? 'Sangat Memuaskan' : 'Memuaskan',
                'continue_to' => ['Universitas Cenderawasih', 'Universitas Pelita Harapan', 'Politeknik Negeri Jayapura', 'Bekerja di kampung'][$i % 4],
                'photo' => $i % 2 ? 'assets/img/seed/graduate-girl.jpg' : 'assets/img/seed/graduate-boy.jpg',
                'testimony' => 'Ob Anggen mengajarkan saya untuk tidak menyerah. Sekarang saya siap melangkah ke jenjang berikutnya.',
                'is_active' => true,
            ]);
        }
        foreach (['Yonas Kogoya', 'Lea Tabuni', 'Abner Wenda', 'Mince Yikwa'] as $i => $name) {
            Graduate::updateOrCreate(['slug' => Str::slug($name.' 2025')], [
                'name' => $name, 'level' => $i % 2 ? 'smp' : 'sma', 'graduation_year' => 2025, 'class_name' => $i % 2 ? 'IX' : 'XII IPS',
                'predicate' => 'Memuaskan', 'continue_to' => $i % 2 ? 'SMA Ob Anggen' : 'Universitas Cenderawasih',
                'photo' => $i % 2 ? 'assets/img/seed/graduate-girl.jpg' : 'assets/img/seed/graduate-boy.jpg', 'is_active' => true,
            ]);
        }

        CalendarEvent::query()->delete();
        $year = (int) date('Y');
        $academic = $year.'/'.($year + 1);
        $events = [
            ['Awal tahun ajaran', "$year-07-13", null, 'akademik', 'semua'],
            ['Ujian Tengah Semester SD', "$year-09-15", "$year-09-19", 'ujian', 'sd'],
            ['Ujian Tengah Semester SMP & SMA', "$year-09-21", "$year-09-26", 'ujian', 'semua'],
            ['Pentas seni sekolah', "$year-10-09", null, 'kegiatan', 'semua'],
            ['OutTreat siswa SMA', "$year-10-19", "$year-10-21", 'kegiatan', 'sma'],
            ['Hari Guru — ibadah syukur', "$year-11-25", null, 'kegiatan', 'semua'],
            ['Penilaian Akhir Semester', "$year-12-01", "$year-12-10", 'ujian', 'semua'],
            ['Pembagian rapor semester ganjil', "$year-12-17", null, 'akademik', 'semua'],
            ['Libur Natal & Tahun Baru', "$year-12-19", ($year + 1).'-01-04', 'libur', 'semua'],
            ['Awal semester genap', ($year + 1).'-01-11', null, 'akademik', 'semua'],
            ['Lomba cerdas cermat SD', ($year + 1).'-02-14', null, 'kegiatan', 'sd'],
            ['Ujian sekolah SMP', ($year + 1).'-03-09', ($year + 1).'-03-14', 'ujian', 'smp'],
        ];
        foreach ($events as [$title, $start, $end, $category, $level]) {
            CalendarEvent::create([
                'title' => $title, 'start_date' => $start, 'end_date' => $end, 'category' => $category, 'level' => $level,
                'academic_year' => $academic, 'time_info' => '08.00 WIT', 'location' => 'Kampus masing-masing', 'is_active' => true,
            ]);
        }
    }
}
