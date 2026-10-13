# Website Resmi Sekolah Ob Anggen

Laravel 12 · PHP 8.2+ · MySQL · Blade (`@extends` / `@section`) · **Bootstrap 5.3** (HTML template, aset lokal, tanpa Tailwind)

Paket ini sudah berisi folder `vendor`, jadi **tidak perlu `composer install`** dan tidak perlu internet untuk menjalankannya.

---

## 1. Persyaratan

| Kebutuhan | Keterangan |
|---|---|
| PHP 8.2 / 8.3 / 8.4 | XAMPP 8.2+, Laragon, atau PHP CLI |
| MySQL 5.7+ / MariaDB 10.4+ | bawaan XAMPP / Laragon |
| Ekstensi PHP | `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `ctype`, `xml` |

XAMPP Windows: buka `C:\xampp\php\php.ini`, pastikan baris `extension=fileinfo`, `extension=pdo_mysql`, dan `extension=mbstring` **tidak** diawali `;`, lalu restart Apache.

## 2. Instalasi

Ekstrak zip, misalnya ke `C:\xampp\htdocs\oa-school`, lalu nyalakan **MySQL**.

**Cara A — otomatis**
Windows: klik dua kali `install.bat`, lalu `start.bat`. Linux/Mac: `bash install.sh`, lalu `php artisan serve`.

**Cara B — lewat terminal**
```bash
cd oa-school
php artisan oa:install      # membuat database oa-is-god-school, tabel, data awal, storage link
php artisan serve
```

**Cara C — impor SQL (phpMyAdmin)**
Buat database `oa-is-god-school` (collation `utf8mb4_unicode_ci`), lalu impor `database/oa-is-god-school.sql`.
Setelah itu jalankan `php artisan storage:link` dan `php artisan serve`.

Buka:
- Website: **http://localhost:8000**
- Dashboard: **http://localhost:8000/login**

| Role | Email | Kata sandi |
|---|---|---|
| Master Admin | master@obanggen.sch.id | password123 |
| Admin Publikasi | publikasi@obanggen.sch.id | password123 |
| Admin Pendaftaran | pendaftaran@obanggen.sch.id | password123 |

Bila MySQL memakai kata sandi atau port lain, ubah `DB_USERNAME`, `DB_PASSWORD`, `DB_PORT` di file `.env`, lalu jalankan `php artisan config:clear`.

---

## 3. Pembagian peran

| Fitur | Master | Publikasi | Pendaftaran |
|---|:-:|:-:|:-:|
| Studio Struktur & Konten (menu utama, submenu, isi halaman) | ✔ | menu yang ditugaskan | menu yang ditugaskan |
| Isi Beranda (hero, sambutan, statistik, video, bagian yang tampil) | ✔ | bila ditugaskan | bila ditugaskan |
| Pengaturan website (identitas, logo, kontak, warna, font) | ✔ | | |
| Akun admin (buat, ubah, nonaktifkan) & log aktivitas | ✔ | | |
| Berita & pengumuman umum, kategori | ✔ | ✔ | |
| Galeri (album foto + video) | ✔ | ✔ | |
| Kelulusan (Graduation), kalender akademik | ✔ | ✔ | |
| Guru & staf, keunikan, testimoni, kampus, fasilitas | ✔ | bila ditugaskan | bila ditugaskan |
| Jalur pendaftaran, buka/tutup, kuota, pembuat formulir | ✔ | | ✔ |
| Proses pendaftar (status, jadwal, catatan, CSV, cetak) | ✔ | | ✔ |
| Berita pendaftaran (pengumuman panitia) | ✔ | | ✔ |

"Bila ditugaskan" artinya Master Admin mencentang role tersebut di **Studio › [menu] › Akses Dashboard**.

---

## 4. Cara Master Admin mengelola website (Studio)

Menu: **Dashboard › Struktur & Konten**

Kiri: pohon **Menu Utama → Submenu** (bisa diseret untuk mengubah urutan).
Kanan: ruang kerja menu yang dipilih, dengan tiga tab:

1. **Isi Konten**
   - *Halaman Konten*: disusun dari blok (Teks, Gambar + Teks, Video, Daftar Poin, Kartu/Program, Galeri Foto, Kutipan, Ajakan/Tombol). Setiap blok punya lebar (penuh, 2/3, 1/2, 1/3), gaya (polos, kartu, disorot), dan bisa disembunyikan.
   - *Beranda*: hero, statistik, sambutan, video, pilihan bagian yang tampil, dan blok tambahan.
   - *Menu modul* (Berita, Galeri, Kalender, dll.): header halaman, teks pembuka, ringkasan data, dan tombol ke pengelolaan datanya.
2. **Pengaturan Menu** — nama, alamat (slug), jenis isi, letak (menu utama / submenu), filter (mis. Kalender SD), ikon, tampil/sembunyi, tombol menonjol di navbar, buka di tab baru, hapus.
3. **Akses Dashboard** — centang **Admin Publikasi** dan/atau **Admin Pendaftaran**. Menu itu otomatis muncul di sidebar mereka pada bagian **"Tugas dari Master"**, dan modul datanya ikut terbuka untuk mereka.

Jenis isi menu: Beranda, Halaman Konten, Grup Dropdown, Berita & Pengumuman, Galeri, Kelulusan, Kalender Akademik, Guru & Staf, Keunikan Sekolah, Fasilitas, Pendaftaran Online, Cek Status Pendaftaran, Tautan Luar.

Halaman modul di website (mis. `/galeri`) hanya terbuka untuk pengunjung bila ada menu aktif bertipe itu. Menyembunyikan menu = menyembunyikan fiturnya.

## 5. Alur Admin Publikasi
Dashboard "Ruang Redaksi": tulis berita (draf / terbit / terjadwal, tanda Penting), buat album dan unggah banyak foto sekaligus, tambah lulusan, tambah agenda kalender, serta mengisi halaman yang ditugaskan Master (contoh bawaan: *Prestasi Siswa*).

## 6. Alur Admin Pendaftaran
Dashboard "Meja Pendaftaran":
1. Atur **Jalur & Formulir**: buka/tutup, periode, kuota, persyaratan, tahapan, dan isian formulir (teks, pilihan, tanggal, unggah dokumen, dll.).
2. Pengunjung mengisi formulir di `/pendaftaran` → mendapat nomor (mis. `OA-2026-0009`) → email konfirmasi.
3. Buka **Data Pendaftar** → ubah status: *Baru → Berkas diverifikasi / Perlu perbaikan → Tes / wawancara → Diterima / Tidak diterima*, atur jadwal, tulis pesan untuk pendaftar, catatan internal, kirim WhatsApp, cetak kartu, unduh CSV.
4. Pendaftar memantau di `/pendaftaran/cek-status` (nomor + email/HP).
5. Terbitkan pengumuman lewat **Berita Pendaftaran**.

---

## 7. Struktur folder

```
oa-school/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── Web/                     ← website publik
│   │   │   │   ├── HomeController.php        PageController.php      NewsController.php
│   │   │   │   ├── GalleryController.php     GraduateController.php  CalendarController.php
│   │   │   │   ├── StaffController.php       UniquenessController.php FacilityController.php
│   │   │   │   └── RegistrationController.php
│   │   │   └── Dashboard/               ← dashboard 3 role
│   │   │       ├── HomeController.php        (beranda berbeda per role)
│   │   │       ├── StudioController.php      (struktur menu, isi, akses)
│   │   │       ├── BlockController.php       (blok isi halaman)
│   │   │       ├── NewsController.php        CategoryController.php  GalleryController.php
│   │   │       ├── GraduateController.php    CalendarController.php
│   │   │       ├── StaffController.php       UniquenessController.php TestimonialController.php
│   │   │       ├── BranchController.php      FacilityController.php
│   │   │       ├── ApplicantController.php   RegistrationTypeController.php
│   │   │       └── UserController.php        SettingController.php   ProfileController.php  ActivityController.php
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php        role:master
│   │       ├── ModuleAccess.php          module:galeri (hak kelola modul)
│   │       ├── PublicModule.php          public.module:berita (fitur tampil di website)
│   │       └── EnsureUserIsActive.php    akun nonaktif otomatis keluar
│   ├── Mail/                             RegistrationReceived, RegistrationStatusChanged
│   ├── Models/                           User, Setting, Menu, Block, News, Category, GalleryAlbum, GalleryPhoto,
│   │                                     Graduate, CalendarEvent, Branch, Staff, Uniqueness, Testimonial, Facility,
│   │                                     RegistrationType, RegistrationField, Registration, RegistrationLog, ActivityLog
│   ├── Providers/AppServiceProvider.php  (data navbar, sidebar, tema dashboard)
│   └── Support/
│       ├── Modules.php                   daftar jenis isi menu & hak kelola tiap role
│       ├── DashboardMenu.php             sidebar & tema dashboard per role
│       ├── SettingSchema.php             daftar pengaturan website & isi beranda
│       ├── Upload.php                    simpan/hapus gambar
│       └── helpers.php                   setting(), media_url(), tgl(), wa_link(), clean_html(), activity_log()
├── bootstrap/app.php                     alias middleware
├── config/
├── database/
│   ├── migrations/                       11 file migrasi
│   ├── seeders/                          data awal (akun, menu, halaman, berita, galeri, pendaftar contoh)
│   └── oa-is-god-school.sql              dump siap impor
├── lang/id/                              pesan validasi Bahasa Indonesia
├── public/
│   ├── index.php  .htaccess
│   └── assets/
│       ├── css/style.css                 tampilan website publik
│       ├── css/dashboard.css             tampilan dashboard (tema master / publikasi / pendaftaran)
│       ├── js/main.js  js/dashboard.js
│       ├── img/seed/                     foto & logo bawaan
│       └── vendor/                       bootstrap, bootstrap-icons, fonts, glightbox, summernote, jquery, sortablejs
├── resources/views/
│   ├── layouts/
│   │   ├── web.blade.php                 kerangka website (head, navbar, footer)
│   │   ├── web-page.blade.php            @extends web → header halaman + isi + sidebar
│   │   ├── web-full.blade.php            @extends web → header halaman + isi lebar penuh
│   │   ├── dashboard.blade.php           SATU layout untuk tiga role
│   │   ├── auth.blade.php  print.blade.php  error.blade.php
│   ├── web/                              halaman publik
│   │   ├── home.blade.php  page.blade.php
│   │   ├── partials/                     navbar, nav-item, footer, page-header, sidebar, block, intro, social, ridge
│   │   ├── news/  gallery/  graduates/  calendar/  staff/  uniqueness/  facilities/
│   │   └── registration/                 index, show, form, _field, success, status
│   ├── dashboard/
│   │   ├── home/                         master, publikasi, pendaftaran
│   │   ├── studio/                       index, show, _tree, _create-modal, _content-*, _settings, _access, block-form
│   │   ├── partials/                     sidebar, topbar, alerts, delete, empty, status
│   │   ├── news/ categories/ gallery/ graduates/ calendar/
│   │   ├── staff/ uniqueness/ testimonials/ branches/ facilities/
│   │   ├── applicants/                   index, show, print
│   │   ├── registration-types/           index, form, _fields, _field-inputs
│   │   └── users/ settings/ profile/ activity/
│   ├── components/form/                  input, textarea, select, editor, image, toggle, buttons (<x-form.*>)
│   ├── auth/login.blade.php
│   ├── emails/                           layout, registration-received, registration-status
│   └── errors/                           403, 404, 419, 429, 500, 503
├── routes/web.php                        rute website & /dashboard
├── routes/console.php                    perintah php artisan oa:install
├── install.bat  install.sh  start.bat
└── .env  .env.example                    DB_DATABASE=oa-is-god-school
```

## 8. Tabel database

| Tabel | Isi |
|---|---|
| `users` | akun + `role` (master / publikasi / pendaftaran), `is_active`, `created_by` |
| `settings` | pengaturan website & isi beranda (key–value) |
| `menus` | Menu Utama & Submenu: `type`, `param`, `parent_id`, `position`, header halaman, `access_publikasi`, `access_pendaftaran` |
| `blocks` | blok isi halaman milik sebuah menu |
| `news`, `categories` | berita (`channel` umum / pendaftaran) & kategori berita/galeri |
| `gallery_albums`, `gallery_photos` | album galeri dan fotonya |
| `graduates`, `calendar_events` | kelulusan & kalender akademik |
| `branches`, `staff`, `uniquenesses`, `testimonials`, `facilities` | profil sekolah |
| `registration_types`, `registration_fields` | jalur pendaftaran & isian formulirnya |
| `registrations`, `registration_logs` | data pendaftar & riwayat prosesnya |
| `activity_logs` | jejak aktivitas semua admin |

## 9. Layout & pemanggilan konten

Website publik memakai **tiga layout bertingkat**:

```blade
{{-- resources/views/web/page.blade.php --}}
@extends('layouts.web-page')          {{-- web-page sendiri @extends('layouts.web') --}}

