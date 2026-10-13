@extends('layouts.dashboard')

@section('title', 'Galeri')
@section('page_title', 'Galeri')
@section('page_subtitle', 'Satu album berisi banyak foto kegiatan (dan video YouTube bila ada).')
@section('actions')
    <a href="{{ route('dashboard.gallery.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Album baru</a>
@endsection

@section('content')
    <form class="dash-filter mb-3" method="GET">
        <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Semua kategori</option>
            @foreach ($categories as $cat)<option value="{{ $cat->id }}" @selected(request('kategori') == $cat->id)>{{ $cat->name }}</option>@endforeach
        </select>
        <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari album...">
        <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
    </form>

    @if ($albums->isEmpty())
        <div class="card dash-card">@include('dashboard.partials.empty', ['icon' => 'bi-images', 'text' => 'Belum ada album.', 'action' => route('dashboard.gallery.create'), 'actionLabel' => 'Buat album'])</div>
    @else
        <div class="row g-3">
            @foreach ($albums as $album)
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div class="card dash-card h-100 overflow-hidden {{ $album->is_active ? '' : 'opacity-75' }}">
                        <a href="{{ route('dashboard.gallery.edit', $album) }}"><img src="{{ $album->cover_url }}" alt="" class="w-100" style="aspect-ratio:16/10;object-fit:cover"></a>
                        <div class="card-body">
                            <h3 class="h6 mb-1 text-truncate">{{ $album->title }}</h3>
                            <div class="small text-muted-d mb-2">{{ $album->photos_count }} foto · {{ $album->event_date ? tgl($album->event_date, 'd M Y') : '-' }}</div>
                            <div class="d-flex gap-1 flex-wrap">
                                @if ($album->category)<span class="badge badge-soft">{{ $album->category->name }}</span>@endif
                                @if ($album->video_url)<span class="badge text-bg-dark"><i class="bi bi-play-fill"></i> Video</span>@endif
                                @unless ($album->is_active)<span class="badge text-bg-secondary">Disembunyikan</span>@endunless
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('gallery.show', $album->slug) }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> Lihat</a>
                            <div class="d-flex gap-1">
                                <a href="{{ route('dashboard.gallery.edit', $album) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Kelola</a>
                                @include('dashboard.partials.delete', ['url' => route('dashboard.gallery.destroy', $album), 'label' => 'album "'.$album->title.'" beserta semua fotonya'])
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $albums->links() }}</div>
    @endif
@endsection
