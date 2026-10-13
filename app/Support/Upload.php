<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Simpan & hapus file website: foto, video, dokumen (disk public → storage/app/public).
 * Tidak ada batas ukuran dari aplikasi; file besar dikirim bertahap (lihat ChunkUpload).
 */
class Upload
{
    public static function store(?UploadedFile $file, string $folder): ?string
    {
        if (! $file || ! $file->isValid()) {
            return null;
        }

        $name = Str::random(32).'.'.strtolower($file->getClientOriginalExtension() ?: ($file->extension() ?: 'bin'));
        $path = trim($folder, '/').'/'.$name;

        // File hasil unggah bertahap sudah ada di server: cukup dipindah (cepat walau ukurannya ratusan MB)
        if ($file instanceof ChunkedFile) {
            $target = Storage::disk('public')->path($path);
            File::ensureDirectoryExists(dirname($target));

            if (@rename($file->getPathname(), $target)) {
                return $path;
            }
        }

        return $file->storeAs($folder, $name, 'public');
    }

    /** Ganti file lama dengan file baru (bila ada), lalu kembalikan path terbaru. */
    public static function replace(?UploadedFile $file, ?string $old, string $folder): ?string
    {
        if (! $file) {
            return $old;
        }

        static::delete($old);

        return static::store($file, $folder);
    }

    public static function delete(?string $path): void
    {
        // File contoh bawaan (assets/...) dan URL luar tidak dihapus
        if (! $path || Str::startsWith($path, ['assets/', 'http://', 'https://'])) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    public static function deleteMany(?array $paths): void
    {
        foreach ((array) $paths as $path) {
            static::delete(is_array($path) ? ($path['image'] ?? null) : $path);
        }
    }
}
