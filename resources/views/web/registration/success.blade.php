@extends('layouts.web-full')

@section('title', 'Pendaftaran terkirim')
@section('page_title', 'Pendaftaran berhasil dikirim')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('registration.index') }}">Pendaftaran</a></li>
    <li class="breadcrumb-item active">Berhasil</li>
@endsection
@section('no_intro', true)

@section('page')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="oa-panel text-center">
                <div class="display-5 text-success mb-2"><i class="bi bi-check-circle-fill"></i></div>
                <h2 class="h4">Terima kasih, {{ $registration->full_name }}!</h2>
                <p class="text-muted-oa">{{ setting('reg_success_message') }}</p>

                <div class="oa-ticket my-4">
                    <small class="d-block text-muted-oa mb-1">Nomor pendaftaran Anda</small>
                    <strong>{{ $registration->number }}</strong>
                    <div class="small mt-2">{{ $registration->type->name }} · {{ tgl($registration->created_at, 'd F Y, H.i') }} WIT</div>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-center no-print">
                    <button type="button" class="btn btn-outline-primary" data-copy="{{ $registration->number }}"><i class="bi bi-clipboard"></i> Salin nomor</button>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak bukti</button>
                    <a href="{{ route('registration.status', ['nomor' => $registration->number, 'email' => $registration->email]) }}" class="btn btn-primary"><i class="bi bi-search"></i> Cek status</a>
                </div>
                <p class="small text-muted-oa mt-4 mb-0">Konfirmasi juga dikirim ke <strong>{{ $registration->email }}</strong>.</p>
            </div>
        </div>
    </div>
@endsection