@section('title', $menu->title)
@section('page')
    @foreach ($blocks as $b)
        @include('web.partials.block', ['b' => $b])
    @endforeach
@endsection
```

Dashboard memakai **satu** `layouts/dashboard.blade.php`. Perbedaan tiap role diatur oleh:
- class `<body class="theme-{role}">` → warna & gaya sidebar di `dashboard.css`,
- `App\Support\DashboardMenu::for($user)` → isi sidebar,
- `Dashboard\HomeController` → beranda `home/master`, `home/publikasi`, atau `home/pendaftaran`.

## 10. Email & WhatsApp
- Default `MAIL_MAILER=log`: email ditulis ke `storage/logs/laravel.log` (aman untuk uji coba).
- Untuk Gmail: aktifkan Verifikasi 2 Langkah, buat **Sandi aplikasi**, lalu di `.env` isi `MAIL_MAILER=smtp` dan `MAIL_PASSWORD="sandi aplikasi"`, kemudian `php artisan config:clear`.
- Tombol WhatsApp membuka `wa.me` dengan pesan yang sudah terisi.

## 11. Mengatasi masalah

| Gejala | Solusi |
|---|---|
| `could not find driver` | aktifkan `extension=pdo_mysql` di php.ini |
| `Access denied` / `Connection refused` | MySQL belum menyala atau `DB_USERNAME`/`DB_PASSWORD` di `.env` salah |
| `Unable to guess the MIME type` | aktifkan `extension=fileinfo` |
| Gambar unggahan tidak tampil | `php artisan storage:link` (Windows: terminal sebagai Administrator). Ada rute cadangan `/storage/...` bila tetap gagal |
| Tampilan tidak berubah setelah edit | `php artisan optimize:clear` |
| Error 419 | muat ulang halaman (sesi habis) |
| Ingin mengulang data awal | `php artisan oa:install --fresh` |
#   W e b s i t e - O b - A n g g e n - S c h o o l  
 