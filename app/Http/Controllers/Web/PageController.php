<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlockItem;
use App\Models\Menu;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $menu = Menu::with('parent')->where('slug', $slug)->where('type', 'halaman')->firstOrFail();

        abort_unless($menu->isVisible() || auth()->check(), 404);

        $blocks = $menu->blocks()->where('is_active', true)->get();
        $blocks->each->setRelation('menu', $menu);

        return view('web.page', compact('menu', 'blocks'));
    }

    /** Halaman detail satu kartu dari blok "Koleksi Kartu". */
    public function item(string $slug, string $item)
    {
        $menu = Menu::with('parent')->where('slug', $slug)->whereIn('type', ['halaman', 'beranda'])->firstOrFail();

        abort_unless($menu->isVisible() || auth()->check(), 404);

        $item = BlockItem::with('block')->where('slug', $item)
            ->whereHas('block', fn ($q) => $q->where('menu_id', $menu->id)->where('type', 'koleksi')->where('is_active', true))
            ->firstOrFail();

        abort_unless($item->is_active || auth()->check(), 404);
        $item->block->setRelation('menu', $menu);

        // Kartu lain di blok yang sama, utamakan kategori yang sama
        $related = BlockItem::where('block_id', $item->block_id)->where('is_active', true)->where('id', '!=', $item->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [(string) $item->category])
            ->orderBy('position')->take(4)->get();

        return view('web.page-item', compact('menu', 'item', 'related'));
    }
}
