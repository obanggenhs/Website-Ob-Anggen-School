@extends('layouts.dashboard')

@section('title', 'Keunikan Sekolah')
@section('page_title', 'Keunikan Sekolah')
@section('actions')
    <a href="{{ route('dashboard.uniqueness.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
@endsection

@section('content')
    <div class="row g-3">
        @forelse ($items as $item)
            <div class="col-md-6 col-xl-4">
                <div class="card dash-card h-100 overflow-hidden">
                    <img src="{{ $item->image_url }}" alt="" style="aspect-ratio:16/9;object-fit:cover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2 mb-1">
                            <h3 class="h6 mb-0"><i class="bi {{ $item->icon }} text-primary"></i> {{ $item->title }}</h3>
                            <span class="small text-muted-d">#{{ $item->position }}</span>
                        </div>
                        <p class="small text-muted-d mb-2">{{ \Illuminate\Support\Str::limit($item->excerpt, 110) }}</p>
                        @include('dashboard.partials.status', ['active' => $item->is_active])
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-1">
                        <a href="{{ route('dashboard.uniqueness.edit', $item) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Ubah</a>
                        @include('dashboard.partials.delete', ['url' => route('dashboard.uniqueness.destroy', $item), 'label' => $item->title])
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="card dash-card">@include('dashboard.partials.empty', ['icon' => 'bi-stars', 'text' => 'Belum ada keunikan.'])</div></div>
        @endforelse
    </div>
@endsection
