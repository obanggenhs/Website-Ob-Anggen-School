@extends('layouts.dashboard')

@section('title', 'Kalender Akademik')
@section('page_title', 'Kalender Akademik')
@section('page_subtitle', 'Agenda untuk SD, SMP, SMA, atau semua jenjang.')
@section('actions')
    <a href="{{ route('dashboard.calendar.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah agenda</a>
@endsection

@section('content')
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="waktu" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="mendatang" @selected(request('waktu', 'mendatang') === 'mendatang')>Mendatang & berlangsung</option>
                    <option value="semua" @selected(request('waktu') === 'semua')>Semua agenda</option>
                </select>
                <select name="jenjang" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua jenjang</option>
                    @foreach (\App\Models\CalendarEvent::LEVELS as $k => $v)<option value="{{ $k }}" @selected(request('jenjang') === $k)>{{ $v }}</option>@endforeach
                </select>
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    @foreach (\App\Models\CalendarEvent::CATEGORIES as $k => $v)<option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $v['label'] }}</option>@endforeach
                </select>
                @if ($years->isNotEmpty())
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua tahun ajaran</option>
                        @foreach ($years as $y)<option value="{{ $y }}" @selected(request('tahun') === $y)>{{ $y }}</option>@endforeach
                    </select>
                @endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Tanggal</th><th>Agenda</th><th>Kategori</th><th>Jenjang</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($events as $e)
                        <tr>
                            <td class="small text-nowrap"><strong>{{ $e->date_label }}</strong><div class="text-muted-d">{{ $e->relative_label }}</div></td>
                            <td><strong>{{ $e->title }}</strong><div class="small text-muted-d">{{ $e->time_info }} {{ $e->location ? '· '.$e->location : '' }}</div></td>
                            <td><span class="badge" style="background: {{ $e->category_color }}">{{ $e->category_label }}</span></td>
                            <td>{{ $e->level_label }}</td>
                            <td>@include('dashboard.partials.status', ['active' => $e->is_active])</td>
                            <td><div class="dash-actions">
                                <a href="{{ route('dashboard.calendar.edit', $e) }}" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                @include('dashboard.partials.delete', ['url' => route('dashboard.calendar.destroy', $e), 'label' => 'agenda ini'])
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">@include('dashboard.partials.empty', ['icon' => 'bi-calendar3', 'text' => 'Tidak ada agenda.'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($events->hasPages())<div class="card-footer">{{ $events->links() }}</div>@endif
    </div>
@endsection
