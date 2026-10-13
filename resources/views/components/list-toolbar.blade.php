{{--
    Bilah bantuan pengunjung di atas daftar (dipakai halaman Koleksi Kartu dan halaman modul).
    Isi bilah mengikuti "Fitur bantuan pengunjung" yang dipilih admin di Studio.

    <x-list-toolbar :opt="$opt" type="berita" :in="$in" :chips="[...]" :years="$years" :result="$news" noun="berita" />

    Props:
      opt         : hasil PageOptions::for()
      type        : jenis halaman (menentukan pilihan urutan)
      in          : hasil PageOptions::input() → cari, tahun, urut, tampil
      chips       : satu kelompok tombol pilihan
                    ['param' => 'kategori', 'current' => '..', 'items' => [nilai => label], 'all' => 'Semua',
                     'always' => false, 'icon' => 'bi-geo-alt', 'reset' => ['jenjang']]
                    atau beberapa kelompok sekaligus: [[...], [...]]
                    'always' => true : tetap tampil walau fitur filter dimatikan (mis. pilihan kampus)
                    'reset'  => [..] : pilihan lain yang dikosongkan saat tombol ini diklik
      years       : daftar tahun untuk filter tahun
      result      : paginator / koleksi hasil (untuk jumlah hasil)
      noun        : kata benda untuk jumlah hasil, mis. "alumni"
      hidden      : nilai lain yang harus ikut terkirim (mis. kanal, kampus)
      scope       : nilai yang ikut di semua tautan & formulir (mis. blok)
      pageName    : nama parameter halaman (pagination)
      anchor      : id elemen tujuan setelah formulir dikirim
      placeholder : teks contoh di kotak pencarian
      chipsAlways : sama dengan 'always' untuk semua kelompok chips
    Slot: pilihan tambahan (mis. dropdown jenjang / kampus) yang ikut di dalam formulir.
--}}
@props([
    'opt' => [], 'type' => 'halaman', 'in' => [], 'chips' => null, 'years' => [], 'result' => null, 'noun' => 'data',
    'hidden' => [], 'scope' => [], 'pageName' => 'page', 'anchor' => null, 'placeholder' => 'Cari...', 'chipsAlways' => false,
])

@php
    $in = array_merge(['cari' => '', 'tahun' => '', 'urut' => '', 'tampil' => 'kartu'], $in);
    $years = collect($years);
    $sorts = \App\Support\PageOptions::sorts($type);
    $hash = $anchor ? '#'.$anchor : '';
    $link = fn (array $params) => request()->fullUrlWithQuery(array_merge($scope, $params, [$pageName => null])).$hash;

    // Satukan bentuk chips menjadi daftar kelompok
    $groups = [];
    if (is_array($chips) && $chips !== []) {
        $groups = array_key_exists('param', $chips) ? [$chips] : array_values(array_filter($chips));
    }
    $groups = array_map(fn ($g) => array_merge(['current' => '', 'items' => [], 'all' => null, 'always' => $chipsAlways, 'icon' => null, 'reset' => []], $g), $groups);
    $groups = array_values(array_filter($groups, fn ($g) => ($g['always'] || ! empty($opt['filter'])) && count($g['items']) > 0));

    $showYear = ! empty($opt['year']) && $years->isNotEmpty();
    $showSort = ! empty($opt['sort']);
    $showSearch = ! empty($opt['search']);
    $showView = ! empty($opt['view']);
    $showCount = ! empty($opt['count']) && $result !== null;
    $extra = trim((string) $slot) !== '';
    $hasForm = $showYear || $showSort || $showSearch || $extra;

    // Pilihan yang bisa dihapus lewat "Hapus pencarian & filter" (pilihan kampus dsb. tidak ikut dihapus)
    $resetParams = ['cari' => null, 'tahun' => null, 'urut' => null];
    $filtered = $in['cari'] !== '' || $in['tahun'] !== '';
    foreach ($groups as $g) {
        if (! $g['always']) {
            $resetParams[$g['param']] = null;
            $filtered = $filtered || (string) $g['current'] !== '';
        }
    }

    // Nilai yang ikut terkirim saat formulir dipakai (tanpa menggandakan isian yang sudah ada)
    $keep = array_merge($hidden, $scope);
    foreach ($groups as $g) {
        if ((string) $g['current'] !== '') {
            $keep[$g['param']] = $g['current'];
        }
    }
    if ($showView && $in['tampil'] === 'daftar') {
        $keep['tampil'] = 'daftar';
    }
    foreach (['cari' => $showSearch, 'tahun' => $showYear, 'urut' => $showSort] as $name => $shown) {
        if ($shown) {
            unset($keep[$name]);
        }
    }
