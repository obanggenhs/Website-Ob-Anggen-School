@extends('layouts.dashboard')

@section('title', 'Guru & Staf')
@section('page_title', 'Guru & Staf')
@section('actions')
    <a href="{{ route('dashboard.staff.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
@endsection

@section('content')
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kelompok</option>
                    @foreach (\App\Models\Staff::CATEGORIES as $k => $v)<option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $v }}</option>@endforeach
                </select>
                <select name="kampus" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kampus</option>
                    @foreach ($branches as $b)<option value="{{ $b->id }}" @selected(request('kampus') == $b->id)>{{ $b->name }}</option>@endforeach
                </select>
                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari nama...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>#</th><th colspan="2">Nama</th><th>Kelompok</th><th>Kampus</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($staff as $p)
                        <tr>
                            <td class="text-muted-d small">{{ $p->position }}</td>
                            <td style="width:52px"><img src="{{ $p->photo_url }}" class="dash-thumb dash-thumb--round" alt=""></td>
                            <td><strong>{{ $p->name }}</strong> @if ($p->is_featured)<i class="bi bi-star-fill text-warning small" title="Ditampilkan sebagai pimpinan"></i>@endif<div class="small text-muted-d">{{ $p->position_title }}</div></td>
                            <td class="small">{{ $p->category_label }}</td>
                            <td class="small">{{ $p->branch?->name ?? '-' }}</td>
                            <td>@include('dashboard.partials.status', ['active' => $p->is_active])</td>
                            <td><div class="dash-actions">
                                <a href="{{ route('dashboard.staff.edit', $p) }}" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                @include('dashboard.partials.delete', ['url' => route('dashboard.staff.destroy', $p), 'label' => $p->name])
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">@include('dashboard.partials.empty', ['icon' => 'bi-people', 'text' => 'Belum ada data guru & staf.'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($staff->hasPages())<div class="card-footer">{{ $staff->links() }}</div>@endif
    </div>
@endsection
