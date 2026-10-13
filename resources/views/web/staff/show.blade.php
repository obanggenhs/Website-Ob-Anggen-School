@extends('layouts.web-page')

@section('title', $person->name)
@section('page_title', $person->name)
@section('page_subtitle', $person->position_title ?: $person->category_label)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">{{ $menu?->title ?? 'Guru & Staf' }}</a></li>
    <li class="breadcrumb-item active">{{ $person->name }}</li>
@endsection
@section('no_intro', true)

@section('page')
    <div class="oa-panel">
        <div class="row g-4">
            <div class="col-md-4">
                <img src="{{ $person->photo_url }}" alt="{{ $person->name }}" class="oa-portrait">
            </div>
            <div class="col-md-8">
                <span class="oa-badge mb-2">{{ $person->category_label }}</span>
                <h2 class="h4">{{ $person->name }}</h2>
                <p class="text-muted-oa">{{ $person->position_title }}</p>
                <ul class="oa-info-list mb-4">
                    @if ($person->branch)<li><span>Kampus</span><span>{{ $person->branch->name }}</span></li>@endif
                    @if ($person->subject)<li><span>Bidang</span><span>{{ $person->subject }}</span></li>@endif
                    @if ($person->education)<li><span>Pendidikan</span><span>{{ $person->education }}</span></li>@endif
                    @if ($person->joined_year)<li><span>Bergabung sejak</span><span>{{ $person->joined_year }}</span></li>@endif
                    @if ($person->email)<li><span>Email</span><span><a href="mailto:{{ $person->email }}">{{ $person->email }}</a></span></li>@endif
                </ul>
                @if ($person->about)
                    <h3 class="h6">Tentang</h3>
                    <p>{{ $person->about }}</p>
                @endif
                @if ($person->duties)
                    <h3 class="h6 mt-3">Tugas</h3>
                    <ul class="oa-checklist">@foreach (lines($person->duties) as $duty)<li>{{ $duty }}</li>@endforeach</ul>
                @endif
            </div>
        </div>
    </div>

    @if ($colleagues->isNotEmpty())
        <div class="oa-sechead mt-4"><h2>Rekan sejawat</h2></div>
        <div class="row g-3">
            @foreach ($colleagues as $c)
                <div class="col-6 col-md-3">@include('web.staff._card', ['person' => $c])</div>
            @endforeach
        </div>
    @endif
@endsection