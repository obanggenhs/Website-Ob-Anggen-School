@extends('layouts.dashboard')

@php $editing = $item->exists; @endphp
@section('title', $editing ? 'Ubah testimoni' : 'Tambah testimoni')
@section('page_title', $editing ? 'Ubah testimoni' : 'Tambah testimoni')

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.testimonials.update', $item) : route('dashboard.testimonials.store') }}" enctype="multipart/form-data" class="card dash-card" style="max-width:900px">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-8"><div class="row g-3">
                    <x-form.input name="name" label="Nama" :value="$item->name" required col="col-md-6" />
                    <x-form.input name="role" label="Keterangan" :value="$item->role" col="col-md-6" placeholder="Alumni 2024 / Orang tua siswa" />
                    <x-form.textarea name="content" label="Isi testimoni" :value="$item->content" rows="5" required />
                    <x-form.input name="position" label="Urutan" type="number" :value="$item->position" col="col-md-4" />
                    <x-form.toggle name="show_on_home" label="Tampilkan di Beranda" :checked="$item->show_on_home" col="col-md-4" />
                    <x-form.toggle name="is_active" label="Aktif" :checked="$item->is_active" col="col-md-4" />
                </div></div>
                <div class="col-md-4"><div class="row"><x-form.image name="photo" label="Foto" :value="$item->photo" /></div></div>
            </div>
            <x-form.buttons :back="route('dashboard.testimonials.index')" />
        </div>
    </form>
@endsection
