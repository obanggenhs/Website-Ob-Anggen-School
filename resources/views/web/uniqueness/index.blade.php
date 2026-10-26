@extends('layouts.web-page')

@section('title', $menu?->title ?? 'Keunikan')

@section('page')
    {{-- Bilah bantuan pengunjung: diatur admin di Studio › Keunikan › Fitur bantuan pengunjung --}}
    <x-list-toolbar :opt="$opt" type="keunikan" :in="$in" :result="$items" noun="keunikan" placeholder="Cari keunikan..." />

    @if ($items->isEmpty())
        <div class="oa-empty"><i class="bi bi-stars"></i>{{ $in['cari'] !== '' ? 'Tidak ada keunikan yang cocok dengan pencarian.' : 'Belum ada data keunikan.' }}</div>
    @else
        <div class="row g-3">
            @foreach ($items as $item)
                <div class="col-12 col-sm-6 col-lg-3" data-reveal>
                    <a href="{{ route('uniqueness.show', $item->slug) }}" class="oa-card">
                        <div class="oa-card__img">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy">
                            @if ($item->video_url)<span class="oa-card__play"><i class="bi bi-play-circle-fill"></i></span>@endif
                        </div>
                        <div class="oa-card__body">
                            <h3 class="oa-card__title"><i class="bi {{ $item->icon ?: 'bi-stars' }} text-primary me-1"></i> {{ $item->title }}</h3>
                            <p class="oa-card__text">{{ $item->excerpt }}</p>
                            <span class="oa-card__foot oa-link-more">Selengkapnya <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('after')
    @if ($testimonials->isNotEmpty())
        <section class="oa-section oa-section--surface">
            <div class="container oa-container">
                <div class="oa-sechead oa-sechead--center"><h2>Ulasan alumni & orang tua</h2></div>
                <div class="row g-3">
                    @foreach ($testimonials as $t)
                        <div class="col-md-4" data-reveal>
                            <div class="oa-testi">
                                <p class="oa-testi__quote">{{ $t->content }}</p>
                                <div class="oa-testi__person">
                                    <img src="{{ $t->photo_url }}" alt="{{ $t->name }}" loading="lazy">
                                    <span><strong>{{ $t->name }}</strong><small>{{ $t->role }}</small></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection