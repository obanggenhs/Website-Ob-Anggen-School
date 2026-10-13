@extends('layouts.dashboard')

@section('title', 'Fasilitas')
@section('page_title', 'Fasilitas')
@section('page_subtitle', 'Gedung sekolah dan asrama di setiap kampus.')
@section('actions')
    <a href="{{ route('dashboard.facilities.create', array_filter(['jenis' => request('jenis'), 'kampus' => request('kampus')])) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah fasilitas</a>
@endsection

@section('content')
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="jenis" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Gedung & asrama</option>
                    @foreach (\App\Models\Facility::TYPES as $k => $v)<option value="{{ $k }}" @selected(request('jenis') === $k)>{{ $v }}</option>@endforeach
                </select>
                <select name="kampus" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kampus</option>
                    @foreach ($branches as $b)<option value="{{ $b->id }}" @selected(request('kampus') == $b->id)>{{ $b->name }}</option>@endforeach
                </select>
                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th colspan="2">Fasilitas</th><th>Jenis</th><th>Kampus</th><th>Jenjang</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($facilities as $f)
                        <tr>
                            <td style="width:64px"><img src="{{ $f->image_url }}" class="dash-thumb" alt=""></td>
                            <td><strong>{{ $f->name }}</strong><div class="small text-muted-d">{{ $f->label }} {{ $f->capacity ? '· '.$f->capacity : '' }}</div></td>
                            <td class="small">{{ \App\Models\Facility::TYPES[$f->type] }}</td>
                            <td class="small">{{ $f->branch?->name ?? '-' }}</td>
                            <td><span class="badge badge-soft">{{ $f->level_label }}</span></td>
                            <td>@include('dashboard.partials.status', ['active' => $f->is_active])</td>
                            <td><div class="dash-actions">
                                <a href="{{ route('facilities.show', $f->slug) }}" target="_blank" class="btn btn-sm btn-light btn-icon"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('dashboard.facilities.edit', $f) }}" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                @include('dashboard.partials.delete', ['url' => route('dashboard.facilities.destroy', $f), 'label' => $f->name])
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">@include('dashboard.partials.empty', ['icon' => 'bi-building', 'text' => 'Belum ada fasilitas.'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($facilities->hasPages())<div class="card-footer">{{ $facilities->links() }}</div>@endif
    </div>
@endsection
