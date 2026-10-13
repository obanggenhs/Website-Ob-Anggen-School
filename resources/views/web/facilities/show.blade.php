@extends('layouts.web-page')

@section('title', $facility->name)
@section('page_title', $facility->name)
@section('page_subtitle', ($facility->label ? $facility->label.' · ' : '').($facility->branch?->name ? 'Kampus '.$facility->branch->name : ''))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('facilities.index', ['jenis' => $facility->type, 'kampus' => $facility->branch_id]) }}">{{ $menu?->title ?? \App\Models\Facility::TYPES[$facility->type] }}</a></li>
    <li class="breadcrumb-item active">{{ $facility->name }}</li>
@endsection
@section('no_intro', true)

@section('page')
    <div class="oa-panel">
        <a href="{{ $facility->image_url }}" class="glightbox" data-gallery="fasilitas">
            <img src="{{ $facility->image_url }}" alt="{{ $facility->name }}" class="oa-cover mb-3">
        </a>
        <div class="row g-4">
            <div class="col-md-7">
                <div class="prose">{!! $facility->detail ?: e($facility->description) !!}</div>
            </div>
            <div class="col-md-5">
                <ul class="oa-info-list">
                    <li><span>Kampus</span><span>{{ $facility->branch?->name ?? '-' }}</span></li>
                    <li><span>Jenjang</span><span>{{ $facility->level_label }}</span></li>
                    @if ($facility->capacity)<li><span>Kapasitas</span><span>{{ $facility->capacity }}</span></li>@endif
                    @if ($facility->area)<li><span>Luas</span><span>{{ $facility->area }}</span></li>@endif
                    @if ($facility->used_for)<li><span>Digunakan untuk</span><span>{{ $facility->used_for }}</span></li>@endif
                </ul>
            </div>
        </div>
        @if (! empty($facility->photos))
            <div class="oa-gallery mt-3">
                @foreach ($facility->photos as $photo)
                    <a href="{{ media_url($photo) }}" class="glightbox" data-gallery="fasilitas"><img src="{{ media_url($photo) }}" alt="" loading="lazy"></a>
                @endforeach
            </div>
        @endif
    </div>

    @if ($others->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>Fasilitas lain di kampus ini</h2></div>
        <div class="row g-3">
            @foreach ($others as $o)
                <div class="col-6 col-md-3">
                    <a href="{{ route('facilities.show', $o->slug) }}" class="oa-card">
                        <div class="oa-card__img"><img src="{{ $o->image_url }}" alt="{{ $o->name }}" loading="lazy"></div>
                        <div class="oa-card__body"><h3 class="oa-card__title">{{ $o->name }}</h3></div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection