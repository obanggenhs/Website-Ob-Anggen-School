@extends('layouts.dashboard')

@php $editing = $album->exists; @endphp

@section('title', $editing ? $album->title : 'Album baru')
@section('page_title', $editing ? 'Kelola album' : 'Album baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.gallery.index') }}">Galeri</a></li>
    <li class="breadcrumb-item active">{{ $editing ? $album->title : 'Baru' }}</li>
@endsection
@section('actions')
    @if ($editing)<a href="{{ route('gallery.show', $album->slug) }}" target="_blank" class="btn btn-light"><i class="bi bi-eye"></i> Lihat di website</a>@endif
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.gallery.update', $album) : route('dashboard.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card dash-card">
                    <div class="card-header"><h2 class="dash-card__title">Informasi album</h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <x-form.input name="title" label="Judul album" :value="$album->title" required />
                            <x-form.select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')->all()" :value="$album->category_id" placeholder="— Tanpa kategori —" />
                            <x-form.input name="event_date" label="Tanggal kegiatan" type="date" :value="$album->event_date?->format('Y-m-d')" />
                            <x-form.textarea name="description" label="Keterangan" :value="$album->description" rows="3" />
                            <x-form.input name="video_url" label="Link video YouTube (opsional)" type="url" :value="$album->video_url" />
                            <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$album->is_active" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card dash-card mb-4">
                    <div class="card-header"><h2 class="dash-card__title">{{ $editing ? 'Tambah foto' : 'Foto album' }}</h2></div>
                    <div class="card-body">
                        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple data-count-label="photoCount" @required(! $editing)>
                        <div class="form-text"><span id="photoCount"></span> Pilih banyak foto sekaligus, tanpa batas jumlah dan ukuran. File besar diunggah bertahap dengan tampilan progres.</div>
                    </div>
                </div>

                @if ($editing)
                    <div class="card dash-card">
                        <div class="card-header d-flex justify-content-between"><h2 class="dash-card__title">Foto di album ({{ $album->photos->count() }})</h2><small class="text-muted-d">Tulis keterangan di bawah foto</small></div>
                        <div class="card-body">
                            @if ($album->photos->isEmpty())
                                @include('dashboard.partials.empty', ['icon' => 'bi-image', 'text' => 'Album masih kosong.'])
                            @else
                                <div class="photo-grid">
                                    @foreach ($album->photos as $photo)
                                        <div class="photo-grid__item">
                                            <img src="{{ $photo->url }}" alt="">
                                            @if ($album->cover === $photo->image || (! $album->cover && $loop->first))
                                                <span class="photo-grid__badge badge text-bg-warning">Sampul</span>
                                            @endif
                                            <div class="photo-grid__tools">
                                                <button type="submit" form="cover{{ $photo->id }}" class="btn btn-sm btn-light btn-icon" title="Jadikan sampul"><i class="bi bi-star"></i></button>
                                                <button type="submit" form="del{{ $photo->id }}" class="btn btn-sm btn-danger btn-icon" title="Hapus foto"><i class="bi bi-trash"></i></button>
                                            </div>
                                            <input type="text" name="captions[{{ $photo->id }}]" value="{{ $photo->caption }}" class="form-control" placeholder="Keterangan...">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('dashboard.gallery.index') }}" class="btn btn-light">Kembali</a>
                    <button class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1"></i> {{ $editing ? 'Simpan album' : 'Buat album' }}</button>
                </div>
            </div>
        </div>
    </form>

    @if ($editing)
        @foreach ($album->photos as $photo)
            <form id="cover{{ $photo->id }}" method="POST" action="{{ route('dashboard.gallery.cover', [$album, $photo]) }}">@csrf</form>
            <form id="del{{ $photo->id }}" method="POST" action="{{ route('dashboard.gallery.photo.destroy', [$album, $photo]) }}" data-confirm="Hapus foto ini?">@csrf @method('DELETE')</form>
        @endforeach
    @endif
@endsection
