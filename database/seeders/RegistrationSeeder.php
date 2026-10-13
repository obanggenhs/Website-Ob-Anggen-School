<?php

namespace Database\Seeders;

use App\Http\Controllers\Dashboard\RegistrationTypeController;
use App\Models\Registration;
use App\Models\RegistrationType;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $about = '<p>Ob Anggen adalah sekolah untuk anak-anak lokal di pegunungan Bokondini, Tolikara, Papua. Sekolah ini berfokus memuridkan siswa agar berdampak bagi kemuliaan Tuhan di tengah budayanya, sekaligus menyiapkan mereka untuk melanjutkan kuliah atau bekerja.</p>';
        $year = (int) date('Y');

        $types = [
            ['Pendaftaran Siswa SD', 'siswa-sd', 'siswa', 'SD', "Kartu Keluarga (KK)\nAkta kelahiran\nPasfoto terbaru", true],
            ['Pendaftaran Siswa SMP', 'siswa-smp', 'siswa', 'SMP', "Kartu Keluarga (KK)\nAkta kelahiran\nIjazah / SKL SD\nPasfoto terbaru", true],
            ['Pendaftaran Siswa SMA', 'siswa-sma', 'siswa', 'SMA', "Kartu Keluarga (KK)\nAkta kelahiran\nIjazah / SKL SMP\nPasfoto terbaru", true],
            ['Rekrutmen Guru', 'guru', 'staf', 'Guru', "Curriculum Vitae (CV)\nScan KTP\nScan ijazah terakhir\nSurat rekomendasi gereja", true],
            ['Rekrutmen Pembina Asrama', 'pembina-asrama', 'staf', 'Pembina Asrama', "Curriculum Vitae (CV)\nScan KTP\nSurat rekomendasi gereja", false],
        ];

        foreach ($types as $i => [$name, $slug, $audience, $level, $docs, $open]) {
            $type = RegistrationType::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'audience' => $audience, 'level_label' => $level,
                'academic_year' => ($year + 1).'/'.($year + 2),
                'image' => $audience === 'siswa' ? 'assets/img/seed/students.jpg' : 'assets/img/seed/teacher.jpg',
                'summary' => $audience === 'siswa'
                    ? "Penerimaan siswa baru jenjang {$level} tahun ajaran ".($year + 1).'/'.($year + 2).'.'
                    : "Kesempatan melayani sebagai {$level} di Papua Pegunungan.",
                'description' => $about,
                'requirements' => $docs,
                'steps' => $audience === 'siswa'
                    ? "Isi formulir online & unggah dokumen\nPanitia memverifikasi berkas (maks. 3 hari kerja)\nTes & wawancara bersama orang tua\nPengumuman hasil lewat menu Cek Status"
                    : "Isi formulir lamaran & unggah dokumen\nSeleksi berkas oleh tim\nWawancara & micro teaching\nPengumuman hasil lewat menu Cek Status",
                'accepted_message' => 'Silakan datang ke kantor sekolah membawa dokumen asli untuk daftar ulang paling lambat 14 hari setelah pengumuman.',
                'open_at' => now()->subMonth()->startOfMonth(), 'close_at' => now()->addMonths(3)->endOfMonth(),
                'quota' => $audience === 'siswa' ? 40 : 5, 'is_open' => $open, 'position' => $i + 1,
            ]);

            if ($type->fields()->count() === 0) {
                $type->fields()->createMany(RegistrationTypeController::defaultFields($audience));
            }
        }

        // Contoh pendaftar agar alur proses bisa langsung dicoba
        if (Registration::count() > 0) {
            return;
        }

        $admin = User::where('role', 'pendaftaran')->first();
        $samples = [
            ['Yosia Kogoya', 'siswa-sma', 'baru', 0],
            ['Elisabet Wenda', 'siswa-smp', 'baru', 1],
            ['Natan Tabuni', 'siswa-sma', 'verifikasi', 3],
            ['Hana Yikwa', 'siswa-sd', 'perbaikan', 4],
            ['Mikael Karoba', 'siswa-sma', 'seleksi', 6],
            ['Priskila Murib', 'siswa-smp', 'diterima', 10],
            ['Timotius Gire', 'guru', 'seleksi', 5],
            ['Debora Enembe', 'siswa-sd', 'ditolak', 12],
        ];

        foreach ($samples as $i => [$name, $slug, $status, $daysAgo]) {
            $type = RegistrationType::where('slug', $slug)->first();
            $reg = Registration::create([
                'registration_type_id' => $type->id,
                'number' => Registration::generateNumber(),
                'full_name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)).'@contoh.id',
                'phone' => '0812'.str_pad((string) (34560000 + $i * 1111), 8, '0', STR_PAD_LEFT),
                'data' => $type->audience === 'siswa'
                    ? ['tempat_lahir' => 'Bokondini', 'tanggal_lahir' => '2012-05-1'.$i, 'jenis_kelamin' => $i % 2 ? 'Perempuan' : 'Laki-laki', 'alamat' => 'Kampung Bokondini, Tolikara', 'asal_sekolah' => 'SD Inpres Bokondini', 'kampus' => 'Bokondini', 'nama_orang_tua' => 'Bapak '.explode(' ', $name)[1], 'hp_orang_tua' => '081290001234', 'pekerjaan_orang_tua' => 'Petani']
                    : ['tempat_lahir' => 'Wamena', 'tanggal_lahir' => '1995-03-12', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Wamena, Jayawijaya', 'posisi' => 'Guru Matematika', 'pendidikan' => 'S1', 'pengalaman' => 'Mengajar 3 tahun di SMP swasta', 'gereja' => 'GIDI Wamena'],
                'files' => [],
                'status' => $status,
                'schedule_at' => $status === 'seleksi' ? now()->addDays(3 + $i)->setTime(9, 0) : null,
                'schedule_place' => $status === 'seleksi' ? 'Ruang guru kampus Bokondini' : null,
                'public_note' => $status === 'perbaikan' ? 'Foto Kartu Keluarga kurang jelas. Mohon kirim ulang lewat WhatsApp panitia.' : null,
                'handled_by' => $status === 'baru' ? null : $admin?->id,
                'decided_at' => in_array($status, ['diterima', 'ditolak'], true) ? now()->subDays(1) : null,
            ]);
            $reg->forceFill(['created_at' => now()->subDays($daysAgo), 'updated_at' => now()->subDays($daysAgo)])->save();
            $reg->logs()->create(['status' => 'baru', 'title' => 'Formulir pendaftaran dikirim', 'is_public' => true, 'created_at' => now()->subDays($daysAgo)]);
            if ($status !== 'baru') {
                $reg->logs()->create(['user_id' => $admin?->id, 'status' => $status, 'title' => 'Status: '.Registration::STATUSES[$status]['label'], 'note' => $reg->public_note, 'is_public' => true]);
            }
        }
    }
}
