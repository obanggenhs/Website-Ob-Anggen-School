<?php

namespace App\Support;

/**
 * Semua pengaturan website yang bisa diubah Master Admin tanpa menyentuh kode.
 * Tipe field: text, textarea, editor, image, color, number, toggle, select, email, url
 */
class SettingSchema
{
    public static function tabs(): array
    {
        return [
            'identitas' => [
                'label' => 'Identitas & Logo', 'icon' => 'bi-building',
                'fields' => [
                    'site_name'        => ['label' => 'Nama sekolah', 'type' => 'text', 'default' => 'Ob Anggen School'],
                    'site_tagline'     => ['label' => 'Tagline', 'type' => 'text', 'default' => 'Education and family · Highlands of Papua'],
                    'logo_main'        => ['label' => 'Logo utama', 'type' => 'image', 'default' => 'assets/img/seed/logo.png'],
                    'logo_sd'          => ['label' => 'Logo SD', 'type' => 'image', 'default' => 'assets/img/seed/logo.png', 'col' => 4],
                    'logo_smp'         => ['label' => 'Logo SMP', 'type' => 'image', 'default' => 'assets/img/seed/logo.png', 'col' => 4],
                    'logo_sma'         => ['label' => 'Logo SMA', 'type' => 'image', 'default' => 'assets/img/seed/logo.png', 'col' => 4],
                    'favicon'          => ['label' => 'Favicon', 'type' => 'image', 'default' => 'assets/img/seed/logo.png'],
                    'meta_description' => ['label' => 'Deskripsi singkat (SEO)', 'type' => 'textarea', 'default' => 'Website resmi Ob Anggen School, sekolah Kristen di Papua Pegunungan.'],
                    'footer_about'     => ['label' => 'Teks singkat di footer', 'type' => 'textarea', 'default' => 'Sekolah pemuridan yang menyiapkan anak-anak Papua Pegunungan untuk berdampak bagi keluarga, gereja, dan masyarakat.'],
                ],
            ],
            'kontak' => [
                'label' => 'Kontak & Sosial Media', 'icon' => 'bi-telephone',
                'fields' => [
                    'contact_address'  => ['label' => 'Alamat', 'type' => 'textarea', 'default' => 'Jl. MAF, Bokondini, Kabupaten Tolikara, Papua Pegunungan'],
                    'contact_phone'    => ['label' => 'Telepon', 'type' => 'text', 'default' => '+62 812-0000-0000', 'col' => 6],
                    'contact_whatsapp' => ['label' => 'Nomor WhatsApp', 'type' => 'text', 'default' => '081200000000', 'col' => 6],
                    'contact_email'    => ['label' => 'Email', 'type' => 'email', 'default' => 'obanggenhs@gmail.com', 'col' => 6],
                    'maps_url'         => ['label' => 'Link Google Maps', 'type' => 'url', 'default' => '', 'col' => 6],
                    'social_facebook'  => ['label' => 'Facebook', 'type' => 'url', 'default' => 'https://facebook.com/', 'col' => 6],
                    'social_instagram' => ['label' => 'Instagram', 'type' => 'url', 'default' => 'https://instagram.com/', 'col' => 6],
                    'social_youtube'   => ['label' => 'YouTube', 'type' => 'url', 'default' => 'https://youtube.com/', 'col' => 6],
                    'social_tiktok'    => ['label' => 'TikTok', 'type' => 'url', 'default' => '', 'col' => 6],
                ],
            ],
            'tampilan' => [
                'label' => 'Warna & Tampilan', 'icon' => 'bi-palette',
                'fields' => [
                    'color_primary'      => ['label' => 'Warna utama', 'type' => 'color', 'default' => '#7A2B25', 'col' => 3],
                    'color_primary_dark' => ['label' => 'Warna utama (gelap)', 'type' => 'color', 'default' => '#5C1F1A', 'col' => 3],
                    'color_accent'       => ['label' => 'Warna aksen / tombol', 'type' => 'color', 'default' => '#E39B24', 'col' => 3],
                    'color_secondary'    => ['label' => 'Warna sekunder', 'type' => 'color', 'default' => '#2F5D50', 'col' => 3],
                    'color_bg'           => ['label' => 'Latar halaman', 'type' => 'color', 'default' => '#FBF5F1', 'col' => 3],
                    'color_footer'       => ['label' => 'Latar footer', 'type' => 'color', 'default' => '#2A1513', 'col' => 3],
                    'font_heading'       => ['label' => 'Font judul', 'type' => 'select', 'default' => 'Lexend', 'col' => 3, 'options' => ['Lexend' => 'Lexend', 'Poppins' => 'Poppins', 'Montserrat' => 'Montserrat', 'Merriweather' => 'Merriweather']],
                    'font_body'          => ['label' => 'Font isi', 'type' => 'select', 'default' => 'Nunito Sans', 'col' => 3, 'options' => ['Nunito Sans' => 'Nunito Sans', 'Inter' => 'Inter', 'Open Sans' => 'Open Sans', 'Source Sans 3' => 'Source Sans 3']],
                    'page_header_image'  => ['label' => 'Gambar header halaman (bawaan)', 'type' => 'image', 'default' => 'assets/img/seed/group.jpg'],
                    'layout_sidebar'     => ['label' => 'Tampilkan sidebar (berita terbaru & kontak) di halaman dalam', 'type' => 'toggle', 'default' => 1],
                ],
            ],
            'dashboard' => [
                'label' => 'Tampilan Dashboard', 'icon' => 'bi-window-sidebar',
                'fields' => [
                    'dash_color_master'      => ['label' => 'Warna dashboard Master Admin', 'type' => 'color', 'default' => '#7A2B25', 'col' => 4],
                    'dash_color_publikasi'   => ['label' => 'Warna dashboard Admin Publikasi', 'type' => 'color', 'default' => '#2F5D50', 'col' => 4],
                    'dash_color_pendaftaran' => ['label' => 'Warna dashboard Admin Pendaftaran', 'type' => 'color', 'default' => '#1F4E79', 'col' => 4],
                ],
            ],
            'login' => [
                'label' => 'Halaman Login', 'icon' => 'bi-box-arrow-in-right',
                'view'  => 'dashboard.settings._login',   // memakai tampilan khusus (foto / slide / video)
                'fields' => [
                    'login_bg_type'   => ['label' => 'Jenis latar', 'type' => 'select', 'default' => 'image', 'options' => ['image' => 'Foto', 'slider' => 'Slide foto', 'video' => 'Video']],
                    'login_bg_image'  => ['label' => 'Foto latar', 'type' => 'image', 'default' => ''],
                    'login_bg_slides' => ['label' => 'Slide foto', 'type' => 'slides', 'default' => ''],
                    'login_bg_video'  => ['label' => 'Video latar', 'type' => 'video', 'default' => ''],
                    'login_overlay'   => ['label' => 'Lapisan warna', 'type' => 'select', 'default' => 'sedang', 'options' => ['tipis' => 'Tipis', 'sedang' => 'Sedang', 'pekat' => 'Pekat']],
                ],
            ],
            'pesan' => [
                'label' => 'Email & WhatsApp', 'icon' => 'bi-send-check',
                'after' => 'dashboard.settings._test-send',   // kartu "Kirim tes" di bawah formulir
                'fields' => [
                    'reg_notify_email'  => ['label' => 'Email panitia / email resmi sekolah (menerima notifikasi pendaftar baru)', 'type' => 'email', 'default' => 'obanggenhs@gmail.com', 'col' => 7],
                    'reg_notify_wa'     => ['label' => 'No. WhatsApp panitia (menerima notifikasi pendaftar baru)', 'type' => 'text', 'default' => '', 'col' => 5],
                    'reg_send_wa'       => ['label' => 'Kirim WhatsApp otomatis (ke pendaftar saat status berubah, dan ke panitia saat ada pendaftar baru)', 'type' => 'toggle', 'default' => 1],
                    'wa_api_url'        => ['label' => 'Alamat API gateway WhatsApp (Fonnte: https://api.fonnte.com/send)', 'type' => 'text', 'default' => 'https://api.fonnte.com/send', 'col' => 7],
                    'wa_token'          => ['label' => 'Token gateway WhatsApp', 'type' => 'text', 'default' => '', 'col' => 5],
                    'mail_host'         => ['label' => 'Server email (SMTP host) — Gmail: smtp.gmail.com', 'type' => 'text', 'default' => 'smtp.gmail.com', 'col' => 6],
                    'mail_port'         => ['label' => 'Port — Gmail: 587', 'type' => 'number', 'default' => 587, 'col' => 2],
                    'mail_username'     => ['label' => 'Email pengirim (username SMTP)', 'type' => 'email', 'default' => '', 'col' => 4],
                    'mail_password'     => ['label' => 'Sandi aplikasi Gmail / password SMTP', 'type' => 'text', 'default' => '', 'col' => 6],
                    'mail_from_name'    => ['label' => 'Nama pengirim email', 'type' => 'text', 'default' => 'Panitia Ob Anggen School', 'col' => 6],
                ],
            ],
            'pendaftaran' => [
                'label' => 'Pendaftaran', 'icon' => 'bi-clipboard-check',
                'fields' => [
                    'reg_prefix'          => ['label' => 'Awalan nomor pendaftaran', 'type' => 'text', 'default' => 'OA', 'col' => 4],
                    'reg_send_mail'       => ['label' => 'Kirim email otomatis ke pendaftar saat status berubah', 'type' => 'toggle', 'default' => 1],
                    'reg_success_message' => ['label' => 'Pesan setelah formulir terkirim', 'type' => 'textarea', 'default' => 'Terima kasih, data pendaftaran sudah kami terima. Simpan nomor pendaftaran untuk memantau status seleksi.'],
                ],
            ],
        ];
    }

