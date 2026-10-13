@extends('layouts.dashboard')

@php $editing = $facility->exists; @endphp
@section('title', $editing ? 'Ubah fasilitas' : 'Tambah fasilitas')
@section('page_title', $editing ? 'Ubah: '.$facility->name : 'Tambah fasilitas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.facilities.index') }}">Fasilitas</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Tambah' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.facilities.update', $facility) : route('dashboard.facilities.store') }}" enctype="multipart/form-data">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card dash-card"><div class="card-body"><div class="row g-3">
                    <x-form.input name="name" label="Nama fasilitas" :value="$facility->name" required col="col-md-8" />
                    <x-form.input name="position" label="Urutan" type="number" :value="$facility->position" col="col-md-4" />
                    <x-form.select name="type" label="Jenis" :options="\App\Models\Facility::TYPES" :value="$facility->type" col="col-md-4" />
                    <x-form.select name="branch_id" label="Kampus" :options="$branches->pluck('name', 'id')->all()" :value="$facility->branch_id" placeholder="— Pilih kampus —" col="col-md-4" />
                    <x-form.select name="level" label="Jenjang" :options="\App\Models\Facility::LEVELS" :value="$facility->level" col="col-md-4" />
                    <x-form.input name="label" label="Label ruangan" :value="$facility->label" col="col-md-6" placeholder="Ruang Kelas / Laboratorium / Kamar Putra" />
                    <x-form.input name="used_for" label="Digunakan untuk" :value="$facility->used_for" col="col-md-6" />
                    <x-form.input name="capacity" label="Kapasitas" :value="$facility->capacity" col="col-md-6" placeholder="30 siswa" />
                    <x-form.input name="area" label="Luas" :value="$facility->area" col="col-md-6" placeholder="64 m²" />
                    <x-form.textarea name="description" label="Deskripsi singkat" :value="$facility->description" rows="2" />
                    <x-form.editor name="detail" label="Penjelasan lengkap" :value="$facility->detail" :height="200" />
                </div></div></div>
            </div>
            <div class="col-lg-4">
                <div class="card dash-card mb-4"><div class="card-body"><div class="row g-3">
                    <x-form.image name="image" label="Foto utama" :value="$facility->image" />
                    <div class="col-12">
                        <label class="form-label">Foto tambahan</label>
                        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
                        @if (! empty($facility->photos))
                            <div class="photo-grid mt-2" style="grid-template-columns: repeat(3, 1fr)">
                                @foreach ($facility->photos as $i => $photo)
                                    <label class="photo-grid__item"><img src="{{ media_url($photo) }}" alt=""><span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_photos[]" value="{{ $i }}"></span></label>
                                @endforeach
                            </div>
                            <div class="form-text">Centang foto yang ingin dihapus.</div>
                        @endif
                    </div>
                    <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$facility->is_active" />
                </div></div></div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan</button>
                    <a href="{{ route('dashboard.facilities.index') }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
