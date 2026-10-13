<?php

namespace App\Support;

use App\Models\Menu;

/**
 * Latar belakang halaman login (diatur di Pengaturan Website > Halaman Login).
 * Jenis: foto, slide foto, atau video. Disimpan di tabel settings:
 *  - login_bg_type   : image | slider | video
 *  - login_bg_image  : path foto (juga dipakai sebagai sampul saat video dimuat)
 *  - login_bg_slides : JSON daftar path foto slide
 *  - login_bg_video  : path video (mp4 / webm)
 *  - login_overlay   : tipis | sedang | pekat (lapisan warna di atas latar)
 */
class LoginBackground
{
    public const TYPES = [
        'image'  => ['label' => 'Foto', 'icon' => 'bi-image'],
        'slider' => ['label' => 'Slide foto', 'icon' => 'bi-collection-play'],
        'video'  => ['label' => 'Video', 'icon' => 'bi-camera-video'],
    ];

    public const OVERLAYS = [
        'tipis'  => 'Tipis (latar lebih terlihat)',
        'sedang' => 'Sedang',
        'pekat'  => 'Pekat (tulisan paling mudah dibaca)',
    ];

    /** Daftar path foto slide yang tersimpan. */
    public static function slides(): array
    {
        $slides = json_decode((string) setting('login_bg_slides'), true);

        return is_array($slides) ? array_values(array_filter($slides)) : [];
    }

    /** Foto yang dipakai bila admin belum mengunggah foto khusus login. */
    public static function fallbackImage(): string
    {
        $home = rescue(fn () => Menu::where('type', 'beranda')->value('banner'), null, false);

        return media_url($home ?: setting('hero_image'));
    }

    /**
     * Latar yang ditampilkan di halaman login.
     * Bila jenis yang dipilih belum punya isi, otomatis kembali ke foto.
     */
    public static function media(): array
    {
        $type = array_key_exists((string) setting('login_bg_type'), self::TYPES) ? setting('login_bg_type') : 'image';
        $image = setting('login_bg_image') ? media_url(setting('login_bg_image')) : self::fallbackImage();
        $overlay = array_key_exists((string) setting('login_overlay'), self::OVERLAYS) ? setting('login_overlay') : 'sedang';
        $slides = array_map(fn ($path) => media_url($path), self::slides());
        $video = setting('login_bg_video') ? media_url(setting('login_bg_video')) : null;

        if ($type === 'slider' && count($slides) >= 2) {
            return ['type' => 'slider', 'slides' => $slides, 'poster' => $slides[0], 'overlay' => $overlay];
        }

        if ($type === 'video' && $video) {
            return ['type' => 'video', 'src' => $video, 'poster' => $image, 'overlay' => $overlay];
        }

        return ['type' => 'image', 'src' => $image, 'poster' => $image, 'overlay' => $overlay];
    }
}