@extends('layouts.web-page')

@section('title', $menu?->title ?? 'Cek Status Pendaftaran')
@section('page_title', $menu?->title ?? 'Cek Status Pendaftaran')

@section('page')
    <div class="oa-panel mb-4">
        <form method="GET" action="{{ route('registration.status') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="nomor">Nomor pendaftaran</label>
                <input type="text" name="nomor" id="nomor" value="{{ request('nomor') }}" class="form-control text-uppercase @error('nomor') is-invalid @enderror" placeholder="OA-{{ date('Y') }}-0001" required>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="email">Email / No. HP</label>
                <input type="text" name="email" id="email" value="{{ request('email') }}" class="form-control @error('email') is-invalid @enderror" required>
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-primary"><i class="bi bi-search"></i> Cek status</button>
            </div>
        </form>
    </div>

    @if ($searched && ! $registration)
        <div class="oa-result oa-result--danger"><i class="bi bi-x-circle"></i>
            <div><strong>Data tidak ditemukan.</strong><br>Periksa kembali nomor pendaftaran dan email / nomor HP yang Anda gunakan saat mendaftar.</div>
        </div>
    @elseif ($registration)
        @php
            $step = $registration->pipelineStep();
            $steps = ['Formulir terkirim', 'Verifikasi berkas', 'Tes / wawancara', 'Pengumuman'];
            $info = $registration->status_info;
        @endphp
        <div class="oa-panel">
            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div>
                    <small class="text-muted-oa">{{ $registration->type->name }}</small>
                    <h2 class="h5 mb-0">{{ $registration->full_name }}</h2>
                    <small class="text-muted-oa">No. {{ $registration->number }} · daftar {{ tgl($registration->created_at, 'd F Y') }}</small>
                </div>
                <span class="badge text-bg-{{ $info['color'] }} align-self-start fs-6"><i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}</span>
            </div>

            <div class="oa-stepper">
                @foreach ($steps as $i => $label)
                    <div class="oa-stepper__item {{ $i < $step ? 'is-done' : ($i === $step ? 'is-current' : '') }}">
                        <div class="oa-stepper__dot">@if ($i < $step)<i class="bi bi-check-lg"></i>@else{{ $i + 1 }}@endif</div>
                        {{ $label }}
                    </div>
                @endforeach
            </div>

            <div class="oa-result {{ $registration->status === 'diterima' ? 'oa-result--success' : ($registration->status === 'ditolak' ? 'oa-result--danger' : 'oa-result--info') }} mb-4">
                <i class="bi {{ $info['icon'] }}"></i>
                <div>
                    <strong>{{ $info['label'] }}</strong><br>{{ $info['public'] }}
                    @if ($registration->status === 'diterima' && $registration->type->accepted_message)
                        <p class="mb-0 mt-2">{{ $registration->type->accepted_message }}</p>
                    @endif
                </div>
            </div>

            @if ($registration->schedule_at && ! $registration->isFinal())
                <div class="oa-panel mb-4" style="background: color-mix(in srgb, var(--oa-accent) 12%, #fff); box-shadow:none">
                    <h3 class="h6"><i class="bi bi-calendar-event me-1"></i> Jadwal tes / wawancara</h3>
                    <p class="mb-0"><strong>{{ tgl($registration->schedule_at, 'l, d F Y') }}</strong> pukul {{ $registration->schedule_at->format('H.i') }} WIT<br>{{ $registration->schedule_place }}</p>
                </div>
            @endif

            @if ($registration->public_note)
                <h3 class="h6">Catatan panitia</h3>
                <p class="mb-4">{{ $registration->public_note }}</p>
            @endif

            <h3 class="h6 mb-3">Riwayat proses</h3>
            <ul class="oa-timeline">
                @foreach ($registration->logs as $log)
                    <li>
                        <strong class="d-block small">{{ $log->title }}</strong>
                        @if ($log->note)<span class="small">{{ $log->note }}</span><br>@endif
                        <small>{{ tgl($log->created_at, 'd M Y, H.i') }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="oa-empty"><i class="bi bi-search"></i>Masukkan nomor pendaftaran dan email untuk melihat status, jadwal, dan hasil seleksi.</div>
    @endif
@endsection
