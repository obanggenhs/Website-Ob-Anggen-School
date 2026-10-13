<?php

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Support\SettingSchema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

if (! function_exists('setting')) {
    /** Ambil nilai pengaturan website (tersimpan di tabel settings, di-cache). */
    function setting(string $key, mixed $default = null): mixed
    {
        static $defaults = null;

        $value = Setting::allCached()[$key] ?? null;

        if ($value === null || $value === '') {
            $defaults ??= SettingSchema::defaults();

            return $default ?? ($defaults[$key] ?? null);
        }

        return $value;
    }
}

if (! function_exists('media_url')) {
    /** URL gambar: file upload (storage), file bawaan (assets/...), atau URL luar. */
    function media_url(?string $path, ?string $fallback = null): string
    {
        if (! $path) {
            return $fallback ?? asset('assets/img/placeholder.svg');
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('tgl')) {
    /** Format tanggal berbahasa Indonesia, mis. tgl($date) → 6 Oktober 2026 */
    function tgl(mixed $date, string $format = 'd F Y'): string
    {
        if (! $date) {
            return '-';
        }

        return Carbon::parse($date)->locale('id')->translatedFormat($format);
    }
}

if (! function_exists('lines')) {
    /** Pecah teks per baris menjadi array (baris kosong dibuang). */
    function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text))));
    }
}

if (! function_exists('wa_link')) {
    /** Link WhatsApp (wa.me) dengan pesan terisi. 0812... diubah menjadi 62812... */
    function wa_link(?string $phone, string $message = ''): string
    {
        $number = preg_replace('/\D/', '', (string) $phone);

        if (Str::startsWith($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number.($message !== '' ? '?text='.rawurlencode($message) : '');
    }
}

if (! function_exists('youtube_id')) {
    /** Ambil ID video (11 karakter) dari link YouTube biasa, pendek, embed, shorts, atau live. */
    function youtube_id(?string $url): ?string
    {
        if ($url && preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([\w-]{11})~', $url, $m)) {
            return $m[1];
        }

        return null;
    }
}

if (! function_exists('youtube_embed')) {
    /**
     * Ubah link YouTube menjadi link embed yang bisa dikendalikan JavaScript
     * (enablejsapi) sehingga video otomatis diputar saat kursor diarahkan ke video.
     * Video diputar tanpa suara (mute) karena browser melarang autoplay bersuara.
     */
    function youtube_embed(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $id = youtube_id($url);
        if (! $id) {
            return $url;
        }

        $params = http_build_query([
            'enablejsapi'    => 1,
            'mute'           => 1,
            'playsinline'    => 1,
            'rel'            => 0,
            'modestbranding' => 1,
            'loop'           => 1,
            'playlist'       => $id,
            'origin'         => request()->getSchemeAndHttpHost(),
        ]);

        return 'https://www.youtube.com/embed/'.$id.'?'.$params;
    }
}

if (! function_exists('youtube_thumb')) {
    function youtube_thumb(?string $url): ?string
    {
        $id = youtube_id($url);

        return $id ? 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg' : null;
    }
}

if (! function_exists('is_video_file')) {
    /** True bila link mengarah ke file video langsung (mp4/webm/ogg). */
    function is_video_file(?string $url): bool
    {
        return (bool) ($url && preg_match('~\.(mp4|webm|ogg)(\?.*)?$~i', $url));
    }
}

if (! function_exists('clean_html')) {
    /** Bersihkan HTML dari editor: buang script, atribut on*, dan javascript: */
    function clean_html(?string $html): string
    {
        if (! $html) {
            return '';
        }

        $html = preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\1>#is', '', $html);
        $html = preg_replace('#\son\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)#is', '', $html);
        $html = preg_replace('#(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2#i', '$1="#"', $html);

        // strike & font: hasil tombol Coret dan Warna tulisan di editor
        return strip_tags($html, '<p><br><b><strong><i><em><u><s><strike><font><a><ul><ol><li><h2><h3><h4><h5><blockquote><img><table><thead><tbody><tr><th><td><span><div><hr><sub><sup>');
    }
}

if (! function_exists('activity_log')) {
    /** Catat aktivitas admin (tampil di Log Aktivitas Master Admin). */
    function activity_log(string $action, ?string $subject = null): void
    {
        try {
            ActivityLog::create([
                'user_id'    => auth()->id(),
                'action'     => $action,
                'subject'    => Str::limit((string) $subject, 250),
                'url'        => Str::limit(request()->path(), 250),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}