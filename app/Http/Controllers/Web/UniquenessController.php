<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Testimonial;
use App\Models\Uniqueness;
use App\Support\PageOptions;
use Illuminate\Http\Request;

class UniquenessController extends Controller
{
    public function index(Request $request)
    {
        $menu = Menu::forModule('keunikan');

        // Fitur bantuan pengunjung (pencarian, urutan, jumlah hasil) yang dipilih admin
        $opt = PageOptions::for($menu, 'keunikan');
        $in = PageOptions::input($request, $opt, 'keunikan');

        $items = Uniqueness::where('is_active', true)
            ->when($in['cari'] !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', '%'.$in['cari'].'%')
                ->orWhere('excerpt', 'like', '%'.$in['cari'].'%')
                ->orWhere('content', 'like', '%'.$in['cari'].'%')));

        match ($in['urut']) {
            'az'    => $items->orderBy('title'),
            'za'    => $items->orderByDesc('title'),
            default => $items->orderBy('position'),
        };

        $items = $items->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('position')->get();

        return view('web.uniqueness.index', compact('items', 'testimonials', 'menu', 'opt', 'in'));
    }

    public function show(string $slug)
    {
        $item = Uniqueness::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $others = Uniqueness::where('is_active', true)->where('id', '!=', $item->id)->orderBy('position')->get();
        $menu = Menu::forModule('keunikan');

        return view('web.uniqueness.show', compact('item', 'others', 'menu'));
    }
}
