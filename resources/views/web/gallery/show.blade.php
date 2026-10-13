@extends('layouts.web-full')

@section('title', $album->title)
@section('page_title', $album->title)
@section('page_subtitle', $album->description)
@section('page_banner', $album->cover_url)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}">{{ $menu?->title ?? 'Galeri' }}</a></li>
    <li class="breadcrumb-item active">{{ $album->title }}</li>
@endsection
@section('no_intro', true)

@section('page')
    <div class="d-flex flex-wrap gap-3 align-items-center mb-3 small text-muted-oa">
        @if ($album->event_date)<span><i class="bi bi-calendar3 me-1"></i>{{ tgl($album->event_date, 'l, d F Y') }}</span>@endif
        @if ($album->category)<span><i class="bi bi-tag me-1"></i>{{ $album->category->name }}</span>@endif
        <span><i class="bi bi-images me-1"></i>{{ $album->photos->count() }} foto</span>
    </div>

    @if ($album->video_url)
        <div class="mb-4">
            @include('web.partials.video', ['url' => $album->video_url, 'title' => $album->title])
        </div>
    @endif

    <div class="oa-gallery">
        @foreach ($album->photos as $photo)
            <a href="{{ $photo->url }}" class="glightbox" data-gallery="album" data-description="{{ $photo->caption }}">
                <img src="{{ $photo->url }}" alt="{{ $photo->caption ?: $album->title }}" loading="lazy">
            </a>
        @endforeach
    </div>

    @if ($others->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>Album lainnya</h2></div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            @foreach ($others as $other)
                <div class="col">
                    <a href="{{ route('gallery.show', $other->slug) }}" class="oa-card oa-card--mini">
                        <div class="oa-card__img oa-card__img--square"><img src="{{ $other->cover_url }}" alt="{{ $other->title }}" loading="lazy"></div>
                        <div class="oa-card__body"><h3 class="oa-card__title">{{ $other->title }}</h3></div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection