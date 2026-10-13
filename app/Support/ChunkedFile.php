<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * File hasil unggah bertahap (lihat ChunkUpload).
 * Diperlakukan sama seperti file unggahan biasa oleh validasi & controller,
 * tetapi berasal dari folder sementara di server, bukan dari form PHP langsung.
 */
class ChunkedFile extends UploadedFile
{
    public function __construct(string $path, string $originalName)
    {
        // Parameter terakhir (test = true): file sudah ada di server, jadi tidak dicek dengan is_uploaded_file()
        parent::__construct($path, $originalName, null, null, true);
    }
}
