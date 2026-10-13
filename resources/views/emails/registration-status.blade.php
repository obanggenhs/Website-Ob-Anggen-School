@extends('emails.layout')

@section('body')
    <p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
    <p>Status pendaftaran <strong>{{ $registration->number }}</strong> ({{ $registration->type->name }}) sekarang:</p>
    <p style="font-size:18px;font-weight:bold;color:#7A2B25">{{ $registration->status_label }}</p>
    <p>{{ $registration->status_info['public'] }}</p>

    @if ($note)
        <p style="background:#faf6f3;border-left:3px solid #E39B24;padding:10px 14px">{{ $note }}</p>
    @endif

    @if ($registration->status === 'diterima' && $registration->type->accepted_message)
        <p>{{ $registration->type->accepted_message }}</p>
    @endif

    <p><a href="{{ route('registration.status', ['nomor' => $registration->number, 'email' => $registration->email]) }}" style="color:#7A2B25">Lihat detail di halaman Cek Status</a></p>
@endsection
