<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Support\MenuArchive;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Setelah admin menyimpan perubahan di Struktur & Konten (menu, isi halaman, blok, kartu),
 * data menu buatan admin di tabel menu_contents ikut diperbarui.
 * Gagal memperbarui arsip tidak membatalkan penyimpanan admin; kesalahannya dicatat di log.
 */
class SyncMenuArchive
{
    protected const ROUTES = ['dashboard.studio.', 'dashboard.blocks.', 'dashboard.cards.'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') || $request->isMethod('HEAD') || ! $request->user()) {
            return $response;
        }

        $name = (string) $request->route()?->getName();
        if (! Str::startsWith($name, self::ROUTES)) {
            return $response;
        }

        try {
            $menu = $request->route('menu');

            // Menu tertentu diubah → perbarui menu itu (dan submenunya).
            // Menu baru dibuat, dihapus, atau urutan/induk dipindah → bangun ulang semuanya.
            $menu instanceof Menu && Menu::whereKey($menu->id)->exists()
                ? MenuArchive::sync($menu)
                : MenuArchive::rebuild();
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }
}