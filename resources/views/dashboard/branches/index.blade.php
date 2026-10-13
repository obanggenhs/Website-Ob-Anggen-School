@extends('layouts.dashboard')

@section('title', 'Kampus / Cabang')
@section('page_title', 'Kampus / Cabang')
@section('actions')
    <a href="{{ route('dashboard.branches.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah kampus</a>
@endsection

@section('content')
    <div class="row g-3">
        @forelse ($branches as $b)
            <div class="col-md-6 col-xl-4">
                <div class="card dash-card h-100 overflow-hidden">
                    <img src="{{ media_url($b->image) }}" alt="" style="aspect-ratio:16/9;object-fit:cover">
                    <div class="card-body">
                        <h3 class="h6 mb-1">{{ $b->name }}</h3>
                        <div class="small text-muted-d mb-2"><i class="bi bi-geo-alt"></i> {{ $b->regency }}</div>
                        <div class="d-flex gap-1 flex-wrap">
                            <span class="badge badge-soft">{{ $b->staff_count }} guru & staf</span>
                            <span class="badge badge-soft">{{ $b->facilities_count }} fasilitas</span>
                            @include('dashboard.partials.status', ['active' => $b->is_active])
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-1">
                        <a href="{{ route('dashboard.facilities.index', ['kampus' => $b->id]) }}" class="btn btn-sm btn-light">Fasilitas</a>
                        <a href="{{ route('dashboard.branches.edit', $b) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Ubah</a>
                        @include('dashboard.partials.delete', ['url' => route('dashboard.branches.destroy', $b), 'label' => 'kampus '.$b->name])
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="card dash-card">@include('dashboard.partials.empty', ['icon' => 'bi-geo-alt', 'text' => 'Belum ada kampus.'])</div></div>
        @endforelse
    </div>
@endsection
