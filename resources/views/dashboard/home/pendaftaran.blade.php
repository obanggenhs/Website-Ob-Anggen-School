@extends('layouts.dashboard')

@section('title', 'Beranda Dashboard')

@section('content')
    <div class="dash-hero">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center position-relative" style="z-index:1">
            <div>
                <h1>Meja Pendaftaran</h1>
                <p>Pantau pendaftar dari berkas masuk sampai pengumuman. Klik setiap tahap untuk langsung memproses.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.applicants.index', ['status' => 'baru']) }}" class="btn btn-light"><i class="bi bi-inbox"></i> Proses pendaftar baru</a>
                <a href="{{ route('dashboard.news.create', ['kanal' => 'pendaftaran']) }}" class="btn btn-outline-light"><i class="bi bi-megaphone"></i> Buat pengumuman</a>
            </div>
        </div>
    </div>

    <div class="pipeline mb-4">
        @foreach ($pipeline as $p)
            <a href="{{ route('dashboard.applicants.index', ['status' => $p['key']]) }}" class="pipeline__item" style="border-top-color: var(--bs-{{ $p['color'] }})">
                <small><i class="bi {{ $p['icon'] }}"></i> {{ $p['label'] }}</small>
                <strong>{{ $p['count'] }}</strong>
            </a>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dash-card mb-4">
                <div class="card-header"><h2>Pendaftar masuk 14 hari terakhir</h2></div>
                <div class="card-body">
                    @php $max = max(1, $daily->max('value')); @endphp
                    <div class="mini-chart">
                        @foreach ($daily as $d)
                            <div class="mini-chart__bar" title="{{ $d['label'] }}: {{ $d['value'] }} pendaftar">
                                @if ($d['value'])<b>{{ $d['value'] }}</b>@endif
                                <span style="height: {{ max(3, ($d['value'] / $max) * 100) }}%"></span>
                                <small>{{ $d['label'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Pendaftar terbaru</h2>
                    <a href="{{ route('dashboard.applicants.index') }}" class="small">Semua pendaftar <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <thead><tr><th>Nomor</th><th>Nama</th><th>Jalur</th><th>Masuk</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse ($latest as $r)
                                <tr>
                                    <td class="small fw-bold">{{ $r->number }}</td>
                                    <td><a href="{{ route('dashboard.applicants.show', $r) }}" class="fw-bold text-body">{{ $r->full_name }}</a></td>
                                    <td class="small">{{ $r->type->name }}</td>
                                    <td class="small text-muted-d">{{ $r->created_at->diffForHumans() }}</td>
                                    <td><span class="badge text-bg-{{ $r->status_info['color'] }}">{{ $r->status_label }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">@include('dashboard.partials.empty', ['text' => 'Belum ada pendaftar.'])</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Jalur pendaftaran</h2>
                    <a href="{{ route('dashboard.registration-types.index') }}" class="small">Atur <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        @foreach ($types as $t)
                            <li>
                                <span class="min-w-0 flex-fill">
                                    <a href="{{ route('dashboard.applicants.index', ['jalur' => $t->id]) }}" class="dash-list__title d-block text-truncate">{{ $t->name }}</a>
                                    <span class="dash-list__meta">{{ $t->registrations_count }} pendaftar · {{ $t->new_count }} baru · {{ $t->accepted_count }} diterima{{ $t->quota ? ' / kuota '.$t->quota : '' }}</span>
                                </span>
                                <form method="POST" action="{{ route('dashboard.registration-types.toggle', $t) }}">
                                    @csrf @method('PATCH')
                                    <button class="badge border-0 {{ $t->is_open ? 'text-bg-success' : 'text-bg-secondary' }}" title="Klik untuk {{ $t->is_open ? 'menutup' : 'membuka' }}">{{ $t->is_open ? 'Dibuka' : 'Ditutup' }}</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2><i class="bi bi-calendar-event me-1"></i> Jadwal tes / wawancara</h2></div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        @forelse ($schedules as $s)
                            <li>
                                <span class="badge text-bg-primary">{{ $s->schedule_at->format('d/m') }}</span>
                                <span class="min-w-0">
                                    <a href="{{ route('dashboard.applicants.show', $s) }}" class="dash-list__title d-block text-truncate">{{ $s->full_name }}</a>
                                    <span class="dash-list__meta">{{ $s->schedule_at->format('H.i') }} WIT · {{ $s->schedule_place }}</span>
                                </span>
                            </li>
                        @empty
                            <li class="small text-muted-d">Belum ada jadwal.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            @if ($assigned->isNotEmpty())
                <div class="card dash-card" style="border-top: 4px solid var(--dash-primary)">
                    <div class="card-header"><h2><i class="bi bi-person-check me-1"></i> Tugas dari Master Admin</h2></div>
                    <div class="card-body py-2">
                        <ul class="dash-list">
                            @foreach ($assigned as $m)
                                <li>
                                    <i class="bi {{ $m->type_icon }} text-primary"></i>
                                    <span class="min-w-0 flex-fill">
                                        <a href="{{ route('dashboard.studio.show', $m) }}" class="dash-list__title d-block">{{ $m->title }}</a>
                                        <span class="dash-list__meta">{{ $m->type_label }}</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