    /** Pengaturan isi Beranda (diedit lewat Studio > Beranda). */
    public static function home(): array
    {
        return [
            'hero_title'       => ['label' => 'Judul besar', 'type' => 'text', 'default' => 'Sekolah Pemuridan di Papua Pegunungan'],
            'hero_subtitle'    => ['label' => 'Kalimat pendukung', 'type' => 'textarea', 'default' => 'Sekolah Kristen yang menyiapkan anak-anak Papua menghadapi dunia global melalui pemberdayaan keluarga dan keterlibatan masyarakat.'],
            'hero_image'       => ['label' => 'Gambar latar hero', 'type' => 'image', 'default' => 'assets/img/seed/classroom.jpg'],
            'hero_button'      => ['label' => 'Teks tombol utama', 'type' => 'text', 'default' => 'Daftar sekarang'],
            'hero_button_url'  => ['label' => 'Link tombol utama', 'type' => 'text', 'default' => '/pendaftaran'],
            'stat_campus'      => ['label' => 'Jumlah kampus', 'type' => 'number', 'default' => 3],
            'stat_students'    => ['label' => 'Jumlah siswa', 'type' => 'number', 'default' => 275],
            'stat_teachers'    => ['label' => 'Jumlah guru & staf', 'type' => 'number', 'default' => 45],
            'welcome_title'    => ['label' => 'Judul sambutan', 'type' => 'text', 'default' => 'Selamat datang di Ob Anggen School'],
            'welcome_text'     => ['label' => 'Isi sambutan', 'type' => 'editor', 'default' => '<p>Ob Anggen adalah sekolah Kristen di Papua Pegunungan yang menyediakan pendidikan unggul untuk mempersiapkan anak-anak menghadapi dunia global, melalui pemberdayaan keluarga dan keterlibatan masyarakat.</p><p>Kami melayani sekitar 275 siswa bersama 45 guru dan staf di tiga kampus: Bokondini, Eragayam, dan Dogobak.</p>'],
            'welcome_image'    => ['label' => 'Foto sambutan', 'type' => 'image', 'default' => 'assets/img/seed/students.jpg'],
            'welcome_link'     => ['label' => 'Link "Baca selengkapnya"', 'type' => 'text', 'default' => '/halaman/sejarah'],
            'video_title'      => ['label' => 'Judul video', 'type' => 'text', 'default' => 'Tur video kehidupan di Ob Anggen'],
            'video_text'       => ['label' => 'Keterangan video', 'type' => 'textarea', 'default' => 'Lihat keseharian siswa, guru, dan keluarga Ob Anggen di tengah pegunungan Papua.'],
            'video_url'        => ['label' => 'Link YouTube', 'type' => 'url', 'default' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U'],
            'video_bg'         => ['label' => 'Gambar latar video', 'type' => 'image', 'default' => 'assets/img/seed/group.jpg'],
            'home_show_quick'        => ['label' => 'Akses cepat', 'type' => 'toggle', 'default' => 1],
            'home_show_branches'     => ['label' => 'Kampus / cabang', 'type' => 'toggle', 'default' => 1],
            'home_show_agenda'       => ['label' => 'Agenda mendatang', 'type' => 'toggle', 'default' => 1],
            'home_show_news'         => ['label' => 'Berita terbaru', 'type' => 'toggle', 'default' => 1],
            'home_show_video'        => ['label' => 'Video profil', 'type' => 'toggle', 'default' => 1],
            'home_show_testimonials' => ['label' => 'Testimoni', 'type' => 'toggle', 'default' => 1],
            'home_blocks_position'   => ['label' => 'Posisi blok tambahan', 'type' => 'select', 'default' => 'bottom', 'options' => ['top' => 'Setelah akses cepat', 'bottom' => 'Sebelum footer']],
        ];
    }

    /** Semua nilai bawaan (dipakai seeder & fungsi setting()). */
    public static function defaults(): array
    {
        $defaults = [];

        foreach (static::tabs() as $tab) {
            foreach ($tab['fields'] as $key => $field) {
                $defaults[$key] = $field['default'] ?? null;
            }
        }
        foreach (static::home() as $key => $field) {
            $defaults[$key] = $field['default'] ?? null;
        }

        return $defaults;
    }
}
