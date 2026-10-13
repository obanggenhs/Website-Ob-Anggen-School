<?php

namespace App\Http\Middleware;

use App\Support\ChunkUpload;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mengubah isian _chunked[...] (hasil unggah bertahap dari dashboard) menjadi file unggahan biasa,
 * sehingga validasi dan controller tetap memakai $request->file('nama-isian') tanpa perubahan.
 * Hanya berlaku untuk pengguna yang sudah masuk; file sementara dihapus setelah permintaan selesai.
 */
class AttachChunkedUploads
{
    public function handle(Request $request, Closure $next): Response
    {
        $tokens = $request->input('_chunked');

        if (! is_array($tokens) || ! $request->user()) {
            return $next($request);
        }

        $used = [];
        $files = $this->resolve($tokens, $request->user()->id, $used);

        foreach ($files as $key => $value) {
            $request->files->set($key, $this->merge($request->files->get($key), $value));
        }
        $request->request->remove('_chunked');

        // Daftar file yang sudah dibaca Laravel dikosongkan agar file baru ikut terbaca
        (fn () => $this->convertedFiles = null)->call($request);

        try {
            return $next($request);
        } finally {
            foreach ($used as $file) {
                ChunkUpload::forget($file);
            }
        }
    }

    /** Ubah token menjadi file dengan susunan yang sama (mis. photos[] → daftar file). */
    protected function resolve(array $tokens, int $userId, array &$used): array
    {
        $result = [];

        foreach ($tokens as $key => $value) {
            if (is_array($value)) {
                $nested = $this->resolve($value, $userId, $used);
                if ($nested !== []) {
                    $result[$key] = $nested;
                }
            } elseif ($file = ChunkUpload::file($userId, (string) $value)) {
                $used[] = $file;
                $result[$key] = $file;
            }
        }

        return $result;
    }

    /** Gabungkan dengan file yang (mungkin) juga terkirim langsung pada isian yang sama. */
    protected function merge(mixed $existing, mixed $incoming): mixed
    {
        if (! is_array($existing) || ! is_array($incoming)) {
            return $incoming;
        }

        foreach ($incoming as $key => $value) {
            if (is_int($key)) {
                $existing[] = $value;
            } else {
                $existing[$key] = $this->merge($existing[$key] ?? null, $value);
            }
        }

        return $existing;
    }
}
