@extends('layouts.dashboard')

@php $editing = $person->exists; @endphp
@section('title', $editing ? 'Ubah data' : 'Tambah guru / staf')
@section('page_title', $editing ? 'Ubah: '.$person->name : 'Tambah guru / staf')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.staff.index') }}">Guru & Staf</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Tambah' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.staff.update', $person) : route('dashboard.staff.store') }}" enctype="multipart/form-data" class="card dash-card">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3">
                        <x-form.input name="name" label="Nama lengkap" :value="$person->name" required col="col-md-8" />
                        <x-form.input name="position" label="Urutan tampil" type="number" :value="$person->position" col="col-md-4" />
                        <x-form.select name="category" label="Kelompok" :options="\App\Models\Staff::CATEGORIES" :value="$person->category" col="col-md-6" />
                        <x-form.select name="branch_id" label="Kampus" :options="$branches->pluck('name', 'id')->all()" :value="$person->branch_id" placeholder="— Semua kampus —" col="col-md-6" />
                        <x-form.input name="position_title" label="Jabatan" :value="$person->position_title" col="col-md-6" placeholder="Kepala Sekolah SD Bokondini" />
                        <x-form.input name="subject" label="Bidang / mata pelajaran" :value="$person->subject" col="col-md-6" />
                        <x-form.input name="education" label="Pendidikan terakhir" :value="$person->education" col="col-md-4" />
                        <x-form.input name="joined_year" label="Bergabung sejak" type="number" :value="$person->joined_year" col="col-md-4" />
                        <x-form.input name="phone" label="No. HP" :value="$person->phone" col="col-md-4" />
                        <x-form.input name="email" label="Email" type="email" :value="$person->email" />
                        <x-form.textarea name="about" label="Tentang" :value="$person->about" rows="3" />
                        <x-form.textarea name="duties" label="Tugas (satu baris satu tugas)" :value="$person->duties" rows="3" />
                        <x-form.toggle name="is_featured" label="Tampilkan di bagian Pimpinan sekolah" :checked="$person->is_featured" col="col-md-6" />
                        <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$person->is_active" col="col-md-6" />
                    </div>
                </div>
                <div class="col-lg-4"><div class="row"><x-form.image name="photo" label="Foto" :value="$person->photo" /></div></div>
            </div>
            <x-form.buttons :back="route('dashboard.staff.index')" />
        </div>
    </form>
@endsection
