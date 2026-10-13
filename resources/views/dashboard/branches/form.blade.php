@extends('layouts.dashboard')

@php $editing = $branch->exists; @endphp
@section('title', $editing ? 'Ubah kampus' : 'Tambah kampus')
@section('page_title', $editing ? 'Ubah kampus '.$branch->name : 'Tambah kampus')

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.branches.update', $branch) : route('dashboard.branches.store') }}" enctype="multipart/form-data" class="card dash-card" style="max-width:900px">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-7"><div class="row g-3">
                    <x-form.input name="name" label="Nama kampus" :value="$branch->name" required />
                    <x-form.input name="regency" label="Kabupaten" :value="$branch->regency" />
                    <x-form.textarea name="description" label="Deskripsi singkat" :value="$branch->description" rows="4" />
                    <x-form.input name="position" label="Urutan" type="number" :value="$branch->position" col="col-md-6" />
                    <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$branch->is_active" col="col-md-6 pt-md-4" />
                </div></div>
                <div class="col-md-5"><div class="row"><x-form.image name="image" label="Foto kampus" :value="$branch->image" /></div></div>
            </div>
            <x-form.buttons :back="route('dashboard.branches.index')" />
        </div>
    </form>
@endsection
