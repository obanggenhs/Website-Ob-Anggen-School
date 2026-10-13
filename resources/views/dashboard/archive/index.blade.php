@extends('layouts.dashboard')

@section('title', 'Data Menu Baru')
@section('page_title', 'Data Menu Baru')
@section('page_subtitle', 'Isi Menu Utama, Submenu, dan halaman yang dibuat admin (bukan menu bawaan). Tersimpan di database sekolah dan diperbarui otomatis setiap kali disimpan.')
@section('actions')
    <a href="{{ route('dashboard.archive.export', request()->only('menu', 'cari')) }}" class="btn btn-soft"><i class="bi bi-file-earmark-spreadsheet"></i> Unduh Excel (CSV)</a>
    @if (auth()->user()->isMaster())
        <form method="POST" action="{{ route('dashboard.archive.rebuild') }}" class="d-inline">
            @csrf
            <button class="btn btn-light" title="Bangun ulang data dari isi website terbaru"><i class="bi bi-arrow-repeat"></i> Perbarui data</button>
        </form>
    @endif
@endsection

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-diagram-3"></i><span><strong>{{ $stats['menus'] }}</strong><small>Menu & submenu buatan admin</small></span></div></div>
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-layout-text-window"></i><span><strong>{{ $stats['items'] }}</strong><small>Isi halaman tersimpan</small></span></div></div>
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-paperclip"></i><span><strong>{{ $stats['files'] }}</strong><small>File (foto, video, dokumen)</small></span></div></div>
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-database-check"></i><span><strong>{{ $stats['synced'] ? \Illuminate\Support\Carbon::parse($stats['synced'])->diffForHumans() : '-' }}</strong><small>Terakhir diperbarui</small></span></div></div>
    </div>

    <div class="alert alert-light border small d-flex gap-2 align-items-start">
        <i class="bi bi-database text-primary"></i>
        <div>
            Di database <strong>{{ config('database.connections.'.config('database.default').'.database') }}</strong>, data ini ada di tabel
            <code>menu_contents</code> dengan kolom <code>main_menu</code> (Menu Utama), <code>submenu</code>, <code>page_title</code> (halaman),
            <code>content_type</code>, <code>content_title</code>, <code>content_text</code> (isi), dan <code>file_names</code>.
            Menu bawaan tidak ikut dicatat.
        </div>
    </div>

    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="menu" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Pilih menu">
                    <option value="">Semua menu buatan admin</option>
                    @foreach ($menus as $m)
                        <option value="{{ $m->id }}" @selected($current?->id === $m->id)>{{ $m->parent ? $m->parent->title.' › '.$m->title : $m->title }}</option>
                    @endforeach
                </select>
                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari menu, judul, isi, nama file...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
                @if (request()->filled('cari') || $current)
                    <a href="{{ route('dashboard.archive.index') }}" class="btn btn-sm btn-light" title="Hapus filter"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table dash-table archive-table">
                <thead>
                    <tr><th style="width:56px">Urutan</th><th>Jenis isi</th><th>Judul &amp; isi</th><th>File</th><th>Status</th><th>Diubah</th></tr>
                </thead>
                <tbody>
                    @php $group = null; @endphp
                    @forelse ($rows as $row)
                        @if ($group !== $row->menu_id)
                            @php $group = $row->menu_id; @endphp
                            <tr class="archive-group">
                                <td colspan="6">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div class="min-w-0">
                                            <span class="archive-path">
                                                <i class="bi bi-folder2-open"></i> {{ $row->main_menu }}
                                                @if ($row->submenu)<i class="bi bi-chevron-right small"></i> {{ $row->submenu }}@endif
                                            </span>
                                            <span class="badge badge-soft ms-1">{{ $row->page_type }}</span>
                                            @if ($row->created_by)<small class="text-muted-d ms-1">dibuat {{ $row->created_by }}</small>@endif
                                        </div>
                                        <div class="d-flex gap-1">
                                            @if ($row->page_url)
                                                <a href="{{ $row->page_url }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat</a>
                                            @endif
                                            @if ($row->menu)
                                                <a href="{{ route('dashboard.studio.show', $row->menu_id) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Kelola</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        <tr class="{{ $row->is_active ? '' : 'is-hidden' }}">
                            <td class="text-muted-d">{{ $row->position ?: '–' }}</td>
                            <td class="text-nowrap"><span class="badge text-bg-light border">{{ $row->content_type }}</span></td>
                            <td class="archive-text">
                                @if ($row->content_title)<strong class="d-block">{{ $row->content_title }}</strong>@endif
                                @if ($row->content_text)<span class="text-muted-d">{{ \Illuminate\Support\Str::limit($row->content_text, 180) }}</span>@endif
                                @if ($row->link)<div class="small text-truncate"><i class="bi bi-link-45deg"></i> {{ $row->link }}</div>@endif
                                @if (! $row->content_title && ! $row->content_text && ! $row->link)<span class="text-muted-d">—</span>@endif
                            </td>
                            <td class="small">
                                @if ($row->files)
                                    <span class="fw-semibold">{{ count($row->files) }} file</span>
                                    <div class="text-muted-d archive-files" title="{{ $row->file_names }}">{{ \Illuminate\Support\Str::limit($row->file_names, 70) }}</div>
                                @else
                                    <span class="text-muted-d">—</span>
                                @endif
                            </td>
                            <td>@include('dashboard.partials.status', ['active' => $row->is_active])</td>
                            <td class="small text-nowrap">{{ $row->updated_at ? tgl($row->updated_at, 'd M Y, H.i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                @include('dashboard.partials.empty', [
                                    'icon' => 'bi-database',
                                    'text' => request()->filled('cari')
                                        ? 'Tidak ada isi yang cocok dengan pencarian.'
                                        : 'Belum ada menu buatan admin. Buat Menu Utama atau Submenu baru di Struktur & Konten; isinya otomatis tercatat di sini.',
                                    'action' => auth()->user()->isMaster() && ! request()->filled('cari') ? route('dashboard.studio.index') : null,
                                    'actionLabel' => 'Buka Struktur & Konten',
                                ])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($rows->hasPages())<div class="card-footer">{{ $rows->links() }}</div>@endif
    </div>
@endsection