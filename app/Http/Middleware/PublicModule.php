<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Halaman modul di website hanya terbuka bila Master Admin menampilkannya di struktur menu.
 * Admin yang sedang login tetap bisa melihat (untuk pratinjau).
 */
class PublicModule
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (! Menu::moduleEnabled($type) && ! $request->user()) {
            abort(404);
        }

        return $next($request);
    }
}
