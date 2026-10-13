@extends('layouts.print')

@section('title', 'Kartu Pendaftaran '.$registration->number)

@section('content')
    @php $r = $registration; @endphp
    <h2 style="font-size:16px" class="mb-1">KARTU PENDAFTARAN</h2>
    <p class="mb-3">{{ $r->type->name }}{{ $r->type->academic_year ? ' · Tahun ajaran '.$r->type->academic_year : '' }}</p>
    <table class="data w-100 mb-3">
        <tr><td>Nomor pendaftaran</td><td><strong style="font-size:16px">{{ $r->number }}</strong></td></tr>
        <tr><td>Nama lengkap</td><td>{{ $r->full_name }}</td></tr>
        <tr><td>Email / HP</td><td>{{ $r->email }} / {{ $r->phone }}</td></tr>
        @foreach ($r->type->fields->where('type', '!=', 'file') as $field)
            <tr><td>{{ $field->label }}</td><td>{{ ($r->data[$field->name] ?? null) ?: '-' }}</td></tr>
        @endforeach
        <tr><td>Status</td><td><strong>{{ $r->status_label }}</strong></td></tr>
        @if ($r->schedule_at)
            <tr><td>Jadwal tes / wawancara</td><td>{{ tgl($r->schedule_at, 'l, d F Y') }} pukul {{ $r->schedule_at->format('H.i') }} WIT — {{ $r->schedule_place }}</td></tr>
        @endif
        <tr><td>Tanggal daftar</td><td>{{ tgl($r->created_at, 'd F Y, H.i') }}</td></tr>
    </table>
    <div class="d-flex justify-content-between mt-5" style="font-size:12px">
        <div>Dicetak: {{ tgl(now(), 'd F Y, H.i') }}</div>
        <div class="text-center">Panitia Pendaftaran<br><br><br><br>(...........................)</div>
    </div>
@endsection
