@extends('layouts.dashboard')

@section('title', 'Akun Admin')
@section('page_title', 'Akun Admin')
@section('page_subtitle', 'Buat dan atur akun untuk dashboard Publikasi dan dashboard Pendaftaran.')
@section('actions')
    <a href="{{ route('dashboard.users.create', ['role' => 'publikasi']) }}" class="btn btn-outline-primary"><i class="bi bi-broadcast"></i> Akun Publikasi</a>
    <a href="{{ route('dashboard.users.create', ['role' => 'pendaftaran']) }}" class="btn btn-primary"><i class="bi bi-clipboard-check"></i> Akun Pendaftaran</a>
@endsection

@section('content')
    <div class="row g-3 mb-4">
        @foreach ($access as $role => $a)
            @php $theme = \App\Support\DashboardMenu::theme($role); @endphp
            <div class="col-lg-6">
                <div class="card dash-card h-100" style="border-top: 4px solid {{ $theme['color'] }}">
                    <div class="card-body">
                        <h2 class="h6 mb-2"><i class="bi {{ $theme['icon'] }}" style="color: {{ $theme['color'] }}"></i> Yang dikelola {{ $theme['label'] }}</h2>
                        <div class="small mb-2"><strong>Modul bawaan:</strong> {{ $a['core']->join(', ') }}</div>
                        <div class="small">
                            <strong>Menu yang ditugaskan Master ({{ $a['menus']->count() }}):</strong>
                            @forelse ($a['menus'] as $m)
                                <a href="{{ route('dashboard.studio.show', [$m, 'tab' => 'akses']) }}" class="dash-chip mt-1"><i class="bi {{ $m->type_icon }}"></i> {{ $m->title }}</a>
                            @empty
                                <span class="text-muted-d">belum ada</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card dash-card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.users.index') }}" class="dash-chip {{ ! request('role') ? 'active' : '' }}">Semua</a>
                @foreach (\App\Models\User::ROLES as $k => $v)
                    <a href="{{ route('dashboard.users.index', ['role' => $k]) }}" class="dash-chip {{ request('role') === $k ? 'active' : '' }}">{{ $v }}</a>
                @endforeach
            </div>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Nama</th><th>Role</th><th>Terakhir masuk</th><th>Dibuat oleh</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="dash-avatar" style="background: {{ \App\Support\DashboardMenu::theme($u->role)['color'] }}">{{ $u->initials }}</span>
                                    <span><strong>{{ $u->name }}</strong> @if ($u->is(auth()->user()))<span class="badge text-bg-light border">Anda</span>@endif<div class="small text-muted-d">{{ $u->email }}</div></span>
                                </div>
                            </td>
                            <td><span class="role-chip role-chip--{{ $u->role }}">{{ $u->role_label }}</span></td>
                            <td class="small">{{ $u->last_login_at ? $u->last_login_at->diffForHumans() : 'Belum pernah' }}</td>
                            <td class="small">{{ $u->creator?->name ?? '-' }}</td>
                            <td>
                                @if ($u->is_active)<span class="badge text-bg-success">Aktif</span>@else<span class="badge text-bg-secondary">Nonaktif</span>@endif
                            </td>
                            <td><div class="dash-actions">
                                @unless ($u->is(auth()->user()))
                                    <form method="POST" action="{{ route('dashboard.users.toggle', $u) }}">@csrf @method('PATCH')
                                        <button class="btn btn-sm btn-light" title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"><i class="bi {{ $u->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i></button>
                                    </form>
                                @endunless
                                <a href="{{ route('dashboard.users.edit', $u) }}" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                @unless ($u->is(auth()->user()))
                                    @include('dashboard.partials.delete', ['url' => route('dashboard.users.destroy', $u), 'label' => 'akun '.$u->name])
                                @endunless
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
