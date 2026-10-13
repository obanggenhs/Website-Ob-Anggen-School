{{-- Halaman detail satu kartu dari blok "Koleksi Kartu" --}}
@extends('layouts.web-page')

@php
    $shape = $item->block->cardSetting('shape');
    $options = $menu->pageOptions();
    $metaList = $item->metaList();
@endphp

@section('title', $item->title.' · '.$menu->title)
@section('description', $item->summary)
@section('page_title', $item->title)
@section('page_subtitle', $item->subtitle)
@if ($item->image)
    @section('page_banner', $item->image_url)
@endif
@section('breadcrumb')
    @if ($menu->parent)<li class="breadcrumb-item">{{ $menu->parent->title }}</li>@endif
    <li class="breadcrumb-item"><a href="{{ $menu->link }}">{{ $menu->title }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($item->title, 40) }}</li>
@endsection
@section('no_intro', true)

@section('page')
    <article class="oa-panel">
        <div class="row g-4">
            @if ($item->image && $shape !== 'none')
                <div class="col-md-5">
                    <a href="{{ $item->image_url }}" class="glightbox" data-gallery="kartu">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="{{ $shape === 'portrait' ? 'oa-portrait' : 'oa-cover' }} w-100">
                    </a>
                </div>
            @endif
            <div class="{{ $item->image && $shape !== 'none' ? 'col-md-7' : 'col-12' }}">
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @if ($item->category)<span class="oa-badge">{{ $item->category }}</span>@endif
                    @if ($item->year)<span class="oa-badge oa-badge--grey"><i class="bi bi-calendar3"></i> {{ $item->year }}</span>@endif
                </div>
                <h2 class="h4 mb-1">{{ $item->title }}</h2>
                @if ($item->subtitle)<p class="text-muted-oa mb-3">{{ $item->subtitle }}</p>@endif

                @if ($metaList)
                    <ul class="oa-info-list mb-3">
                        @foreach ($metaList as $row)
                            <li><span>{{ $row['label'] ?: 'Info' }}</span><span>{{ $row['value'] }}</span></li>
                        @endforeach
                    </ul>
                @endif

                @if ($item->excerpt)<p class="mb-0">{{ $item->excerpt }}</p>@endif

                @if ($item->document)
                    <a href="#dokumen" class="btn btn-primary btn-sm mt-3">
                        <i class="bi {{ \App\Support\Media::isPdf($item->document) ? 'bi-book' : 'bi-download' }} me-1"></i>
                        {{ \App\Support\Media::isPdf($item->document) ? 'Baca dokumen' : 'Unduh dokumen' }}
                    </a>
                @endif
            </div>
        </div>

        @if (strip_tags((string) $item->body) !== '')
            <div class="prose mt-4">{!! $item->body !!}</div>
        @endif

        @if ($item->document)
            <div class="mt-4" id="dokumen">
                @include('web.partials.document', [
                    'path' => $item->document, 'name' => $item->document_name, 'size' => $item->document_size,
                    'title' => \App\Support\Media::displayName($item->document_name, $item->document, $item->title),
                    'mode' => 'reader', 'download' => (bool) $item->document_download,
                ])
            </div>
        @endif

        @if ($item->videoLink())
            <div class="mt-4">@include('web.partials.video', ['url' => $item->videoLink(), 'title' => $item->title])</div>
        @endif

        @if (! empty($item->photos))
            <h3 class="h6 mt-4 mb-2">Dokumentasi</h3>
            <div class="oa-gallery">
                @foreach ($item->photos as $photo)
                    <a href="{{ media_url($photo) }}" class="glightbox" data-gallery="kartu-foto"><img src="{{ media_url($photo) }}" alt="" loading="lazy"></a>
                @endforeach
            </div>
        @endif

        <hr class="my-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <a href="{{ $menu->link }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke {{ $menu->title }}</a>
            @if (! empty($options['share']))
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <strong class="small">Bagikan:</strong>
                    <a class="btn btn-sm btn-outline-primary" href="https://wa.me/?text={{ rawurlencode($item->title.' '.url()->current()) }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                    <a class="btn btn-sm btn-outline-primary" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-copy="{{ url()->current() }}"><i class="bi bi-link-45deg"></i> Salin link</button>
                </div>
            @endif
        </div>
    </article>

    @if ($related->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>{{ $item->category ? 'Lainnya di '.$item->category : 'Lainnya' }}</h2></div>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach ($related as $other)
                <div class="col">
                    @include('web.partials.collection-card', [
                        'card' => $other, 'url' => route('page.item', [$menu->slug, $other->slug]),
                        'shape' => $shape, 'excerpt' => false, 'button' => $item->block->cardSetting('button'),
                    ])
                </div>
            @endforeach
        </div>
    @endif
@endsection
