@extends('layouts.web-page')

@section('title', $graduate->name)
@section('page_title', $graduate->name)
@section('page_subtitle', 'Lulusan '.$graduate->level_label.' angkatan '.$graduate->graduation_year)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('graduates.index') }}">{{ $menu?->title ?? 'Kelulusan' }}</a></li>
    <li class="breadcrumb-item active">{{ $graduate->name }}</li>
@endsection
@section('no_intro', true)

@section('page')
    <div class="oa-panel">
        <div class="row g-4">
            <div class="col-md-5">
                <img src="{{ $graduate->photo_url }}" alt="{{ $graduate->name }}" class="oa-portrait">
            </div>
            <div class="col-md-7">
                <h2 class="h4 mb-3">{{ $graduate->name }}</h2>
                <ul class="oa-info-list mb-4">
                    <li><span>Jenjang</span><span>{{ $graduate->level_label }}</span></li>
                    <li><span>Kelas</span><span>{{ $graduate->class_name ?: '-' }}</span></li>
                    <li><span>Tahun lulus</span><span>{{ $graduate->graduation_year }}</span></li>
                    <li><span>Predikat</span><span>{{ $graduate->predicate ?: '-' }}</span></li>
                    <li><span>Melanjutkan ke</span><span>{{ $graduate->continue_to ?: '-' }}</span></li>
                </ul>
                @if ($graduate->testimony)
                    <blockquote class="oa-quote">{{ $graduate->testimony }}</blockquote>
                @endif
            </div>
        </div>
    </div>

    @if ($friends->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>Teman seangkatan</h2></div>
        <div class="row g-3">
            @foreach ($friends as $f)
                <div class="col-6 col-md-3">
                    <a href="{{ route('graduates.show', $f->slug) }}" class="oa-card oa-person">
                        <div class="oa-card__img oa-card__img--portrait"><img src="{{ $f->photo_url }}" alt="{{ $f->name }}" loading="lazy"></div>
                        <div class="oa-card__body"><h3 class="oa-card__title">{{ $f->name }}</h3></div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection