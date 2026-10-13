@extends('layouts.web-page')

@section('title', $post->title)
@section('description', $post->summary)
@section('page_title', $post->title)
@section('page_banner', $post->cover_url)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('news.index', $post->channel === 'pendaftaran' ? ['kanal' => 'pendaftaran'] : []) }}">{{ $menu?->title ?? 'Berita' }}</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('no_intro', true)

@section('page')
    <article class="oa-panel">
        <div class="oa-card__meta mb-3">
            <span><i class="bi bi-calendar3"></i>{{ tgl($post->published_at, 'l, d F Y') }}</span>
            @if ($post->category)<span><i class="bi bi-tag"></i>{{ $post->category->name }}</span>@endif
            @if ($post->author)<span><i class="bi bi-person"></i>{{ $post->author->name }}</span>@endif
            <span><i class="bi bi-clock"></i>{{ $post->reading_time }} menit baca</span>
            <span><i class="bi bi-eye"></i>{{ number_format($post->views, 0, ',', '.') }}x dibaca</span>
        </div>
        @if ($post->cover)
            <a href="{{ $post->cover_url }}" class="glightbox"><img src="{{ $post->cover_url }}" alt="{{ $post->title }}" class="oa-cover mb-3"></a>
        @endif
        <div class="prose">{!! $post->body !!}</div>

        {{-- Tombol bagikan bisa dimatikan admin di Studio › Berita › Fitur bantuan pengunjung --}}
        @if (\App\Support\PageOptions::for($menu, 'berita')['share'])
            <hr class="my-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <strong class="small me-1">Bagikan:</strong>
                <a class="btn btn-sm btn-outline-primary" href="https://wa.me/?text={{ rawurlencode($post->title.' '.url()->current()) }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                <a class="btn btn-sm btn-outline-primary" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i> Facebook</a>
                <button type="button" class="btn btn-sm btn-outline-primary" data-copy="{{ url()->current() }}"><i class="bi bi-link-45deg"></i> Salin link</button>
            </div>
        @endif
    </article>

    @if ($related->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>Berita lainnya</h2></div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @foreach ($related as $item)
                <div class="col">@include('web.news._card', ['post' => $item])</div>
            @endforeach
        </div>
    @endif
@endsection