@endphp

@if ($groups || $hasForm || $showView || $showCount)
    <div class="oa-filterbar oa-listbar" @if ($anchor) id="{{ $anchor }}" @endif>
        @foreach ($groups as $g)
            <div class="oa-chips oa-listbar__chips">
                @if ($g['all'])
                    <a href="{{ $link(array_merge(array_fill_keys($g['reset'], null), [$g['param'] => null])) }}" class="oa-chip {{ (string) $g['current'] === '' ? 'active' : '' }}">{{ $g['all'] }}</a>
                @endif
                @foreach ($g['items'] as $value => $label)
                    <a href="{{ $link(array_merge(array_fill_keys($g['reset'], null), [$g['param'] => $value])) }}" class="oa-chip {{ (string) $g['current'] === (string) $value ? 'active' : '' }}">@if ($g['icon'])<i class="bi {{ $g['icon'] }}"></i> @endif{{ $label }}</a>
                @endforeach
            </div>
        @endforeach

        @if ($hasForm || $showView)
            <div class="oa-listbar__row">
                @if ($hasForm)
                    <form method="GET" action="{{ url()->current() }}{{ $hash }}" class="oa-listbar__form" role="search">
                        @foreach ($keep as $name => $value)
                            @if ($value !== null && $value !== '')
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        {{ $slot }}

                        @if ($showYear)
                            <select name="tahun" class="form-select form-select-sm rounded-pill" data-autosubmit aria-label="Filter tahun">
                                <option value="">Semua tahun</option>
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" @selected((string) $in['tahun'] === (string) $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if ($showSort)
                            <select name="urut" class="form-select form-select-sm rounded-pill" data-autosubmit aria-label="Urutkan">
                                @foreach ($sorts as $key => $label)
                                    <option value="{{ $key }}" @selected($in['urut'] === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if ($showSearch)
                            <div class="oa-search oa-search--btn">
                                <i class="bi bi-search"></i>
                                <input type="search" name="cari" value="{{ $in['cari'] }}" class="form-control" placeholder="{{ $placeholder }}" aria-label="Cari">
                                <button type="submit" class="oa-search__go" aria-label="Cari">Cari</button>
                            </div>
                        @endif
                    </form>
                @endif

                @if ($showView)
                    <div class="oa-viewtoggle" role="group" aria-label="Tampilan">
                        <a href="{{ $link(['tampil' => null]) }}" class="{{ $in['tampil'] !== 'daftar' ? 'active' : '' }}" title="Tampilan kartu" aria-label="Tampilan kartu"><i class="bi bi-grid-3x3-gap"></i></a>
                        <a href="{{ $link(['tampil' => 'daftar']) }}" class="{{ $in['tampil'] === 'daftar' ? 'active' : '' }}" title="Tampilan daftar" aria-label="Tampilan daftar"><i class="bi bi-list-ul"></i></a>
                    </div>
                @endif
            </div>
        @endif

        @if ($showCount || $filtered)
            <div class="oa-listbar__info">
                @if ($showCount)
                    <span><i class="bi bi-info-circle"></i> {{ \App\Support\PageOptions::countText($result, $noun) }}</span>
                @endif
                @if ($filtered)
                    <a href="{{ $link($resetParams) }}" class="oa-listbar__reset"><i class="bi bi-x-circle"></i> Hapus pencarian & filter</a>
                @endif
            </div>
        @endif
    </div>
@endif
