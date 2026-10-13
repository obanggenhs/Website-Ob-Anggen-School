@extends('layouts.web-page')

@section('title', $menu?->title ?? 'Pendaftaran')

@section('page')
    <div class="oa-chips mb-4">
        <a href="{{ route('registration.index') }}" class="oa-chip {{ ! $audience ? 'active' : '' }}">Semua jalur</a>
        @foreach (\App\Models\RegistrationType::AUDIENCES as $key => $label)
            <a href="{{ route('registration.index', ['untuk' => $key]) }}" class="oa-chip {{ $audience === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
        @if (\App\Models\Menu::moduleEnabled('cek_status'))
            <a href="{{ route('registration.status') }}" class="oa-chip ms-auto"><i class="bi bi-search"></i> Cek status</a>
        @endif
    </div>

    @if ($types->isEmpty())
        <div class="oa-empty"><i class="bi bi-clipboard-x"></i>Belum ada jalur pendaftaran.</div>
    @else
        <div class="row g-3">
            @foreach ($types as $type)
                @php $open = $type->isAccepting(); @endphp
                <div class="col-md-6" data-reveal>
                    <a href="{{ route('registration.show', $type->slug) }}" class="oa-card">
                        <div class="oa-card__img">
                            <img src="{{ $type->image_url }}" alt="{{ $type->name }}" loading="lazy">
                            <span class="oa-card__badge oa-badge {{ $open ? 'oa-badge--green' : 'oa-badge--grey' }}">
                                <i class="bi {{ $open ? 'bi-door-open' : 'bi-door-closed' }}"></i> {{ $type->status_label }}
                            </span>
                        </div>
                        <div class="oa-card__body">
                            <span class="oa-card__meta">
                                <span><i class="bi bi-person-badge"></i>{{ \App\Models\RegistrationType::AUDIENCES[$type->audience] }}{{ $type->level_label ? ' · '.$type->level_label : '' }}</span>
                                @if ($type->period_label)<span><i class="bi bi-calendar-range"></i>{{ $type->period_label }}</span>@endif
                            </span>
                            <h3 class="oa-card__title">{{ $type->name }}</h3>
                            <p class="oa-card__text">{{ $type->summary }}</p>
                            <span class="oa-card__foot oa-link-more">{{ $open ? 'Lihat persyaratan & daftar' : 'Lihat informasi' }} <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('sidebar')
    @if ($announcements->isNotEmpty())
        <div class="oa-widget">
            <h2 class="oa-widget__title">Info pendaftaran</h2>
            @foreach ($announcements as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="oa-mini-post">
                    <img src="{{ $item->cover_url }}" alt="" loading="lazy">
                    <span class="min-w-0"><strong>{{ $item->title }}</strong><small>{{ tgl($item->published_at, 'd M Y') }}</small></span>
                </a>
            @endforeach
        </div>
    @endif
@endpush
