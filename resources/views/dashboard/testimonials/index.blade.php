@extends('layouts.dashboard')

@section('title', 'Ulasan / Testimoni')
@section('page_title', 'Ulasan / Testimoni')
@section('page_subtitle', 'Tampil di Beranda dan halaman Keunikan.')
@section('actions')
    <a href="{{ route('dashboard.testimonials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
@endsection

@section('content')
    <div class="row g-3">
        @forelse ($items as $t)
            <div class="col-md-6 col-xl-4">
                <div class="card dash-card h-100">
                    <div class="card-body">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <img src="{{ $t->photo_url }}" class="dash-thumb dash-thumb--round" alt="">
                            <div><strong>{{ $t->name }}</strong><div class="small text-muted-d">{{ $t->role }}</div></div>
                        </div>
                        <p class="small mb-2">“{{ \Illuminate\Support\Str::limit($t->content, 150) }}”</p>
                        @include('dashboard.partials.status', ['active' => $t->is_active])
                        @if ($t->show_on_home)<span class="badge badge-soft">Di Beranda</span>@endif
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-1">
                        <a href="{{ route('dashboard.testimonials.edit', $t) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Ubah</a>
                        @include('dashboard.partials.delete', ['url' => route('dashboard.testimonials.destroy', $t), 'label' => 'testimoni '.$t->name])
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="card dash-card">@include('dashboard.partials.empty', ['icon' => 'bi-chat-quote', 'text' => 'Belum ada testimoni.'])</div></div>
        @endforelse
    </div>
@endsection
