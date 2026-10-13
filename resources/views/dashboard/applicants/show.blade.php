@extends('layouts.dashboard')

@php
    $r = $registration;
    $info = $r->status_info;
    $sections = $r->type->fields->groupBy('section');
@endphp

@section('title', $r->full_name)
@section('page_title', $r->full_name)
@section('page_subtitle', $r->number.' · '.$r->type->name.' · daftar '.tgl($r->created_at, 'd F Y, H.i'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.applicants.index') }}">Data Pendaftar</a></li>
    <li class="breadcrumb-item active">{{ $r->number }}</li>
@endsection
@section('actions')
    <a href="{{ route('dashboard.applicants.print', $r) }}" target="_blank" class="btn btn-light"><i class="bi bi-printer"></i> Cetak</a>
    <a href="{{ wa_link($r->phone, 'Halo '.$r->full_name.', kami dari panitia pendaftaran '.setting('site_name').'. Status pendaftaran '.$r->number.': '.$r->status_label.'. Cek detail di '.route('registration.status', ['nomor' => $r->number, 'email' => $r->email])) }}" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim WhatsApp</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Data pendaftar</h2>
                    <span class="badge text-bg-{{ $info['color'] }} fs-7"><i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}</span>
                </div>
                <div class="card-body">
                    <dl class="detail-list">
                        <dt>Nama lengkap</dt><dd class="fw-bold">{{ $r->full_name }}</dd>
                        <dt>Email</dt><dd><a href="mailto:{{ $r->email }}">{{ $r->email }}</a></dd>
                        <dt>No. HP / WA</dt><dd><a href="{{ wa_link($r->phone) }}" target="_blank">{{ $r->phone }}</a></dd>
                    </dl>
                    @foreach (\App\Models\RegistrationField::SECTIONS as $key => $label)
                        @if ($key !== 'dokumen' && $sections->has($key))
                            <h3 class="h6 mt-4 mb-2 text-muted-d">{{ $label }}</h3>
                            <dl class="detail-list">
                                @foreach ($sections[$key] as $field)
                                    <dt>{{ $field->label }}</dt>
                                    <dd>{{ ($r->data[$field->name] ?? null) ?: '-' }}</dd>
                                @endforeach
                            </dl>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2>Dokumen</h2></div>
                <div class="card-body">
                    <div class="row g-2">
                        @forelse ($sections['dokumen'] ?? [] as $field)
                            @php $file = $r->files[$field->name] ?? null; @endphp
                            <div class="col-md-6">
                                @if ($file)
                                    <a href="{{ route('dashboard.applicants.file', [$r, $field->name]) }}" target="_blank" class="file-tile">
                                        <i class="bi {{ str_ends_with(strtolower($file['name'] ?? ''), '.pdf') ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image' }}"></i>
                                        <span class="min-w-0"><strong class="d-block small">{{ $field->label }}</strong><small class="text-muted-d text-truncate d-block">{{ $file['name'] ?? '' }} · {{ number_format(($file['size'] ?? 0) / 1024, 0) }} KB</small></span>
                                    </a>
                                @else
                                    <div class="file-tile opacity-50"><i class="bi bi-file-earmark-x"></i><span><strong class="d-block small">{{ $field->label }}</strong><small>Tidak diunggah</small></span></div>
                                @endif
                            </div>
                        @empty
                            <div class="col-12 small text-muted-d">Jalur ini tidak meminta dokumen.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2>Riwayat proses</h2></div>
                <div class="card-body">
                    <ul class="timeline">
                        @foreach ($r->logs as $log)
                            <li class="{{ $log->is_public ? '' : 'is-private' }}">
                                <strong>{{ $log->title }}</strong>
                                @unless ($log->is_public)<span class="badge text-bg-light border">internal</span>@endunless
                                @if ($log->note)<div>{{ $log->note }}</div>@endif
                                <small class="text-muted-d">{{ tgl($log->created_at, 'd M Y, H.i') }} · {{ $log->user?->name ?? 'Pendaftar / sistem' }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card dash-card mb-4" style="border-top: 4px solid var(--dash-primary)">
                <div class="card-header"><h2><span class="badge rounded-pill text-bg-dark me-1">1</span> Ubah status</h2></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.applicants.status', $r) }}">
                        @csrf
                        <div class="d-grid gap-2 mb-3" style="grid-template-columns: 1fr 1fr">
                            @foreach (\App\Models\Registration::STATUSES as $key => $s)
                                <input type="radio" class="btn-check" name="status" id="st_{{ $key }}" value="{{ $key }}" @checked($r->status === $key)>
                                <label class="btn btn-outline-{{ $s['color'] }} btn-sm text-start" for="st_{{ $key }}"><i class="bi {{ $s['icon'] }}"></i> {{ $s['label'] }}</label>
                            @endforeach
                        </div>
                        <label class="form-label" for="public_note">Pesan untuk pendaftar</label>
                        <textarea name="public_note" id="public_note" rows="3" class="form-control mb-2" placeholder="mis. Berkas lengkap, silakan tunggu jadwal wawancara.">{{ $r->public_note }}</textarea>
                        @include('dashboard.applicants._channels', ['n' => 1, 'r' => $r, 'mailText' => 'Kirim email pemberitahuan ke', 'waText' => 'Kirim WhatsApp pemberitahuan ke'])
                        <button class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Simpan status</button>
                    </form>
                </div>
            </div>

            @include('dashboard.applicants._notifications', ['r' => $r])

            <div class="card dash-card mb-4">
                <div class="card-header"><h2><span class="badge rounded-pill text-bg-dark me-1">2</span> Jadwal tes / wawancara</h2></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.applicants.schedule', $r) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label" for="schedule_at">Tanggal & jam (WIT)</label>
                                <input type="datetime-local" name="schedule_at" id="schedule_at" value="{{ $r->schedule_at?->format('Y-m-d\TH:i') }}" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="schedule_place">Tempat</label>
                                <input type="text" name="schedule_place" id="schedule_place" value="{{ $r->schedule_place }}" class="form-control" placeholder="Ruang guru kampus Bokondini" required>
                            </div>
                        </div>
                        <div class="my-2">
                            @include('dashboard.applicants._channels', ['n' => 2, 'r' => $r, 'mailText' => 'Kirim email jadwal ke', 'waText' => 'Kirim WhatsApp jadwal ke'])
                        </div>
                        <button class="btn btn-soft w-100"><i class="bi bi-calendar-check me-1"></i> Simpan jadwal</button>
                    </form>
                </div>
            </div>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2><span class="badge rounded-pill text-bg-dark me-1">3</span> Catatan internal</h2></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.applicants.note', $r) }}">
                        @csrf
                        <textarea name="note" rows="3" class="form-control mb-2" placeholder="Hanya terlihat oleh admin." required></textarea>
                        <button class="btn btn-light w-100"><i class="bi bi-journal-plus me-1"></i> Tambah catatan</button>
                    </form>
                    @if ($r->internal_note)<div class="small text-muted-d mt-2">Catatan terakhir: {{ $r->internal_note }}</div>@endif
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <small class="text-muted-d">Ditangani: {{ $r->handler?->name ?? 'belum ada' }}</small>
                    @include('dashboard.partials.delete', ['url' => route('dashboard.applicants.destroy', $r), 'class' => 'btn-outline-danger', 'text' => 'Hapus data', 'confirm' => 'Hapus data pendaftar '.$r->full_name.' beserta dokumennya?'])
                </div>
            </div>
        </div>
    </div>
@endsection
