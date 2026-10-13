@extends('layouts.web-full')

@section('title', $menu?->title ?? 'Kelulusan')
@section('page_title', ($menu?->title ?? 'Kelulusan').($year ? ' '.$year : ''))

@section('page')
    @php
        // Tombol angkatan (bawaan sama seperti sebelumnya) + "Semua angkatan"
        $yearChips = $years->mapWithKeys(fn ($y) => [$y => 'Angkatan '.$y])->all();
        if ($years->count() > 1) {
            $yearChips['semua'] = 'Semua angkatan';
        }
    @endphp

    {{-- Bilah bantuan pengunjung: diatur admin di Studio › Kelulusan › Fitur bantuan pengunjung --}}
    <x-list-toolbar :opt="$opt" type="kelulusan" :in="array_merge($in, ['tahun' => ''])" :result="$graduates" noun="lulusan" placeholder="Cari nama, kampus..."
        :chips="$opt['year'] ? ['param' => 'tahun', 'current' => $allYears ? 'semua' : (string) $year, 'items' => $yearChips, 'always' => true] : null">
        @if ($opt['filter'])
            <select name="jenjang" class="form-select form-select-sm rounded-pill" data-autosubmit aria-label="Jenjang">
                <option value="">Semua jenjang</option>
                @foreach (\App\Models\Graduate::LEVELS as $key => $label)
                    <option value="{{ $key }}" @selected($level === $key)>{{ $label }}</option>
                @endforeach
            </select>
        @elseif ($level)
            <input type="hidden" name="jenjang" value="{{ $level }}">
        @endif
    </x-list-toolbar>

    @if ($graduates->isEmpty())
        <div class="oa-empty">
            <i class="bi bi-mortarboard"></i>
            @if ($in['cari'] !== '')
                Tidak ada lulusan yang cocok dengan "{{ $in['cari'] }}"{{ $year ? ' di angkatan '.$year : '' }}.
                @if ($year && $years->count() > 1)
                    <div class="mt-2"><a href="{{ request()->fullUrlWithQuery(['tahun' => 'semua', 'page' => null]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Cari di semua angkatan</a></div>
                @endif
            @else
                Belum ada data lulusan.
            @endif
        </div>
    @else
        {{-- 6 lulusan per baris di layar lebar --}}
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 oa-results {{ $in['tampil'] === 'daftar' ? 'is-list' : '' }}">
            @foreach ($graduates as $g)
                <div class="col">
                    <a href="{{ route('graduates.show', $g->slug) }}" class="oa-card oa-card--mini oa-person">
                        <div class="oa-card__img oa-card__img--portrait"><img src="{{ $g->photo_url }}" alt="{{ $g->name }}" loading="lazy"></div>
                        <div class="oa-card__body">
                            <span class="oa-badge">{{ $g->level_label }} {{ $g->class_name }}</span>
                            <h3 class="oa-card__title">{{ $g->name }}</h3>
                            @if ($g->continue_to)<span class="oa-card__meta justify-content-center"><span class="text-truncate" style="max-width: 100%"><i class="bi bi-signpost-2"></i>{{ $g->continue_to }}</span></span>@endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-4 {{ $opt['count'] ? 'oa-pager--counted' : '' }}">{{ $graduates->links() }}</div>
    @endif
@endsection
