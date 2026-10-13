@extends('layouts.web-page')

@section('title', $menu->title)

@section('page')
    @if ($blocks->isEmpty())
        <div class="oa-empty"><i class="bi bi-file-earmark-text"></i>Isi halaman ini sedang disiapkan.</div>
    @else
        <div class="row oa-blocks">
            @include('web.partials.blocks', ['list' => $blocks])
        </div>
    @endif
@endsection
