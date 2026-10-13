# Website Resmi Sekolah Ob Anggen

Aplikasi Web Laravel 12 · PHP 8.2+ · MySQL · Blade (`@extends` / `@section`) · Bootstrap 5.3 (HTML template, aset lokal, tanpa Tailwind).

Paket ini sudah berisi folder `vendor`, jadi **tidak perlu `composer install`** dan tidak perlu internet untuk menjalankannya.

---

## 1. Persyaratan Sistem

| Kebutuhan | Keterangan |
| :--- | :--- |
| **PHP** | 8.2 / 8.3 / 8.4 (XAMPP 8.2+, Laragon, atau PHP CLI) |
| **Database** | MySQL 5.7+ / MariaDB 10.4+ (bawaan XAMPP / Laragon) |
| **Ekstensi PHP** | `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `ctype`, `xml` |

> **Catatan XAMPP Windows:** Buka `C:\xampp\php\php.ini`, pastikan baris `extension=fileinfo`, `extension=pdo_mysql`, dan `extension=mbstring` tidak diawali tanda titik koma (`;`), lalu restart Apache.

---

## 2. Cara Menjalankan

Ekstrak zip/proyek ke direktori web server (misalnya `C:\xampp\htdocs\oa-school`), lalu nyalakan **MySQL**.

- **Cara A (Otomatis Windows):** Klik dua kali `install.bat`, lalu `start.bat`.
- **Cara B (Terminal):**
- **Cara C (Manual):** Buat database `oa-is-god-school`, lalu impor file `database/oa-is-god-school.sql`. Jalankan perintah `php artisan storage:link` dan `php artisan serve`.

Akses website:
- **Website Publik:** [http://localhost:8000](http://localhost:8000)
- **Halaman Login Dashboard:** [http://localhost:8000/login](http://localhost:8000/login)

---

## 3. Akun Akses Default (Role, Email & Password)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Master Admin** | `master@obanggen.sch.id` | `password123` |
| **Admin Publikasi** | `publikasi@obanggen.sch.id` | `password123` |
| **Admin Pendaftaran** | `pendaftaran@obanggen.sch.id` | `password123` |

*Bila MySQL memakai kata sandi atau port lain, sesuaikan `DB_USERNAME`, `DB_PASSWORD`, dan `DB_PORT` di file `.env`, lalu jalankan `php artisan config:clear`.*

---

## 4. Pembagian Peran & Hak Akses Fitur

| Fitur / Modul | Master Admin | Publikasi | Pendaftaran |
| :--- | :---: | :---: | :---: |
| Studio Struktur & Konten | ✅ | Bila ditugaskan | Bila ditugaskan |
| Isi Beranda (hero, sambutan, statistik, video) | ✅ | Bila ditugaskan | Bila ditugaskan |
| Pengaturan Website (identitas, logo, kontak, font) | ✅ | ❌ | ❌ |
| Akun Admin & Log Aktivitas | ✅ | ❌ | ❌ |
| Berita & Pengumuman (kategori, galeri) | ✅ | ✅ | ❌ |
| Galeri Album (Foto & Video) | ✅ | ✅ | ❌ |
| Kelulusan & Kalender Akademik | ✅ | ✅ | ❌ |
| Guru & Staf, Fasilitas | ✅ | Bila ditugaskan | Bila ditugaskan |
| Jalur & Kuota Pendaftaran, Pembuat Formulir | ✅ | ❌ | ✅ |
| Proses Pendaftar (status, jadwal, cetak kartu, CSV) | ✅ | ❌ | ✅ |
| Berita Pendaftaran | ✅ | ✅ | ✅ |

> **Keterangan:** *"Bila ditugaskan"* artinya Master Admin mencentang role tersebut di menu **Studio > Menu > Akses Dashboard**.

---

## 5. Mengatasi Masalah (Troubleshooting)

| Gejala Masalah | Solusi |
| :--- | :--- |
| `could not find driver` | Aktifkan `extension=pdo_mysql` di `php.ini`. |
| `Access denied` / `Connection refused` | MySQL belum menyala atau periksa setelan database di file `.env`. |
| `Unable to guess the MIME type` | Aktifkan `extension=fileinfo` di `php.ini`. |
| Gambar unggahan tidak tampil | Jalankan `php artisan storage:link` di terminal administrator. |
| Tampilan tidak berubah setelah diedit | Jalankan `php artisan optimize:clear`. |
| Error 419 (Page Expired) | Muat ulang halaman (sesi login habis). |
| Ingin mereset data awal | Jalankan `php artisan oa:install --fresh`. |