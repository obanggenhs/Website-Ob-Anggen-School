@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')
@section('page_title', 'Log Aktivitas')
@section('page_subtitle', 'Rekam jejak perubahan yang dilakukan semua admin.')

@section('content')
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua role</option>
                    @foreach (\App\Models\User::ROLES as $k => $v)<option value="{{ $k }}" @selected(request('role') === $k)>{{ $v }}</option>@endforeach
                </select>
                <select name="user" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua admin</option>
                    @foreach ($users as $u)<option value="{{ $u->id }}" @selected(request('user') == $u->id)>{{ $u->name }}</option>@endforeach
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Waktu</th><th>Admin</th><th>Aktivitas</th><th>IP</th></tr></thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="small text-nowrap">{{ tgl($log->created_at, 'd M Y, H.i') }}<div class="text-muted-d">{{ $log->created_at->diffForHumans() }}</div></td>
                            <td><strong>{{ $log->user?->name ?? '-' }}</strong> @if ($log->user)<div><span class="role-chip role-chip--{{ $log->user->role }}">{{ $log->user->role_label }}</span></div>@endif</td>
                            <td>{{ ucfirst($log->action) }} <span class="text-muted-d">{{ $log->subject }}</span></td>
                            <td class="small text-muted-d">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">@include('dashboard.partials.empty', ['icon' => 'bi-clock-history', 'text' => 'Belum ada aktivitas.'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())<div class="card-footer">{{ $logs->links() }}</div>@endif
    </div>
@endsection
