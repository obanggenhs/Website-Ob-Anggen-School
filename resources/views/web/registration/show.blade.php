@extends('layouts.web-page')

@section('title', $type->name)
@section('page_title', $type->name)
@section('page_subtitle', $type->summary)
@section('page_banner', $type->image_url)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('registration.index', ['untuk' => $type->audience]) }}">{{ $menu?->title ?? 'Pendaftaran' }}</a></li>
    <li class="breadcrumb-item active">{{ $type->name }}</li>
@endsection
@section('no_intro', true)

@section('page')
    @php $open = $type->isAccepting(); @endphp

    <div class="oa-panel mb-4 d-flex flex-wrap gap-3 align-items-center justify-content-between" style="border-left: 4px solid {{ $open ? '#2f9e6a' : '#9a8f8b' }}">
        <div>
            <span class="oa-badge {{ $open ? 'oa-badge--green' : 'oa-badge--grey' }} mb-1">{{ $type->status_label }}</span>
            <div class="small text-muted-oa">
                @if ($type->period_label)<i class="bi bi-calendar-range me-1"></i> Periode {{ $type->period_label }}@endif
                @if ($type->quota) · Kuota {{ $type->quota }} orang @endif
                @if ($type->academic_year) · Tahun ajaran {{ $type->academic_year }} @endif
            </div>
        </div>
        @if ($open)
            <a href="{{ route('registration.form', $type->slug) }}" class="btn btn-accent btn-lg"><i class="bi bi-pencil-square me-1"></i> Isi formulir</a>
        @endif
    </div>

    @if ($type->description)
        <div class="oa-panel mb-4">
            <h2 class="h5 mb-3">Tentang jalur ini</h2>
            <div class="prose">{!! $type->description !!}</div>
        </div>
    @endif

    <div class="row g-4">
        @if ($type->requirements)
            <div class="col-md-6">
                <div class="oa-panel h-100">
                    <h2 class="h6 mb-3"><i class="bi bi-folder-check me-1 text-primary"></i> Dokumen yang disiapkan</h2>
                    <ul class="oa-checklist">@foreach (lines($type->requirements) as $doc)<li>{{ $doc }}</li>@endforeach</ul>
                </div>
            </div>
        @endif
        @if ($type->steps)
            <div class="col-md-6">
                <div class="oa-panel h-100">
                    <h2 class="h6 mb-3"><i class="bi bi-signpost-split me-1 text-primary"></i> Tahapan seleksi</h2>
                    <ol class="oa-steps">@foreach (lines($type->steps) as $step)<li>{{ $step }}</li>@endforeach</ol>
                </div>
            </div>
        @endif
    </div>

    @if ($others->isNotEmpty())
        <div class="oa-sechead mt-5"><h2>Jalur lainnya</h2></div>
        <div class="oa-chips">
            @foreach ($others as $o)
                <a href="{{ route('registration.show', $o->slug) }}" class="oa-chip">{{ $o->name }} <span class="text-muted-oa">· {{ $o->status_label }}</span></a>
            @endforeach
        </div>
    @endif
@endsection
