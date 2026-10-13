<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Models\Uniqueness;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $story = 'Ruben adalah anak yang pendiam di sekolah. Keluarganya berasal dari latar belakang yang sangat sederhana, namun orang tuanya membayangkan masa depan yang cerah. Ketekunan membantunya bangkit setiap kali menghadapi kesulitan, dan ia terus mencoba lagi sampai berhasil.';

        foreach ([
            ['Bokondini', 'Kabupaten Tolikara'],
            ['Eragayam', 'Kabupaten Mamberamo Tengah'],
            ['Dogobak', 'Kabupaten Mamberamo Tengah'],
        ] as $i => [$name, $regency]) {
            Branch::updateOrCreate(['name' => $name], [
                'regency' => $regency, 'image' => 'assets/img/seed/landscape.jpg', 'position' => $i + 1, 'is_active' => true,
                'description' => "Kampus {$name} melayani anak-anak dari kampung sekitar mulai jenjang SD sampai SMA, lengkap dengan asrama.",
            ]);
        }

        $programs = [
            ['Gereja dengan Sekolah', 'bi-house-heart', 'Sekolah tumbuh bersama jemaat lokal sehingga pendidikan dan iman berjalan beriringan.'],
            ['KGOT', 'bi-book', 'Kelompok belajar firman yang membentuk karakter siswa sejak dini.'],
            ['Life Skill', 'bi-tools', 'Siswa belajar bertani, beternak, dan keterampilan praktis yang berguna di kampung.'],
            ['OutTreat', 'bi-tree', 'Kegiatan di alam terbuka untuk melatih kepemimpinan, kerja sama, dan keberanian.'],
        ];
        foreach ($programs as $i => [$title, $icon, $excerpt]) {
            Uniqueness::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title, 'icon' => $icon, 'excerpt' => $excerpt,
                'content' => "<p>{$excerpt}</p><p>{$story}</p>",
                'image' => 'assets/img/seed/family.jpg',
                'photos' => ['assets/img/seed/students.jpg', 'assets/img/seed/classroom.jpg'],
                'video_url' => $i === 0 ? 'https://www.youtube.com/watch?v=ysz5S6PUM-U' : null,
                'position' => $i + 1, 'is_active' => true,
            ]);
        }

        foreach ([
            ['Rosa Pagawak', 'Alumni 2024', 'assets/img/seed/graduate-girl.jpg'],
            ['Ruben Penggu', 'Alumni 2026', 'assets/img/seed/graduate-boy.jpg'],
            ['Maria Wenda', 'Orang tua siswa', 'assets/img/seed/graduate-girl.jpg'],
        ] as $i => [$name, $role, $photo]) {
            Testimonial::updateOrCreate(['name' => $name], [
                'role' => $role, 'photo' => $photo, 'position' => $i + 1, 'is_active' => true, 'show_on_home' => true,
                'content' => 'Sekolah ini mengajarkan saya untuk tidak mudah menyerah. Guru-guru dan pembina asrama mendampingi kami seperti keluarga sendiri, sampai kami berani bermimpi lebih tinggi.',
            ]);
        }

        $branch = Branch::pluck('id', 'name');
        $people = [
            ['Yohana Kogoya', 'pimpinan', 'Kepala Sekolah SD Bokondini', 'Bokondini', true],
            ['Daniel Tabuni', 'pimpinan', 'Kepala Sekolah SD Eragayam', 'Eragayam', true],
            ['Selina Wenda', 'pimpinan', 'Kepala Sekolah SD Dogobak', 'Dogobak', true],
            ['Martha Yikwa', 'pimpinan', 'Kepala Sekolah SMP Bokondini', 'Bokondini', true],
            ['Ester Wanimbo', 'guru_sma', 'Guru Matematika', 'Bokondini', false],
            ['Yakob Enembe', 'guru_smp', 'Guru IPA', 'Bokondini', false],
            ['Lince Karoba', 'guru_sd', 'Guru Kelas 3', 'Eragayam', false],
            ['Petrus Gire', 'pembina_asrama', 'Pembina Asrama Putra', 'Bokondini', false],
            ['Agustina Murib', 'staf', 'Staf Tata Usaha', 'Bokondini', false],
        ];
        foreach ($people as $i => [$name, $category, $title, $campus, $featured]) {
            Staff::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'category' => $category, 'position_title' => $title, 'branch_id' => $branch[$campus] ?? null,
                'subject' => str_starts_with($category, 'guru') ? $title : null, 'education' => 'S1 Pendidikan',
                'email' => Str::slug($name, '.').'@obanggen.sch.id', 'joined_year' => 2016 + $i % 6,
                'about' => 'Melayani di Ob Anggen dengan semangat mendidik anak-anak Papua Pegunungan agar bertumbuh dalam iman, ilmu, dan karakter.',
                'duties' => "Mengajar dan mendampingi siswa\nMembina kegiatan ekstrakurikuler\nBerkoordinasi dengan orang tua siswa",
                'photo' => $i % 2 ? 'assets/img/seed/teacher2.jpg' : 'assets/img/seed/teacher.jpg',
                'is_featured' => $featured, 'position' => $i + 1, 'is_active' => true,
            ]);
        }
    }
}
