<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Graduate;
use App\Models\Menu;
use App\Support\PageOptions;
use Illuminate\Http\Request;

class GraduateController extends Controller
{
    public function index(Request $request)
    {
        $level = in_array($request->jenjang, ['sd', 'smp', 'sma'], true) ? $request->jenjang : null;
        $menu = Menu::forModule('kelulusan', $level);

        // Fitur bantuan pengunjung (pencarian, angkatan, urutan, dll.) yang dipilih admin
        $opt = PageOptions::for($menu, 'kelulusan');
        $in = PageOptions::input($request, $opt, 'kelulusan');

        $years = Graduate::where('is_active', true)->distinct()->orderByDesc('graduation_year')->pluck('graduation_year');

        // Pilihan angkatan: bawaan angkatan terbaru, "semua" untuk semua angkatan.
        // Bila pilihan angkatan dimatikan admin, semua angkatan ditampilkan sekaligus.
        $allYears = ! $opt['year'] || $request->query('tahun') === 'semua';
        $year = $allYears ? null : ($request->integer('tahun') ?: $years->first());

        $graduates = Graduate::where('is_active', true)
            ->when($year, fn ($q) => $q->where('graduation_year', $year))
            ->when($level, fn ($q) => $q->where('level', $level))
            ->when($in['cari'] !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$in['cari'].'%')
                ->orWhere('continue_to', 'like', '%'.$in['cari'].'%')
                ->orWhere('class_name', 'like', '%'.$in['cari'].'%')
                ->orWhere('graduation_year', 'like', '%'.$in['cari'].'%')));

        match ($in['urut']) {
            'za'      => $graduates->orderByDesc('name'),
            'terbaru' => $graduates->orderByDesc('graduation_year')->orderBy('name'),
            'terlama' => $graduates->orderBy('graduation_year')->orderBy('name'),
            default   => $graduates->orderBy('name'),
        };

        $graduates = $graduates->paginate($opt['per_page'])->withQueryString();

        return view('web.graduates.index', compact('graduates', 'years', 'year', 'allYears', 'level', 'menu', 'opt', 'in'));
    }

    public function show(string $slug)
    {
        $graduate = Graduate::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $friends = Graduate::where('is_active', true)->where('id', '!=', $graduate->id)
            ->where('graduation_year', $graduate->graduation_year)->inRandomOrder()->take(4)->get();

        $menu = Menu::forModule('kelulusan');

        return view('web.graduates.show', compact('graduate', 'friends', 'menu'));
    }
}
