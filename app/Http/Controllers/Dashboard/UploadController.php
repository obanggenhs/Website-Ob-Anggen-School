<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\ChunkUpload;
use Illuminate\Http\Request;

/**
 * Menerima potongan file dari dashboard (unggah bertahap).
 * Dipakai semua admin untuk foto, video, PDF, dan file lain tanpa batas ukuran.
 */
class UploadController extends Controller
{
    public function chunk(Request $request)
    {
        $data = $request->validate([
            'upload_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]{16,64}$/'],
            'name'      => ['required', 'string', 'max:255'],
            'size'      => ['required', 'integer', 'min:1'],
            'offset'    => ['required', 'integer', 'min:0'],
            'chunk'     => ['required', 'file'],
        ]);

        if (ChunkUpload::isBlocked($data['name'])) {
            return response()->json([
                'message' => 'File "'.ChunkUpload::cleanName($data['name']).'" tidak bisa diunggah karena jenisnya bisa dijalankan sebagai program. Simpan sebagai ZIP atau PDF terlebih dahulu.',
            ], 422);
        }

        $result = ChunkUpload::append(
            $request->user()->id,
            $data['upload_id'],
            $data['name'],
            (int) $data['size'],
            (int) $data['offset'],
            $request->file('chunk')->getRealPath(),
        );

        return response()->json($result, $result['status']);
    }
}
