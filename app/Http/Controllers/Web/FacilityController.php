<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Facility;
use App\Models\Menu;
use App\Support\PageOptions;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request, string $jenis)
    {
        $branches = Branch::active()->get();
        $branch = $branches->firstWhere('id', $request->integer('kampus')) ?? $branches->first();
        $level = array_key_exists((string) $request->jenjang, Facility::LEVELS) ? $request->jenjang : null;

        $menu = Menu::forModule('fasilitas', $jenis);

        // Fitur bantuan pengunjung (pencarian, filter, urutan, dll.) yang dipilih admin
        $opt = PageOptions::for($menu, 'fasilitas');
        $in = PageOptions::input($request, $opt, 'fasilitas');

        $facilities = Facility::where('is_active', true)->where('type', $jenis)
            ->when($branch, fn ($q) => $q->where('branch_id', $branch->id))
            ->when($level, fn ($q) => $q->where('level', $level))
            ->when($in['cari'] !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$in['cari'].'%')
                ->orWhere('label', 'like', '%'.$in['cari'].'%')
                ->orWhere('description', 'like', '%'.$in['cari'].'%')
                ->orWhere('used_for', 'like', '%'.$in['cari'].'%')));

        match ($in['urut']) {
            'az'    => $facilities->orderBy('name'),
            'za'    => $facilities->orderByDesc('name'),
            default => $facilities->orderBy('position'),
        };

        $facilities = $facilities->get();

        $levels = Facility::where('is_active', true)->where('type', $jenis)
            ->when($branch, fn ($q) => $q->where('branch_id', $branch->id))
            ->distinct()->pluck('level');

        return view('web.facilities.index', compact('facilities', 'branches', 'branch', 'level', 'levels', 'jenis', 'menu', 'opt', 'in'));
    }

    public function show(string $slug)
    {
        $facility = Facility::with('branch')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $others = Facility::where('is_active', true)->where('id', '!=', $facility->id)
            ->where('type', $facility->type)->where('branch_id', $facility->branch_id)
            ->orderBy('position')->take(4)->get();

        $menu = Menu::forModule('fasilitas', $facility->type);

        return view('web.facilities.show', compact('facility', 'others', 'menu'));
    }
}
