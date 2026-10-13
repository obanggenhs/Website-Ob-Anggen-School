@extends('layouts.web-full')

@section('title', $menu?->title ?? 'Galeri')
@section('page_title', $menu?->title ?? 'Galeri')

@section('page')
    {{-- Bilah bantuan pengunjung: diatur admin di Studio › Galeri › Fitur bantuan pengunjung --}}
    <x-list-toolbar :opt="$opt" type="galeri" :in="$in" :years="$years" :result="$albums" noun="album"
        :chips="['param' => 'kategori', 'current' => $category?->slug ?? '', 'all' => 'Semua album', 'items' => $categories->pluck('name', 'slug')->all()]"
        placeholder="Cari album / kegiatan..." />

    @if ($albums->isEmpty())
        <div class="oa-empty"><i class="bi bi-images"></i>{{ $in['cari'] !== '' || $in['tahun'] !== '' ? 'Tidak ada album yang cocok dengan pencarian.' : 'Belum ada album di galeri.' }}</div>
    @else
        {{-- 6 album per baris di layar lebar --}}
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            @foreach ($albums as $album)
                <div class="col">
                    <a href="{{ route('gallery.show', $album->slug) }}" class="oa-card oa-card--mini">
                        <div class="oa-card__img oa-card__img--square">
                            <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" loading="lazy">
                            @if ($album->video_url)<span class="oa-card__badge oa-badge oa-badge--accent"><i class="bi bi-play-fill"></i> Video</span>@endif
                            <span class="oa-album__count"><i class="bi bi-images"></i> {{ $album->photos_count }}</span>
                        </div>
                        <div class="oa-card__body">
                            <h3 class="oa-card__title">{{ $album->title }}</h3>
                            <span class="oa-card__meta">
                                @if ($album->event_date)<span><i class="bi bi-calendar3"></i>{{ tgl($album->event_date, 'd M Y') }}</span>@endif
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-4 {{ $opt['count'] ? 'oa-pager--counted' : '' }}">{{ $albums->links() }}</div>
    @endif
@endsection
