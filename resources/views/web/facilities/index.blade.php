@extends('layouts.web-full')

@section('title', $menu?->title ?? \App\Models\Facility::TYPES[$jenis])
@section('page_title', ($menu?->title ?? \App\Models\Facility::TYPES[$jenis]).($branch ? ' · '.$branch->name : ''))

@section('page')
    <div class="oa-filterbar">
        <div class="oa-chips">
            @foreach ($branches as $b)
                <a href="{{ route('facilities.index', ['jenis' => $jenis, 'kampus' => $b->id]) }}" class="oa-chip {{ $branch?->id === $b->id ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> {{ $b->name }}</a>
            @endforeach
        </div>
        @if ($levels->count() > 1)
            <div class="oa-chips">
                <a href="{{ route('facilities.index', ['jenis' => $jenis, 'kampus' => $branch?->id]) }}" class="oa-chip {{ ! $level ? 'active' : '' }}">Semua</a>
                @foreach (\App\Models\Facility::LEVELS as $key => $label)
                    @if ($levels->contains($key))
                        <a href="{{ route('facilities.index', ['jenis' => $jenis, 'kampus' => $branch?->id, 'jenjang' => $key]) }}" class="oa-chip {{ $level === $key ? 'active' : '' }}">{{ $label }}</a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    @if ($facilities->isEmpty())
        <div class="oa-empty"><i class="bi bi-building"></i>Belum ada data fasilitas untuk pilihan ini.</div>
    @else
        <div class="row g-3">
            @foreach ($facilities as $f)
                <div class="col-sm-6 col-lg-4 col-xl-3" data-reveal>
                    <a href="{{ route('facilities.show', $f->slug) }}" class="oa-card">
                        <div class="oa-card__img">
                            <img src="{{ $f->image_url }}" alt="{{ $f->name }}" loading="lazy">
                            <span class="oa-card__badge oa-badge">{{ $f->level_label }}</span>
                        </div>
                        <div class="oa-card__body">
                            <h3 class="oa-card__title">{{ $f->name }}</h3>
                            <span class="oa-card__meta">
                                @if ($f->label)<span><i class="bi bi-door-open"></i>{{ $f->label }}</span>@endif
                                @if ($f->capacity)<span><i class="bi bi-people"></i>{{ $f->capacity }}</span>@endif
                            </span>
                            <p class="oa-card__text">{{ $f->description }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
