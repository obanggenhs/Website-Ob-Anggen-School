@extends('layouts.dashboard')

@section('title', 'Kelulusan')
@section('page_title', 'Kelulusan (Graduation)')
@section('page_subtitle', 'Data lulusan yang tampil di halaman Graduations.')
@section('actions')
    <a href="{{ route('dashboard.graduates.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah lulusan</a>
@endsection

@section('content')
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua tahun</option>
                    @foreach ($years as $y)<option value="{{ $y }}" @selected(request('tahun') == $y)>{{ $y }}</option>@endforeach
                </select>
                <select name="jenjang" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua jenjang</option>
                    @foreach (\App\Models\Graduate::LEVELS as $k => $v)<option value="{{ $k }}" @selected(request('jenjang') === $k)>{{ $v }}</option>@endforeach
                </select>
                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari nama...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th colspan="2">Nama</th><th>Jenjang</th><th>Tahun</th><th>Melanjutkan ke</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($graduates as $g)
                        <tr>
                            <td style="width:52px"><img src="{{ $g->photo_url }}" class="dash-thumb dash-thumb--round" alt=""></td>
                            <td><strong>{{ $g->name }}</strong><div class="small text-muted-d">{{ $g->class_name }} {{ $g->predicate ? '· '.$g->predicate : '' }}</div></td>
                            <td>{{ $g->level_label }}</td>
                            <td>{{ $g->graduation_year }}</td>
                            <td class="small">{{ $g->continue_to ?: '-' }}</td>
                            <td>@include('dashboard.partials.status', ['active' => $g->is_active])</td>
                            <td><div class="dash-actions">
                                <a href="{{ route('dashboard.graduates.edit', $g) }}" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                @include('dashboard.partials.delete', ['url' => route('dashboard.graduates.destroy', $g), 'label' => $g->name])
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">@include('dashboard.partials.empty', ['icon' => 'bi-mortarboard', 'text' => 'Belum ada data lulusan.'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($graduates->hasPages())<div class="card-footer">{{ $graduates->links() }}</div>@endif
    </div>
@endsection
