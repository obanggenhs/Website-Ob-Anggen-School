<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cek hak kelola modul dashboard, contoh: ->middleware('module:galeri')
 * Beberapa modul dipisah koma berarti cukup salah satu (mis. module:berita,berita_pendaftaran).
 */
class ModuleAccess
{
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        $user = $request->user();

        foreach ($modules as $module) {
            if ($user && $user->canManage($module)) {
                return $next($request);
            }
        }

        abort(403, 'Modul ini belum ditugaskan untuk akun Anda. Hubungi Master Admin.');
    }
}
