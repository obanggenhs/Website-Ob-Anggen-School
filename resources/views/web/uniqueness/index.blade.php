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
        <style>
            .oa-testi-slider { position: relative; }

            /* 2 baris, kolom bertambah ke samping, bisa digeser */
            .oa-testi-track {
                display: grid;
                grid-template-rows: repeat(2, auto);
                grid-auto-flow: column;
                grid-auto-columns: 85%;
                gap: 1rem;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
                padding-bottom: .75rem;
                scrollbar-width: thin;
            }
            .oa-testi-track > .oa-testi-item { scroll-snap-align: start; min-width: 0; }
            .oa-testi-track .oa-testi { height: 100%; }

            @media (min-width: 576px) { .oa-testi-track { grid-auto-columns: calc((100% - 1rem) / 2); } }
            @media (min-width: 992px) { .oa-testi-track { grid-auto-columns: calc((100% - 2rem) / 3); } }

            /* Tombol panah */
            .oa-testi-nav {
                display: flex;
                justify-content: center;
                gap: .5rem;
                margin-top: 1rem;
            }
            .oa-testi-nav button {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: 1px solid var(--bs-border-color, #dee2e6);
                background: var(--bs-body-bg, #fff);
                color: inherit;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: opacity .2s;
            }
            .oa-testi-nav button:disabled { opacity: .35; cursor: default; }
            .oa-testi-nav.is-hidden { display: none; }
        </style>

        <section class="oa-section oa-section--surface">
            <div class="container oa-container">
                <div class="oa-sechead oa-sechead--center"><h2>Ulasan alumni & orang tua</h2></div>

                <div class="oa-testi-slider" data-reveal>
                    <div class="oa-testi-track" id="testiTrack">
                        @foreach ($testimonials as $t)
                            <div class="oa-testi-item">
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

                    <div class="oa-testi-nav" id="testiNav">
                        <button type="button" id="testiPrev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                        <button type="button" id="testiNext" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </section>

        <script>
            (function () {
                var track = document.getElementById('testiTrack');
                var nav   = document.getElementById('testiNav');
                var prev  = document.getElementById('testiPrev');
                var next  = document.getElementById('testiNext');
                if (!track) return;

                function step() {
                    var item = track.querySelector('.oa-testi-item');
                    return item ? item.getBoundingClientRect().width + 16 : track.clientWidth;
                }

                function update() {
                    var max = track.scrollWidth - track.clientWidth;
                    nav.classList.toggle('is-hidden', max <= 2);
                    prev.disabled = track.scrollLeft <= 2;
                    next.disabled = track.scrollLeft >= max - 2;
                }

                prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
                next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
                track.addEventListener('scroll', update, { passive: true });
                window.addEventListener('resize', update);
                update();
            })();
        </script>
    @endif
@endsection