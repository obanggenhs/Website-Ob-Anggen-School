@extends('layouts.dashboard')

@section('title', 'Beranda Dashboard')

@section('content')
    <div class="dash-hero">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center position-relative" style="z-index:1">
            <div>
                <h1>Selamat datang, {{ auth()->user()->name }}</h1>
                <p>Atur struktur menu, isi setiap halaman, dan tentukan bagian mana yang dikelola Admin Publikasi atau Admin Pendaftaran.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('dashboard.studio.index') }}" class="btn btn-light"><i class="bi bi-diagram-3"></i> Buka Studio</a>
                <a href="{{ route('dashboard.users.create') }}" class="btn btn-outline-light"><i class="bi bi-person-plus"></i> Buat akun admin</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($stats as $s)
            <div class="col-6 col-xl-3">
                <a href="{{ $s['url'] }}" class="dash-stat"><i class="bi {{ $s['icon'] }}"></i><span><strong>{{ $s['value'] }}</strong><small>{{ $s['label'] }}</small></span></a>
            </div>
        @endforeach
    </div>

    @if ($hidden || $drafts)
        <div class="alert alert-light border d-flex flex-wrap gap-3 small">
            <i class="bi bi-info-circle text-primary"></i>
            @if ($hidden)<span><strong>{{ $hidden }}</strong> menu sedang disembunyikan dari website.</span>@endif
            @if ($drafts)<span><strong>{{ $drafts }}</strong> berita masih berstatus draf.</span>@endif
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card dash-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Peta website</h2>
                    <a href="{{ route('dashboard.studio.index') }}" class="small">Kelola struktur <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($tree as $root)
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100 {{ $root->is_active ? '' : 'opacity-50' }}">
                                    <a href="{{ route('dashboard.studio.show', $root) }}" class="d-flex align-items-center gap-2 fw-bold text-decoration-none text-body">
                                        <i class="bi {{ $root->type_icon }} text-primary"></i> {{ $root->title }}
                                        @if ($root->is_highlight)<i class="bi bi-star-fill text-warning small" title="Tombol menonjol"></i>@endif
                                        <span class="ms-auto">@foreach ($root->roleBadges() as $r)<span class="role-chip role-chip--{{ $r }}">{{ $r === 'publikasi' ? 'PUB' : 'DAF' }}</span> @endforeach</span>
                                    </a>
                                    @if ($root->children->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1 mt-2">
                                            @foreach ($root->children as $child)
                                                <a href="{{ route('dashboard.studio.show', $child) }}" class="dash-chip {{ $child->is_active ? '' : 'opacity-50' }}"><i class="bi {{ $child->type_icon }}"></i> {{ $child->title }}</a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="small text-muted-d mt-1">{{ $root->type_label }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="tree-legend mt-3">
                        <span><span class="role-chip role-chip--publikasi">PUB</span> juga dikelola Admin Publikasi</span>
                        <span><span class="role-chip role-chip--pendaftaran">DAF</span> juga dikelola Admin Pendaftaran</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Akun admin</h2>
                    <a href="{{ route('dashboard.users.index') }}" class="small">Kelola <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        @forelse ($admins as $admin)
                            <li>
                                <span class="dash-avatar" style="background: {{ \App\Support\DashboardMenu::theme($admin->role)['color'] }}">{{ $admin->initials }}</span>
                                <span class="min-w-0 flex-fill">
                                    <span class="dash-list__title d-block">{{ $admin->name }}</span>
                                    <span class="dash-list__meta">{{ $admin->role_label }} · {{ $admin->last_login_at ? 'aktif '.$admin->last_login_at->diffForHumans() : 'belum pernah masuk' }}</span>
                                </span>
                                @unless ($admin->is_active)<span class="badge text-bg-secondary">Nonaktif</span>@endunless
                            </li>
                        @empty
                            <li class="text-muted-d small">Belum ada akun Admin Publikasi / Pendaftaran.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Pendaftar terbaru</h2>
                    <a href="{{ route('dashboard.applicants.index') }}" class="small">Semua <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        @forelse ($applicants as $a)
                            <li>
                                <span class="min-w-0 flex-fill">
                                    <a href="{{ route('dashboard.applicants.show', $a) }}" class="dash-list__title d-block text-truncate">{{ $a->full_name }}</a>
                                    <span class="dash-list__meta">{{ $a->number }} · {{ $a->type->name }}</span>
                                </span>
                                <span class="badge text-bg-{{ $a->status_info['color'] }}">{{ $a->status_label }}</span>
                            </li>
                        @empty
                            <li class="text-muted-d small">Belum ada pendaftar.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Aktivitas terakhir semua admin</h2>
                    <a href="{{ route('dashboard.activity.index') }}" class="small">Log lengkap <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <tbody>
                            @forelse ($activities as $log)
                                <tr>
                                    <td style="width:170px" class="text-muted-d small">{{ $log->created_at->diffForHumans() }}</td>
                                    <td><strong>{{ $log->user?->name ?? 'Sistem' }}</strong> @if ($log->user)<span class="role-chip role-chip--{{ $log->user->role }}">{{ $log->user->role_label }}</span>@endif</td>
                                    <td>{{ $log->action }} <span class="text-muted-d">{{ $log->subject }}</span></td>
                                </tr>
                            @empty
                                <tr><td class="text-center text-muted-d py-4">Belum ada aktivitas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
