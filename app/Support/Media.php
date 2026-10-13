<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Bantuan untuk file media & dokumen di isi halaman:
 * aturan jenis file, simpan dokumen, label ukuran, ikon jenis file, dan pustaka pembaca PDF.
 *
 * Tidak ada batas ukuran maupun jumlah file dari aplikasi. File besar dikirim
 * bertahap dari dashboard (lihat ChunkUpload), jadi batas php.ini tidak berpengaruh.
 */
class Media
{
    /** Format video yang bisa diputar langsung di browser. */
    public const VIDEO_MIMES = 'mp4,m4v,webm,ogv,ogg,mov';

    /** Pustaka pembaca PDF (pdf.js). Dipakai file lokal bila ada, selain itu dari CDN. */
    public const PDFJS_VERSION = '3.11.174';

    /**
     * Aturan validasi untuk dokumen / file lain: semua jenis file boleh,
     * kecuali file yang bisa dijalankan sebagai program (lihat ChunkUpload::BLOCKED_EXTENSIONS).
     */
    public static function documentRules(bool $pdfOnly = false): array
    {
        return $pdfOnly ? ['file', 'mimes:pdf'] : ['file', static::safeFileRule()];
    }

    /** Tolak file berbahaya (php, exe, html, js, dst.) tanpa membatasi ukuran. */
    public static function safeFileRule(): \Closure
    {
        return function ($attribute, $file, $fail) {
            if ($file instanceof UploadedFile && ChunkUpload::isBlocked($file->getClientOriginalName())) {
                $fail('Jenis file ini tidak bisa diunggah karena bisa dijalankan sebagai program. Simpan sebagai ZIP atau PDF terlebih dahulu.');
            }
        };
    }

    /** Simpan dokumen dan kembalikan path, nama asli, dan ukurannya. */
    public static function storeDocument(?UploadedFile $file, string $folder): ?array
    {
        if (! $file || ! $file->isValid()) {
            return null;
        }

        // Nama & ukuran dibaca dulu, karena file besar langsung dipindah saat disimpan
        $name = Str::limit($file->getClientOriginalName(), 180, '');
        $size = $file->getSize();

        return [
            'document'      => Upload::store($file, $folder),
            'document_name' => $name,
            'document_size' => $size,
        ];
    }

    /** Ukuran file yang mudah dibaca, mis. 12,4 MB. */
    public static function sizeLabel(?int $bytes): string
    {
        if (! $bytes) {
            return '';
        }
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2, ',', '.').' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1, ',', '.').' MB';
        }

        return max(1, (int) round($bytes / 1024)).' KB';
    }

    public static function extension(?string $path): string
    {
        return strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
    }

    public static function isPdf(?string $path): bool
    {
        return static::extension($path) === 'pdf';
    }

    public static function isVideo(?string $path): bool
    {
        return in_array(static::extension($path), explode(',', self::VIDEO_MIMES), true);
    }

    /** Ikon & label jenis dokumen. */
    public static function docType(?string $path): array
    {
        $ext = static::extension($path);

        return match (true) {
            $ext === 'pdf'                                        => ['label' => 'PDF', 'icon' => 'bi-file-earmark-pdf', 'tone' => 'pdf'],
            in_array($ext, ['doc', 'docx', 'odt', 'rtf'], true)   => ['label' => 'Word', 'icon' => 'bi-file-earmark-word', 'tone' => 'word'],
            in_array($ext, ['xls', 'xlsx', 'ods', 'csv'], true)   => ['label' => $ext === 'csv' ? 'CSV' : 'Excel', 'icon' => 'bi-file-earmark-excel', 'tone' => 'excel'],
            in_array($ext, ['ppt', 'pptx', 'odp'], true)          => ['label' => 'PowerPoint', 'icon' => 'bi-file-earmark-ppt', 'tone' => 'ppt'],
            in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz'], true) => ['label' => strtoupper($ext), 'icon' => 'bi-file-earmark-zip', 'tone' => 'doc'],
            in_array($ext, ['mp3', 'wav', 'm4a', 'aac', 'oga', 'flac'], true) => ['label' => 'Audio', 'icon' => 'bi-file-earmark-music', 'tone' => 'doc'],
            in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tif', 'tiff', 'heic'], true) => ['label' => 'Gambar', 'icon' => 'bi-file-earmark-image', 'tone' => 'doc'],
            static::isVideo($path) || in_array($ext, ['avi', 'mkv', 'wmv', 'flv'], true) => ['label' => 'Video', 'icon' => 'bi-file-earmark-play', 'tone' => 'doc'],
            in_array($ext, ['txt', 'md'], true)                   => ['label' => 'Teks', 'icon' => 'bi-file-earmark-text', 'tone' => 'doc'],
            default                                               => ['label' => $ext !== '' ? strtoupper($ext) : 'Dokumen', 'icon' => 'bi-file-earmark', 'tone' => 'doc'],
        };
    }

    /** Nama dokumen yang enak dibaca dari nama file, mis. "Year-Book_2025.pdf" → "Year Book 2025". */
    public static function displayName(?string $name, ?string $path = null, string $fallback = 'Dokumen'): string
    {
        $base = pathinfo((string) ($name ?: $path), PATHINFO_FILENAME);
        $base = trim(preg_replace('/[\s_\-]+/', ' ', $base));

        return $base !== '' && ! preg_match('/^[A-Za-z0-9]{32}$/', $base) ? $base : $fallback;
    }

    /** Nama file saat diunduh: nama asli, atau judul + ekstensi. */
    public static function downloadName(?string $name, ?string $path, string $fallback = 'dokumen'): string
    {
        if ($name) {
            return $name;
        }

        return (Str::slug($fallback) ?: 'dokumen').'.'.(static::extension($path) ?: 'pdf');
    }

    /**
     * Alamat pustaka pdf.js untuk pembaca PDF.
     * Bila folder public/assets/vendor/pdfjs ada, dipakai file lokal; selain itu dari CDN jsDelivr.
     */
    public static function pdfjs(): array
    {
        if (is_file(public_path('assets/vendor/pdfjs/pdf.min.js'))) {
            $base = asset('assets/vendor/pdfjs');

            return [
                'lib'    => $base.'/pdf.min.js',
                'worker' => $base.'/pdf.worker.min.js',
                'cmaps'  => $base.'/cmaps/',
                'fonts'  => $base.'/standard_fonts/',
            ];
        }

        $cdn = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@'.self::PDFJS_VERSION;

        return [
            'lib'    => $cdn.'/build/pdf.min.js',
            'worker' => $cdn.'/build/pdf.worker.min.js',
            'cmaps'  => $cdn.'/cmaps/',
            'fonts'  => $cdn.'/standard_fonts/',
        ];
    }
}
