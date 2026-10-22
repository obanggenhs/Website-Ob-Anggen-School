@extends('layouts.web')

@section('title', 'Beranda')

@php
    $quickColors = ['#7A2B25', '#2F5D50', '#B86B12', '#3b6fd8', '#6a5acd', '#d64545', '#0f766e', '#8a4fb8', '#2f9e6a', '#475569', '#c2410c', '#1F4E79'];
    $blocksTop = setting('home_blocks_position') === 'top';
    $showVideo = setting('home_show_video') && setting('video_url');

        // Tombol hero "Daftar sekarang" ikut status menu/jalur Pendaftaran (dihitung di HomeController)
    $showHeroButton = setting('hero_button') && ($registrationOpen ?? true);

    // Latar hero diatur di Studio > Beranda > "Latar belakang hero Beranda" (foto / video / YouTube / slide)
    $heroMedia = $home?->headerMedia() ?? ['type' => 'image', 'src' => media_url(setting('hero_image')), 'poster' => media_url(setting('hero_image'))];

    // Lapisan warna di atas latar hero (tipis / sedang / pekat), diatur di Studio › Beranda
    $heroOverlay = $home?->headerOverlay() ?? \App\Models\Menu::defaultOverlay();
@endphp

@section('content')
    {{-- ===== HERO (ringkas, tidak memenuhi satu layar) ===== --}}
    <section class="oa-hero oa-overlay--{{ $heroOverlay }} {{ $heroMedia['type'] !== 'image' ? 'has-media' : '' }}" style="--oa-hero-img: url('{{ $heroMedia['poster'] ?? media_url(setting('hero_image')) }}')">
        @include('web.partials.header-media', ['media' => $heroMedia])

        <div class="container oa-container">
            <span class="oa-hero__eyebrow"><i class="bi bi-geo-alt-fill"></i> {{ setting('site_tagline') }}</span>
            <h1 class="oa-hero__title">{{ setting('hero_title') }}</h1>
            <p class="oa-hero__text">{{ setting('hero_subtitle') }}</p>
            <div class="d-flex flex-wrap gap-2">
                @if ($showHeroButton)
                    <a href="{{ url(setting('hero_button_url', '/')) }}" class="btn btn-accent btn-lg">{{ setting('hero_button') }}</a>
                @endif
                @if ($showVideo)
                    <a href="#video-profil" class="btn btn-ghost-light btn-lg"><i class="bi bi-play-circle me-1"></i> Tonton video</a>
                @endif
            </div>
            <div class="oa-hero__stats">
                <div><strong data-count="{{ (int) setting('stat_campus') }}">{{ setting('stat_campus') }}</strong><span>Kampus</span></div>
                <div><strong data-count="{{ (int) setting('stat_students') }}">{{ setting('stat_students') }}</strong><span>Siswa</span></div>
                <div><strong data-count="{{ (int) setting('stat_teachers') }}">{{ setting('stat_teachers') }}</strong><span>Guru &amp; staf</span></div>
            </div>
        </div>
        @include('web.partials.ridge')
    </section>

    {{-- ===== LINK CEPAT (4 menu pertama dari struktur website) ===== --}}
    @if ($shortcuts->isNotEmpty())
        <div class="container oa-container oa-quicklinks">
            <div class="row g-2 g-md-3">
                @foreach ($shortcuts->take(4) as $item)
                    <div class="col-6 col-lg-3">
                        <a href="{{ $item->link }}" class="oa-quicklink" target="{{ $item->target }}">
                            <span class="oa-quicklink__icon"><i class="bi {{ $item->type_icon }}"></i></span>
                            <span class="min-w-0">
                                <strong>{{ $item->title }}</strong>
                                <small class="d-none d-sm-block text-truncate">{{ $item->subtitle ?: $item->type_label }}</small>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ===== BLOK TAMBAHAN (posisi atas) ===== --}}
    @if ($blocksTop && $blocks->isNotEmpty())
        <section class="oa-section">
            <div class="container oa-container">
                <div class="row oa-blocks">
                    @include('web.partials.blocks', ['list' => $blocks])
                </div>
            </div>
        </section>
    @endif

    {{-- ===== SAMBUTAN ===== --}}
    <section class="oa-section">
        <div class="container oa-container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="oa-sechead"><h2>{{ setting('welcome_title') }}</h2></div>
                    <div class="prose mb-3">{!! setting('welcome_text') !!}</div>
                    @if (setting('welcome_link'))
                        <a href="{{ url(setting('welcome_link')) }}" class="btn btn-primary">Baca selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="oa-welcome__img" data-reveal="right">
                        <img src="{{ media_url(setting('welcome_image')) }}" alt="{{ setting('welcome_title') }}" loading="lazy">
                        <div class="oa-welcome__badge"><strong>{{ setting('stat_campus') }} kampus</strong><small>di Papua Pegunungan</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== KAMPUS ===== --}}
    @if ($branches->isNotEmpty())
        <section class="oa-section oa-section--surface">
            <div class="container oa-container">
                <div class="oa-sechead">
                    <div><h2>Kampus Ob Anggen</h2><p>Melayani anak-anak di kampung-kampung pegunungan Papua.</p></div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                    @foreach ($branches as $branch)
                        <div class="col">
                            <a href="{{ \App\Models\Menu::moduleEnabled('fasilitas') ? route('facilities.index', ['jenis' => 'gedung', 'kampus' => $branch->id]) : '#' }}" class="oa-card">
                                <div class="oa-card__img"><img src="{{ media_url($branch->image) }}" alt="{{ $branch->name }}" loading="lazy"></div>
                                <div class="oa-card__body">
                                    <h3 class="oa-card__title">{{ $branch->name }}</h3>
                                    <span class="oa-card__meta"><span><i class="bi bi-geo-alt"></i>{{ $branch->regency }}</span></span>
                                    <p class="oa-card__text">{{ $branch->description }}</p>
                                    <span class="oa-card__foot oa-link-more">Lihat fasilitas <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== AGENDA & AKSES CEPAT ===== --}}
    @if ($agenda->isNotEmpty() || (setting('home_show_quick') && $shortcuts->isNotEmpty()))
        <section class="oa-section">
            <div class="container oa-container">
                <div class="row g-4">
                    @if ($agenda->isNotEmpty())
                        <div class="{{ setting('home_show_quick') ? 'col-lg-7' : 'col-12' }}">
                            <div class="oa-panel h-100">
                                <div class="oa-sechead"><h2>Agenda mendatang</h2>
                                    <a href="{{ route('calendar.index') }}" class="oa-link-more">Kalender lengkap <i class="bi bi-arrow-right"></i></a>
                                </div>
                                <ul class="oa-agenda">
                                    @foreach ($agenda->take(5) as $event)
                                        <li style="--cat: {{ $event->category_color }}">
                                            <span class="oa-date"><strong>{{ $event->start_date->format('d') }}</strong><small>{{ tgl($event->start_date, 'M') }}</small></span>
                                            <span class="min-w-0 flex-fill">
                                                <span class="oa-agenda__title d-block text-truncate">{{ $event->title }}</span>
                                                <span class="oa-agenda__meta d-block text-truncate">{{ $event->level_label }} · {{ $event->date_label }}</span>
                                            </span>
                                            <span class="oa-when {{ $event->isOngoing() ? 'is-now' : '' }}">{{ $event->relative_label }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if (setting('home_show_quick') && $shortcuts->isNotEmpty())
                        <div class="{{ $agenda->isNotEmpty() ? 'col-lg-5' : 'col-12' }}">
                            <div class="oa-sechead"><h2>Akses cepat</h2></div>
                            <div class="oa-tiles">
                                @foreach ($shortcuts->take(9) as $i => $item)
                                    <a href="{{ $item->link }}" class="oa-tile" style="--tile: {{ $quickColors[$i % count($quickColors)] }}"><i class="bi {{ $item->type_icon }}"></i><span>{{ $item->title }}</span></a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ===== BERITA ===== --}}
    @if ($news->isNotEmpty())
        <section class="oa-section pt-0">
            <div class="container oa-container">
                <div class="oa-sechead">
                    <div><h2>Berita terbaru</h2><p>Kabar dan kegiatan terbaru dari ketiga kampus.</p></div>
                    <a href="{{ route('news.index') }}" class="oa-link-more">Semua berita <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                    @foreach ($news as $post)
                        <div class="col">@include('web.news._card', ['post' => $post])</div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== VIDEO PROFIL (diputar otomatis saat kursor diarahkan) ===== --}}
    @if ($showVideo)
        <section class="oa-section pt-0" id="video-profil">
            <div class="container oa-container">
                <div class="oa-videofeature">
                    <div>
                        <span class="oa-hero__eyebrow mb-1"><i class="bi bi-camera-reels"></i> Video profil</span>
                        <h2>{{ setting('video_title') }}</h2>
                        <p>{{ setting('video_text') }}</p>
                        <small class="d-block" style="color: rgba(255,255,255,.7)"><i class="bi bi-cursor"></i> Arahkan kursor ke video untuk memutar tanpa suara. Klik ikon speaker di video untuk menyalakan suara.</small>
                    </div>
                    @include('web.partials.video', ['url' => setting('video_url'), 'title' => setting('video_title')])
                </div>
            </div>
        </section>
    @endif

    {{-- ===== TESTIMONI ===== --}}
    @if ($testimonials->isNotEmpty())
        <section class="oa-section pt-0">
            <div class="container oa-container">
                <div class="oa-sechead oa-sechead--center"><h2>Apa kata mereka</h2><p>Cerita alumni dan orang tua tentang Ob Anggen.</p></div>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    @foreach ($testimonials->take(3) as $t)
                        <div class="col">
                            <div class="oa-testi">
                                <p class="oa-testi__quote">{{ \Illuminate\Support\Str::limit($t->content, 200) }}</p>
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

    {{-- ===== BLOK TAMBAHAN (posisi bawah) ===== --}}
    @if (! $blocksTop && $blocks->isNotEmpty())
        <section class="oa-section pt-0">
            <div class="container oa-container">
                <div class="row oa-blocks">
                    @include('web.partials.blocks', ['list' => $blocks])
                </div>
            </div>
        </section>
    @endif
@endsection
