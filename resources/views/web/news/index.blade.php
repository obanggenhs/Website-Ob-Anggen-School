@extends('layouts.web-full')

@section('title', $menu?->title ?? 'Berita')
@section('page_title', $category?->name ?? ($menu?->title ?? 'Berita'))

@section('page')
    {{-- Bilah bantuan pengunjung: diatur admin di Studio › Berita › Fitur bantuan pengunjung --}}
    <x-list-toolbar :opt="$opt" type="berita" :in="$in" :years="$years" :result="$news" noun="berita"
        :chips="['param' => 'kategori', 'current' => $category?->slug ?? '', 'all' => 'Semua',
                 'items' => $categories->where('news_count', '>', 0)->pluck('name', 'slug')->all()]"
        :hidden="['kanal' => $channel]" placeholder="Cari berita..." />

    @if ($news->isEmpty())
        <div class="oa-empty"><i class="bi bi-newspaper"></i>Belum ada berita{{ $in['cari'] !== '' || $in['tahun'] !== '' ? ' yang cocok dengan pencarian' : '' }}.</div>
    @else
        {{-- 3 berita per baris --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 oa-results {{ $in['tampil'] === 'daftar' ? 'is-list' : '' }}">
            @foreach ($news as $post)
                <div class="col">@include('web.news._card', ['post' => $post])</div>
            @endforeach
        </div>
        <div class="mt-4 {{ $opt['count'] ? 'oa-pager--counted' : '' }}">{{ $news->links() }}</div>
    @endif
@endsection
