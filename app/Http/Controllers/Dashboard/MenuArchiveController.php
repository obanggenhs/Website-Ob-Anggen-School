<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuContent;
use App\Support\MenuArchive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Data Menu Baru: isi menu buatan admin yang tersimpan di database sekolah (tabel menu_contents),
 * tersusun menurut Menu Utama › Submenu › Halaman. Bisa dicari dan diunduh sebagai file CSV (Excel).
 * Master Admin melihat semua; Admin Publikasi / Pendaftaran hanya menu yang ditugaskan kepadanya.
 */
class MenuArchiveController extends Controller
{
    public function index(Request $request)
    {
        $menus = $this->menusFor($request);

        $rows = $this->query($request, $menus)
            ->with('menu:id,title,type,is_active')
            ->paginate(60)
            ->withQueryString();

        $all = MenuContent::whereIn('menu_id', $menus->pluck('id'));
        $stats = [
            'menus' => $menus->count(),
            'items' => (clone $all)->where('position', '>', 0)->count(),
            'files' => (clone $all)->whereNotNull('files')->pluck('files')->sum(fn ($f) => count((array) $f)),
            'synced' => (clone $all)->max('synced_at'),
        ];

        return view('dashboard.archive.index', [
            'rows'    => $rows,
            'menus'   => $menus,
            'stats'   => $stats,
            'current' => $menus->firstWhere('id', (int) $request->query('menu')),
        ]);
    }

    /** Unduh data sebagai CSV (bisa dibuka di Excel). */
    public function export(Request $request)
    {
        $query = $this->query($request, $this->menusFor($request));
        $filename = 'data-menu-sekolah-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");   // agar huruf terbaca benar di Excel
            fputcsv($out, [
                'Menu Utama', 'Submenu', 'Halaman', 'Jenis Halaman', 'Alamat Halaman', 'Urutan', 'Jenis Isi',
                'Judul Isi', 'Isi', 'Link', 'File', 'Tampil di Website', 'Dibuat Oleh', 'Diubah Oleh', 'Dibuat', 'Diubah',
            ], ';');

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->main_menu, $row->submenu, $row->page_title, $row->page_type, $row->page_url, $row->position,
                        $row->content_type, $row->content_title, $row->content_text, $row->link, $row->file_names,
                        $row->is_active ? 'Ya' : 'Tidak', $row->created_by, $row->updated_by,
                        $row->created_at?->format('d-m-Y H:i'), $row->updated_at?->format('d-m-Y H:i'),
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Bangun ulang seluruh data dari menu, blok, dan kartu (khusus Master Admin). */
    public function rebuild()
    {
        MenuArchive::rebuild();
        activity_log('memperbarui data menu', 'Tabel menu_contents dibangun ulang');

        return back()->with('success', 'Data menu buatan admin sudah diperbarui dari isi website terbaru.');
    }

    /* ------------------------------------------------------------------ */

    /** Menu buatan admin yang boleh dilihat akun ini. */
    protected function menusFor(Request $request)
    {
        $user = $request->user();

        return Menu::with('parent:id,title')
            ->where('is_custom', true)
            ->orderBy('parent_id')->orderBy('position')
            ->get()
            ->filter(fn (Menu $menu) => $user->canEditMenu($menu))
            ->values();
    }

    protected function query(Request $request, $menus): Builder
    {
        $ids = $menus->pluck('id');
        $menuId = (int) $request->query('menu');
        $search = trim((string) $request->query('cari'));

        return MenuContent::query()
            ->whereIn('menu_id', $ids)
            ->when($menuId && $ids->contains($menuId), fn ($q) => $q->where('menu_id', $menuId))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('main_menu', 'like', '%'.$search.'%')
                ->orWhere('submenu', 'like', '%'.$search.'%')
                ->orWhere('page_title', 'like', '%'.$search.'%')
                ->orWhere('content_title', 'like', '%'.$search.'%')
                ->orWhere('content_text', 'like', '%'.$search.'%')
                ->orWhere('file_names', 'like', '%'.$search.'%')))
            ->orderBy('main_menu')
            ->orderByRaw('submenu IS NOT NULL')
            ->orderBy('submenu')
            ->orderBy('page_title')
            ->orderBy('position');
    }
}