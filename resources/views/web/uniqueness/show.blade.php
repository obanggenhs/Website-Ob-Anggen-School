@extends('layouts.web-page')

@section('title', $item->title)
@section('page_title', $item->title)
@section('page_subtitle', $item->excerpt)
@section('page_banner', $item->image_url)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('uniqueness.index') }}">{{ $menu?->title ?? 'Keunikan' }}</a></li>
    <li class="breadcrumb-item active">{{ $item->title }}</li>
@endsection
@section('no_intro', true)

@section('page')
    <article class="oa-panel">
        @if ($item->video_url)
            <div class="mb-3">
                @include('web.partials.video', ['url' => $item->video_url, 'title' => $item->title])
            </div>
        @endif
        <div class="prose">{!! $item->content !!}</div>

        @if (! empty($item->photos))
            <h2 class="h6 mt-3 mb-2">Dokumentasi</h2>
            <div class="oa-gallery">
                @foreach ($item->photos as $photo)
                    <a href="{{ media_url($photo) }}" class="glightbox" data-gallery="keunikan"><img src="{{ media_url($photo) }}" alt="" loading="lazy"></a>
                @endforeach
            </div>
        @endif
    </article>

    @if ($others->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>Keunikan lainnya</h2></div>
        <div class="oa-chips">
            @foreach ($others as $o)
                <a href="{{ route('uniqueness.show', $o->slug) }}" class="oa-chip"><i class="bi {{ $o->icon ?: 'bi-stars' }}"></i> {{ $o->title }}</a>
            @endforeach
        </div>
    @endif
@endsection