@extends('layouts.dashboard')

@section('title', 'Kategori')
@section('page_title', 'Kategori')
@section('page_subtitle', 'Pengelompokan berita dan album galeri.')

@section('content')
    <div class="row g-4">
        @foreach ($types as $type)
            <div class="col-lg-6">
                <div class="card dash-card h-100">
                    <div class="card-header"><h2>Kategori {{ \App\Models\Category::TYPES[$type] }}</h2></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.categories.store') }}" class="input-group mb-3">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="text" name="name" class="form-control" placeholder="Nama kategori baru" required maxlength="60">
                            <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</button>
                        </form>
                        <ul class="dash-list">
                            @forelse ($categories[$type] ?? [] as $cat)
                                <li>
                                    <form method="POST" action="{{ route('dashboard.categories.update', $cat) }}" class="input-group input-group-sm flex-fill">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $cat->name }}" class="form-control" required>
                                        <span class="input-group-text">{{ $type === 'news' ? $cat->news_count.' berita' : $cat->albums_count.' album' }}</span>
                                        <button class="btn btn-soft" title="Simpan nama"><i class="bi bi-check2"></i></button>
                                    </form>
                                    @include('dashboard.partials.delete', ['url' => route('dashboard.categories.destroy', $cat), 'label' => 'kategori '.$cat->name])
                                </li>
                            @empty
                                <li class="small text-muted-d">Belum ada kategori.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
