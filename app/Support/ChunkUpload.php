<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Unggah file besar sepotong demi sepotong (chunk) dari dashboard.
 *
 * Browser memotong file menjadi bagian kecil yang selalu lolos batas php.ini
 * (upload_max_filesize & post_max_size), lalu server menyambungnya kembali.
 * Dengan cara ini admin bisa mengunggah foto, video, PDF, dan file lain
 * berukuran berapa pun (yang membatasi hanya sisa ruang penyimpanan).
 *
 * Alur:
 *  1. dashboard.js mengirim potongan ke route dashboard.upload.chunk
 *  2. potongan disambung di storage/app/chunks/{id-pengguna}/{id-unggah}.part
 *  3. setelah lengkap, formulir dikirim dengan isian _chunked[nama-isian] = id-unggah
 *  4. middleware AttachChunkedUploads mengubahnya kembali menjadi file biasa
 *     sehingga controller tetap memakai $request->file('...') seperti biasa
 */
class ChunkUpload
{
    /** Ekstensi yang ditolak demi keamanan server & pengunjung (bisa dijalankan sebagai program/skrip). */
    public const BLOCKED_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'pht', 'phps', 'phar', 'inc',
        'cgi', 'pl', 'py', 'rb', 'sh', 'bash', 'asp', 'aspx', 'jsp', 'jspx', 'cfm',
        'exe', 'msi', 'bat', 'cmd', 'com', 'scr', 'vbs', 'vbe', 'ps1', 'jar', 'dll',
        'htaccess', 'htpasswd', 'html', 'htm', 'xhtml', 'shtml', 'svg', 'js', 'mjs',
    ];

    /** Ukuran satu potongan: di bawah batas php.ini, paling besar 8 MB, paling kecil 256 KB. */
    public static function chunkSize(): int
    {
        $limit = min(static::iniBytes(ini_get('upload_max_filesize')), static::iniBytes(ini_get('post_max_size')));
        $size = $limit - 256 * 1024;   // sisakan ruang untuk isian formulir lain

        return (int) max(256 * 1024, min(8 * 1024 * 1024, $size));
    }

    /** Ubah nilai php.ini (mis. "40M", "2G") menjadi byte. 0 / kosong berarti tidak dibatasi. */
    public static function iniBytes($value): int
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '0' || $value === '-1') {
            return PHP_INT_MAX;
        }

        $number = (float) $value;

        return (int) match (strtolower(substr($value, -1))) {
            'g'     => $number * 1073741824,
            'm'     => $number * 1048576,
            'k'     => $number * 1024,
            default => $number,
        };
    }

    /** Folder sementara milik satu pengguna. */
    public static function directory(int $userId): string
    {
        $dir = storage_path('app/chunks/'.$userId);
        File::ensureDirectoryExists($dir);

        return $dir;
    }

    public static function validId(?string $id): bool
    {
        return is_string($id) && preg_match('/^[A-Za-z0-9_-]{16,64}$/', $id) === 1;
    }

    /** File disimpan dengan nama acak + ekstensi aslinya, jadi cukup ekstensi terakhir yang diperiksa. */
    public static function isBlocked(?string $filename): bool
    {
        $ext = strtolower(pathinfo(trim((string) $filename), PATHINFO_EXTENSION));

        return $ext !== '' && in_array($ext, self::BLOCKED_EXTENSIONS, true);
    }

    /** Nama file asli yang aman disimpan. */
    public static function cleanName(string $name): string
    {
        $name = basename(str_replace('\\', '/', $name));
        $name = preg_replace('/[\x00-\x1F\x7F]+/u', '', $name);

        return Str::limit($name !== '' ? $name : 'file', 180, '');
    }

    /**
     * Sambungkan satu potongan ke file sementara.
     *
     * @return array{status:int, received:int, done:bool, token?:string, message?:string}
     */
    public static function append(int $userId, string $id, string $name, int $size, int $offset, string $chunkPath): array
    {
        $dir = static::directory($userId);
        $part = $dir.'/'.$id.'.part';

        if ($offset === 0) {
            static::cleanup($dir);
        }

        clearstatcache(true, $part);
        $current = is_file($part) ? filesize($part) : 0;
        $length = filesize($chunkPath);

        if ($current === $offset + $length && $offset + $length > 0) {
            // Potongan ini sudah diterima sebelumnya (dikirim ulang karena koneksi putus)
        } elseif ($offset === 0 && $current > 0) {
            @unlink($part);
            $current = 0;
        } elseif ($current !== $offset) {
            return ['status' => 409, 'received' => $current, 'done' => false, 'message' => 'Urutan potongan tidak cocok.'];
        }

        if ($current === $offset) {
            $in = fopen($chunkPath, 'rb');
            $out = fopen($part, 'ab');
            if (! $in || ! $out || stream_copy_to_stream($in, $out) === false) {
                return ['status' => 507, 'received' => $current, 'done' => false, 'message' => 'Server gagal menyimpan file. Periksa sisa ruang penyimpanan.'];
            }
            fclose($in);
            fclose($out);
        }

        clearstatcache(true, $part);
        $received = filesize($part);

        if ($received < $size) {
            return ['status' => 200, 'received' => $received, 'done' => false];
        }

        if ($received > $size) {
            @unlink($part);

            return ['status' => 422, 'received' => 0, 'done' => false, 'message' => 'Ukuran file tidak cocok. Silakan unggah ulang.'];
        }

        rename($part, $dir.'/'.$id.'.file');
        file_put_contents($dir.'/'.$id.'.json', json_encode(['name' => static::cleanName($name), 'size' => $size]));

        return ['status' => 200, 'received' => $received, 'done' => true, 'token' => $id];
    }

    /** File yang sudah lengkap, siap dipakai controller seperti file unggahan biasa. */
    public static function file(int $userId, string $token): ?ChunkedFile
    {
        if (! static::validId($token)) {
            return null;
        }

        $dir = storage_path('app/chunks/'.$userId);
        $path = $dir.'/'.$token.'.file';
        $meta = json_decode((string) @file_get_contents($dir.'/'.$token.'.json'), true) ?: [];

        if (! is_file($path)) {
            return null;
        }

        return new ChunkedFile($path, $meta['name'] ?? 'file');
    }

    /** Hapus file sementara setelah formulir selesai diproses. */
    public static function forget(ChunkedFile $file): void
    {
        $path = $file->getPathname();
        @unlink(preg_replace('/\.file$/', '.json', $path));
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /** Bersihkan potongan yang tertinggal lebih dari sehari (unggahan dibatalkan / gagal). */
    public static function cleanup(string $dir): void
    {
        foreach (glob($dir.'/*') ?: [] as $file) {
            if (is_file($file) && filemtime($file) < time() - 86400) {
                @unlink($file);
            }
        }
    }
}
