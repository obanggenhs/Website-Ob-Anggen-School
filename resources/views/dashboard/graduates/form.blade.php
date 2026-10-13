@extends('layouts.dashboard')

@php $editing = $graduate->exists; @endphp
@section('title', $editing ? 'Ubah lulusan' : 'Tambah lulusan')
@section('page_title', $editing ? 'Ubah data lulusan' : 'Tambah lulusan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.graduates.index') }}">Kelulusan</a></li>
    <li class="breadcrumb-item active">{{ $editing ? $graduate->name : 'Tambah' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.graduates.update', $graduate) : route('dashboard.graduates.store') }}" enctype="multipart/form-data" class="card dash-card">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3">
                        <x-form.input name="name" label="Nama lengkap" :value="$graduate->name" required />
                        <x-form.select name="level" label="Jenjang" :options="\App\Models\Graduate::LEVELS" :value="$graduate->level" col="col-md-4" />
                        <x-form.input name="graduation_year" label="Tahun lulus" type="number" :value="$graduate->graduation_year" required col="col-md-4" />
                        <x-form.input name="class_name" label="Kelas / jurusan" :value="$graduate->class_name" col="col-md-4" />
                        <x-form.input name="predicate" label="Predikat" :value="$graduate->predicate" col="col-md-6" placeholder="Sangat Memuaskan" />
                        <x-form.input name="continue_to" label="Melanjutkan ke" :value="$graduate->continue_to" col="col-md-6" placeholder="Nama kampus / pekerjaan" />
                        <x-form.textarea name="testimony" label="Kesan & pesan" :value="$graduate->testimony" rows="4" />
                        <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$graduate->is_active" />
                    </div>
                </div>
                <div class="col-lg-4"><div class="row"><x-form.image name="photo" label="Foto (potret)" :value="$graduate->photo" /></div></div>
            </div>
            <x-form.buttons :back="route('dashboard.graduates.index')" />
        </div>
    </form>
@endsection
