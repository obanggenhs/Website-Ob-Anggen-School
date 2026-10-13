@extends('layouts.dashboard')

@php $editing = $item->exists; @endphp
@section('title', $editing ? 'Ubah keunikan' : 'Tambah keunikan')
@section('page_title', $editing ? 'Ubah: '.$item->title : 'Tambah keunikan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.uniqueness.index') }}">Keunikan</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Tambah' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.uniqueness.update', $item) : route('dashboard.uniqueness.store') }}" enctype="multipart/form-data">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card dash-card"><div class="card-body"><div class="row g-3">
                    <x-form.input name="title" label="Judul" :value="$item->title" required col="col-md-8" />
                    <x-form.input name="position" label="Urutan" type="number" :value="$item->position" col="col-md-4" />
                    <x-form.textarea name="excerpt" label="Ringkasan" :value="$item->excerpt" rows="2" />
                    <x-form.editor name="content" label="Isi lengkap" :value="$item->content" :height="280" />
                    <x-form.input name="video_url" label="Link video YouTube" type="url" :value="$item->video_url" />
                </div></div></div>
            </div>
            <div class="col-lg-4">
                <div class="card dash-card mb-4"><div class="card-body"><div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="f_icon">Ikon</label>
                        <div class="input-group"><span class="input-group-text"><i id="uIcon"></i></span><input type="text" name="icon" id="f_icon" value="{{ old('icon', $item->icon) }}" class="form-control" data-icon-input="uIcon"></div>
                    </div>
                    <x-form.image name="image" label="Gambar utama" :value="$item->image" />
                    <div class="col-12">
                        <label class="form-label">Foto dokumentasi</label>
                        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
                        @if (! empty($item->photos))
                            <div class="photo-grid mt-2" style="grid-template-columns: repeat(3, 1fr)">
                                @foreach ($item->photos as $i => $photo)
                                    <label class="photo-grid__item"><img src="{{ media_url($photo) }}" alt=""><span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_photos[]" value="{{ $i }}" title="Centang untuk hapus"></span></label>
                                @endforeach
                            </div>
                            <div class="form-text">Centang foto yang ingin dihapus.</div>
                        @endif
                    </div>
                    <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$item->is_active" />
                </div></div></div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan</button>
                    <a href="{{ route('dashboard.uniqueness.index') }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
