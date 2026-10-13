@extends('layouts.dashboard')

@php $editing = $event->exists; @endphp
@section('title', $editing ? 'Ubah agenda' : 'Tambah agenda')
@section('page_title', $editing ? 'Ubah agenda' : 'Tambah agenda')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.calendar.index') }}">Kalender</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Tambah' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.calendar.update', $event) : route('dashboard.calendar.store') }}" class="card dash-card" style="max-width:900px">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="card-body">
            <div class="row g-3">
                <x-form.input name="title" label="Nama agenda" :value="$event->title" required />
                <x-form.input name="start_date" label="Tanggal mulai" type="date" :value="$event->start_date?->format('Y-m-d')" required col="col-md-4" />
                <x-form.input name="end_date" label="Tanggal selesai" type="date" :value="$event->end_date?->format('Y-m-d')" col="col-md-4" help="Kosongkan bila hanya sehari." />
                <x-form.input name="time_info" label="Waktu" :value="$event->time_info" col="col-md-4" placeholder="08.00 WIT" />
                <x-form.select name="category" label="Kategori" :options="collect(\App\Models\CalendarEvent::CATEGORIES)->map(fn ($c) => $c['label'])->all()" :value="$event->category" col="col-md-4" />
                <x-form.select name="level" label="Jenjang" :options="\App\Models\CalendarEvent::LEVELS" :value="$event->level" col="col-md-4" />
                <x-form.input name="academic_year" label="Tahun ajaran" :value="$event->academic_year" col="col-md-4" placeholder="2026/2027" />
                <x-form.input name="location" label="Tempat" :value="$event->location" />
                <x-form.textarea name="description" label="Keterangan" :value="$event->description" rows="3" />
                <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$event->is_active" />
            </div>
            <x-form.buttons :back="route('dashboard.calendar.index')" />
        </div>
    </form>
@endsection
