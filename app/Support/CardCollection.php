<?php

namespace App\Support;

use App\Models\Block;
use Illuminate\Http\Request;

/**
 * Menyiapkan isi blok "Koleksi Kartu" untuk website:
 * pencarian, filter kategori, filter tahun, urutan, dan halaman (pagination)
 * sesuai Fitur bantuan pengunjung yang dipilih admin untuk halaman tersebut.
 */
class CardCollection
{
    public static function listing(Block $block, Request $request): array
    {
        $menu = $block->menu;
        $options = $menu->pageOptions();

        // Bila satu halaman punya lebih dari satu Koleksi Kartu, filter hanya berlaku untuk blok yang dipilih
        $multi = Block::where('menu_id', $menu->id)->where('type', 'koleksi')->where('is_active', true)->count() > 1;
        $targeted = ! $multi || (int) $request->query('blok') === $block->id;

        $input = $targeted
            ? PageOptions::input($request, $options, 'halaman')
            : ['cari' => '', 'tahun' => '', 'urut' => 'urutan', 'tampil' => 'kartu'];

        $base = $block->cards()->where('is_active', true);

        $categories = (clone $base)->reorder()->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category');
        $years = (clone $base)->reorder()->whereNotNull('year')->where('year', '!=', '')
            ->distinct()->orderByDesc('year')->pluck('year');

        $category = $targeted && ! empty($options['filter']) && $categories->contains((string) $request->query('kategori'))
            ? (string) $request->query('kategori')
            : '';

        $query = clone $base;

        if ($input['cari'] !== '') {
            $term = '%'.$input['cari'].'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)
                ->orWhere('subtitle', 'like', $term)
                ->orWhere('category', 'like', $term)
                ->orWhere('year', 'like', $term)
                ->orWhere('excerpt', 'like', $term)
                ->orWhere('meta', 'like', $term)
                ->orWhere('body', 'like', $term));
        }
        if ($category !== '') {
            $query->where('category', $category);
        }
        if ($input['tahun'] !== '') {
            $query->where('year', $input['tahun']);
        }

        match ($input['urut']) {
            'terbaru' => $query->reorder()->orderByDesc('year')->orderByDesc('created_at'),
            'terlama' => $query->reorder()->orderBy('year')->orderBy('created_at'),
            'az'      => $query->reorder()->orderBy('title'),
            'za'      => $query->reorder()->orderByDesc('title'),
            default   => null,   // urutan yang diatur admin
        };

        $pageName = $multi ? 'hal'.$block->id : 'hal';
        $items = $query->paginate($options['per_page'], ['*'], $pageName)->withQueryString();
        $items->getCollection()->each(fn ($item) => $item->setRelation('block', $block));

        return [
            'items'      => $items,
            'options'    => $options,
            'input'      => $input,
            'category'   => $category,
            'categories' => $categories,
            'years'      => $years,
            'pageName'   => $pageName,
            'scope'      => $multi ? ['blok' => $block->id] : [],
            'filtered'   => $input['cari'] !== '' || $category !== '' || $input['tahun'] !== '',
        ];
    }
}
