<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Menu;
use App\Models\Staff;
use App\Support\PageOptions;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $category = array_key_exists((string) $request->kategori, Staff::CATEGORIES) ? $request->kategori : null;
        $branchId = $request->integer('kampus') ?: null;

        $menu = Menu::forModule('guru_staf');

        // Fitur bantuan pengunjung (pencarian, filter, urutan, dll.) yang dipilih admin
        $opt = PageOptions::for($menu, 'guru_staf');
        $in = PageOptions::input($request, $opt, 'guru_staf');

        $leaders = Staff::where('is_active', true)->where('is_featured', true)->with('branch')->orderBy('position')->get();

        $staff = Staff::where('is_active', true)->with('branch')
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($in['cari'] !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$in['cari'].'%')
                ->orWhere('position_title', 'like', '%'.$in['cari'].'%')
                ->orWhere('subject', 'like', '%'.$in['cari'].'%')));

        match ($in['urut']) {
            'az'    => $staff->orderBy('name'),
            'za'    => $staff->orderByDesc('name'),
            default => $staff->orderBy('position')->orderBy('name'),
        };

        $staff = $staff->get();
        $total = $staff->count();
        $staff = $staff->groupBy('category');

        $branches = Branch::active()->get();

        return view('web.staff.index', compact('leaders', 'staff', 'total', 'branches', 'category', 'branchId', 'menu', 'opt', 'in'));
    }

    public function show(string $slug)
    {
        $person = Staff::with('branch')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $colleagues = Staff::where('is_active', true)->where('id', '!=', $person->id)
            ->where('category', $person->category)->take(4)->get();

        $menu = Menu::forModule('guru_staf');

        return view('web.staff.show', compact('person', 'colleagues', 'menu'));
    }
}
