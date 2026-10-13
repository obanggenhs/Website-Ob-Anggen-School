<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $desc = 'Ruang belajar yang nyaman dengan pencahayaan alami, meja siswa, papan tulis, dan rak buku kelas.';

        foreach (Branch::all() as $branch) {
            $rooms = [['tk', 'Kelas TK', 'Ruang Kelas']];
            foreach (range(1, 6) as $n) {
                $rooms[] = ['sd', "SD Kelas {$n}", 'Ruang Kelas'];
            }
            foreach ([7, 8, 9] as $n) {
                $rooms[] = ['smp', "SMP Kelas {$n}", 'Ruang Kelas'];
            }
            $rooms[] = ['sma', 'Laboratorium Sains', 'Laboratorium'];
            $rooms[] = ['umum', 'Perpustakaan', 'Ruang Baca'];

            foreach ($rooms as $i => [$level, $name, $label]) {
                Facility::updateOrCreate(['slug' => Str::slug($branch->name.' '.$name)], [
                    'type' => 'gedung', 'branch_id' => $branch->id, 'level' => $level, 'name' => $name, 'label' => $label,
                    'description' => $desc, 'detail' => "<p>{$desc}</p><p>Ruangan dirawat bersama oleh siswa dan guru sebagai bagian dari pembentukan karakter.</p>",
                    'image' => $label === 'Ruang Kelas' ? 'assets/img/seed/classroom.jpg' : 'assets/img/seed/students.jpg',
                    'photos' => ['assets/img/seed/classroom.jpg', 'assets/img/seed/students.jpg'],
                    'capacity' => '30 siswa', 'area' => '64 m²',
                    'used_for' => $label === 'Laboratorium' ? 'Praktikum IPA' : 'Kegiatan belajar mengajar',
                    'position' => $i + 1, 'is_active' => true,
                ]);
            }

            foreach ([['Asrama Putra', 'Kamar Putra'], ['Asrama Putri', 'Kamar Putri'], ['Ruang Makan Asrama', 'Ruang Bersama']] as $i => [$name, $label]) {
                Facility::updateOrCreate(['slug' => Str::slug($branch->name.' '.$name)], [
                    'type' => 'asrama', 'branch_id' => $branch->id, 'level' => 'umum', 'name' => $name, 'label' => $label,
                    'description' => 'Asrama menjadi rumah kedua bagi siswa dengan pendampingan pembina setiap hari.',
                    'image' => 'assets/img/seed/family.jpg', 'photos' => ['assets/img/seed/family.jpg', 'assets/img/seed/group.jpg'],
                    'capacity' => '40 siswa', 'used_for' => 'Tempat tinggal & pembinaan karakter', 'position' => $i + 1, 'is_active' => true,
                ]);
            }
        }
    }
}
