@extends('layouts.dashboard')

@section('title', 'Data Pendaftar')
@section('page_title', 'Data Pendaftar')
@section('page_subtitle', 'Proses setiap pendaftar: verifikasi berkas → jadwal tes/wawancara → keputusan.')
@section('actions')
    <a href="{{ route('dashboard.whatsapp.edit') }}" class="btn btn-light"><i class="bi bi-whatsapp text-success"></i> Koneksi WhatsApp</a>
    <a href="{{ route('dashboard.applicants.export', request()->query()) }}" class="btn btn-light"><i class="bi bi-filetype-csv"></i> Unduh Excel (CSV)</a>
@endsection

@section('content')
    @php $total = $counts->sum(); @endphp
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('dashboard.applicants.index', request()->except(['status', 'page'])) }}" class="dash-chip {{ ! request('status') ? 'active' : '' }}">Semua <span class="opacity-75">{{ $total }}</span></a>
        @foreach (\App\Models\Registration::STATUSES as $key => $s)
            <a href="{{ route('dashboard.applicants.index', array_merge(request()->except('page'), ['status' => $key])) }}" class="dash-chip {{ request('status') === $key ? 'active' : '' }}">
                <i class="bi {{ $s['icon'] }}"></i> {{ $s['label'] }} <span class="opacity-75">{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                <select name="jalur" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua jalur</option>
                    @foreach ($types as $t)<option value="{{ $t->id }}" @selected(request('jalur') == $t->id)>{{ $t->name }}</option>@endforeach
                </select>
                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Nama, nomor, atau email...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i> Cari</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Nomor</th><th>Nama pendaftar</th><th>Jalur</th><th>Tanggal</th><th>Jadwal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($registrations as $r)
                        <tr>
                            <td class="fw-bold small text-nowrap">{{ $r->number }}</td>
                            <td><a href="{{ route('dashboard.applicants.show', $r) }}" class="fw-bold text-body">{{ $r->full_name }}</a><div class="small text-muted-d">{{ $r->phone }} · {{ $r->email }}</div></td>
                            <td class="small">{{ $r->type->name }}</td>
                            <td class="small text-nowrap">{{ tgl($r->created_at, 'd M Y') }}<div class="text-muted-d">{{ $r->created_at->diffForHumans() }}</div></td>
                            <td class="small text-nowrap">{{ $r->schedule_at ? tgl($r->schedule_at, 'd M, H.i') : '-' }}</td>
                            <td><span class="badge text-bg-{{ $r->status_info['color'] }}">{{ $r->status_label }}</span></td>
                            <td><div class="dash-actions">
                                <a href="{{ wa_link($r->phone, 'Halo '.$r->full_name.', kami dari panitia pendaftaran '.setting('site_name').' terkait pendaftaran '.$r->number.'.') }}" target="_blank" class="btn btn-sm btn-light btn-icon" title="WhatsApp"><i class="bi bi-whatsapp text-success"></i></a>
                                <a href="{{ route('dashboard.applicants.show', $r) }}" class="btn btn-sm btn-primary"><i class="bi bi-arrow-right-circle"></i> Proses</a>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">@include('dashboard.partials.empty', ['icon' => 'bi-person-lines-fill', 'text' => 'Tidak ada pendaftar untuk filter ini.'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($registrations->hasPages())<div class="card-footer">{{ $registrations->links() }}</div>@endif
    </div>
@endsection
