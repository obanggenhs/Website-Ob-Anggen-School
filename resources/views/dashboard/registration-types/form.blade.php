@extends('layouts.dashboard')

@php
    $editing = $type->exists;
    $tab = $tab ?? 'info';
@endphp

@section('title', $editing ? $type->name : 'Jalur baru')
@section('page_title', $editing ? $type->name : 'Jalur pendaftaran baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.registration-types.index') }}">Jalur & Formulir</a></li>
    <li class="breadcrumb-item active">{{ $editing ? $type->name : 'Baru' }}</li>
@endsection
@section('actions')
    @if ($editing)
        <a href="{{ route('registration.show', $type->slug) }}" target="_blank" class="btn btn-light"><i class="bi bi-eye"></i> Lihat di website</a>
    @endif
@endsection

@section('content')
    @if ($editing)
        <ul class="nav nav-pills mb-3 gap-1">
            <li class="nav-item"><a class="nav-link {{ $tab === 'info' ? 'active' : '' }}" href="{{ route('dashboard.registration-types.edit', $type) }}"><i class="bi bi-info-circle"></i> Informasi jalur</a></li>
            <li class="nav-item"><a class="nav-link {{ $tab === 'formulir' ? 'active' : '' }}" href="{{ route('dashboard.registration-types.edit', [$type, 'tab' => 'formulir']) }}"><i class="bi bi-ui-checks"></i> Isian formulir ({{ $type->fields->count() }})</a></li>
        </ul>
    @endif

    @if ($tab === 'formulir' && $editing)
        @include('dashboard.registration-types._fields')
    @else
        <form method="POST" action="{{ $editing ? route('dashboard.registration-types.update', $type) : route('dashboard.registration-types.store') }}" enctype="multipart/form-data">
            @csrf @if ($editing) @method('PUT') @endif
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card dash-card"><div class="card-body"><div class="row g-3">
                        <x-form.input name="name" label="Nama jalur" :value="$type->name" required placeholder="Pendaftaran Siswa SMA" />
                        <x-form.select name="audience" label="Untuk" :options="\App\Models\RegistrationType::AUDIENCES" :value="$type->audience" col="col-md-4" help="{{ $editing ? '' : 'Menentukan formulir bawaan.' }}" />
                        <x-form.input name="level_label" label="Jenjang / posisi" :value="$type->level_label" col="col-md-4" placeholder="SMA / Guru" />
                        <x-form.input name="academic_year" label="Tahun ajaran" :value="$type->academic_year" col="col-md-4" placeholder="2027/2028" />
                        <x-form.textarea name="summary" label="Ringkasan" :value="$type->summary" rows="2" />
                        <x-form.editor name="description" label="Penjelasan lengkap" :value="$type->description" :height="180" />
                        <x-form.textarea name="requirements" label="Dokumen yang disiapkan (satu baris satu dokumen)" :value="$type->requirements" rows="4" col="col-md-6" />
                        <x-form.textarea name="steps" label="Tahapan seleksi (satu baris satu tahap)" :value="$type->steps" rows="4" col="col-md-6" />
                        <x-form.textarea name="accepted_message" label="Pesan untuk pendaftar yang diterima" :value="$type->accepted_message" rows="2" />
                    </div></div></div>
                </div>
                <div class="col-lg-4">
                    <div class="card dash-card mb-4">
                        <div class="card-header"><h2 class="dash-card__title">Periode & kuota</h2></div>
                        <div class="card-body"><div class="row g-3">
                            <x-form.toggle name="is_open" label="Pendaftaran dibuka" :checked="$type->is_open" />
                            <x-form.input name="open_at" label="Mulai" type="date" :value="$type->open_at?->format('Y-m-d')" col="col-6" />
                            <x-form.input name="close_at" label="Ditutup" type="date" :value="$type->close_at?->format('Y-m-d')" col="col-6" />
                            <x-form.input name="quota" label="Kuota (kosong = tanpa batas)" type="number" :value="$type->quota" col="col-6" />
                            <x-form.input name="position" label="Urutan" type="number" :value="$type->position" col="col-6" />
                            <x-form.image name="image" label="Gambar" :value="$type->image" />
                        </div></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> {{ $editing ? 'Simpan informasi' : 'Buat jalur' }}</button>
                        <a href="{{ route('dashboard.registration-types.index') }}" class="btn btn-light">Kembali</a>
                    </div>
                    @if ($editing)
                        <div class="mt-4 text-end">@include('dashboard.partials.delete', ['url' => route('dashboard.registration-types.destroy', $type), 'class' => 'btn-outline-danger btn-sm', 'text' => 'Hapus jalur', 'label' => 'jalur '.$type->name])</div>
                    @endif
                </div>
            </div>
        </form>
    @endif
@endsection
