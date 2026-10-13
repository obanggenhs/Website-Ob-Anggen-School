{{--
    Blok "Koleksi Kartu" di website: bilah bantuan pengunjung + kartu-kartu + halaman.
    Data disiapkan App\Support\CardCollection::listing().
--}}
@php
    $list = \App\Support\CardCollection::listing($b, request());
    $cards = $list['items'];
    $columns = (int) $b->cardSetting('columns');
    $cols = [2 => 'row-cols-1 row-cols-sm-2', 3 => 'row-cols-1 row-cols-sm-2 row-cols-lg-3', 4 => 'row-cols-2 row-cols-md-3 row-cols-lg-4', 6 => 'row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6'][$columns] ?? 'row-cols-2 row-cols-md-3 row-cols-lg-4';
    $menuSlug = $b->menu->slug;
    $isList = $list['input']['tampil'] === 'daftar';
@endphp

@if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
@if (strip_tags((string) $b->body) !== '')<div class="prose mb-3">{!! $b->body !!}</div>@endif

<x-list-toolbar
    :opt="$list['options']" type="halaman" :in="$list['input']"
    :chips="['param' => 'kategori', 'current' => $list['category'], 'items' => $list['categories']->mapWithKeys(fn ($c) => [$c => $c])->all(), 'all' => 'Semua']"
    :years="$list['years']" :result="$cards" noun="data" :scope="$list['scope']" :pageName="$list['pageName']"
    :anchor="'koleksi-'.$b->id" placeholder="Cari nama, angkatan, tahun..." />

@if ($cards->isEmpty())
    <div class="oa-empty">
        <i class="bi {{ $list['filtered'] ? 'bi-search' : 'bi-grid-3x3-gap' }}"></i>
        {{ $list['filtered'] ? 'Tidak ada data yang cocok dengan pencarian atau filter.' : 'Belum ada isi.' }}
    </div>
@else
    <div class="row g-3 oa-results {{ $cols }} {{ $isList ? 'is-list' : '' }}">
        @foreach ($cards as $card)
            <div class="col">
                @include('web.partials.collection-card', [
                    'card' => $card, 'url' => route('page.item', [$menuSlug, $card->slug]),
                    'shape' => $b->cardSetting('shape'), 'excerpt' => (bool) $b->cardSetting('excerpt'), 'button' => $b->cardSetting('button'),
                ])
            </div>
        @endforeach
    </div>
    @if ($cards->hasPages())
        <div class="mt-3 {{ $list['options']['count'] ? 'oa-pager--counted' : '' }}">{{ $cards->fragment('koleksi-'.$b->id)->links() }}</div>
    @endif
@endif
