@extends('layouts.web-full')

@section('title', $menu?->title ?? 'Guru & Staf')

@section('page')
    @if ($leaders->isNotEmpty() && ! $category && ! $branchId && ! request('cari'))
        <div class="oa-sechead"><div><h2>Pimpinan sekolah</h2></div></div>
        <div class="row g-3 mb-5">
            @foreach ($leaders as $person)
                <div class="col-6 col-md-3" data-reveal>@include('web.staff._card', ['person' => $person])</div>
            @endforeach
        </div>
    @endif

    <form class="oa-filterbar" method="GET">
        <div class="oa-chips">
            <a href="{{ route('staff.index', array_filter(['kampus' => $branchId])) }}" class="oa-chip {{ ! $category ? 'active' : '' }}">Semua</a>
            @foreach (\App\Models\Staff::CATEGORIES as $key => $label)
                <a href="{{ route('staff.index', array_filter(['kategori' => $key, 'kampus' => $branchId])) }}" class="oa-chip {{ $category === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
        <div class="d-flex gap-2">
            @if ($category)<input type="hidden" name="kategori" value="{{ $category }}">@endif
            <select name="kampus" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                <option value="">Semua kampus</option>
                @foreach ($branches as $b)<option value="{{ $b->id }}" @selected($branchId === $b->id)>{{ $b->name }}</option>@endforeach
            </select>
            <div class="oa-search"><i class="bi bi-search"></i><input type="search" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama..."></div>
        </div>
    </form>

    @forelse ($staff as $group => $people)
        <div class="oa-sechead mt-2"><h2 class="h5">{{ \App\Models\Staff::CATEGORIES[$group] ?? $group }}</h2></div>
        <div class="row g-3 mb-4">
            @foreach ($people as $person)
                <div class="col-6 col-md-4 col-lg-3" data-reveal>@include('web.staff._card', ['person' => $person])</div>
            @endforeach
        </div>
    @empty
        <div class="oa-empty"><i class="bi bi-people"></i>Data guru & staf tidak ditemukan.</div>
    @endforelse
@endsection
