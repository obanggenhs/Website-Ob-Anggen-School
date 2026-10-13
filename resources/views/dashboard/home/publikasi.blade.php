@extends('layouts.dashboard')

@section('title', 'Beranda Dashboard')

@section('content')
    <div class="dash-hero">
        <div class="position-relative" style="z-index:1">
            <h1>Halo, {{ auth()->user()->name }}</h1>
            <p>Apa yang ingin dipublikasikan hari ini? Tulis berita, unggah album kegiatan, atau perbarui agenda sekolah.</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('dashboard.news.create') }}" class="btn btn-light"><i class="bi bi-pencil-square"></i> Tulis berita</a>
                <a href="{{ route('dashboard.gallery.create') }}" class="btn btn-outline-light"><i class="bi bi-images"></i> Buat album</a>
                <a href="{{ route('dashboard.calendar.create') }}" class="btn btn-outline-light"><i class="bi bi-calendar-plus"></i> Tambah agenda</a>
                <a href="{{ route('dashboard.graduates.create') }}" class="btn btn-outline-light"><i class="bi bi-mortarboard"></i> Tambah lulusan</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($stats as $s)
            <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi {{ $s['icon'] }}"></i><span><strong>{{ $s['value'] }}</strong><small>{{ $s['label'] }}</small></span></div></div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Berita terakhir</h2>
                    <a href="{{ route('dashboard.news.index') }}" class="small">Semua berita <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <tbody>
                            @forelse ($latestNews as $n)
                                <tr>
                                    <td style="width:64px"><img src="{{ $n->cover_url }}" class="dash-thumb" alt=""></td>
                                    <td class="min-w-0">
                                        <a href="{{ route('dashboard.news.edit', $n) }}" class="fw-bold text-body text-decoration-none">{{ \Illuminate\Support\Str::limit($n->title, 70) }}</a>
                                        <div class="small text-muted-d">{{ $n->category?->name ?? 'Tanpa kategori' }} · {{ tgl($n->published_at ?? $n->created_at, 'd M Y') }}</div>
                                    </td>
                                    <td class="text-end"><span class="badge {{ $n->status === 'published' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $n->status === 'published' ? 'Terbit' : 'Draf' }}</span></td>
                                </tr>
                            @empty
                                <tr><td>@include('dashboard.partials.empty', ['text' => 'Belum ada berita.', 'icon' => 'bi-newspaper'])</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Album terbaru</h2>
                    <a href="{{ route('dashboard.gallery.index') }}" class="small">Semua album <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse ($albums as $album)
                            <div class="col-6 col-md-3">
                                <a href="{{ route('dashboard.gallery.edit', $album) }}" class="text-decoration-none text-body">
                                    <img src="{{ $album->cover_url }}" alt="" class="w-100 rounded-3 mb-2" style="aspect-ratio:4/3;object-fit:cover">
                                    <div class="small fw-bold text-truncate">{{ $album->title }}</div>
                                    <div class="small text-muted-d">{{ $album->photos_count }} foto</div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">@include('dashboard.partials.empty', ['text' => 'Belum ada album.', 'icon' => 'bi-images'])</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            @if ($assigned->isNotEmpty())
                <div class="card dash-card mb-4" style="border-top: 4px solid var(--dash-primary)">
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
                                    <a href="{{ $m->link }}" target="_blank" class="btn btn-sm btn-light btn-icon" title="Lihat di website"><i class="bi bi-box-arrow-up-right"></i></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Agenda terdekat</h2>
                    <a href="{{ route('dashboard.calendar.index') }}" class="small">Kalender <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        @forelse ($agenda as $e)
                            <li>
                                <span class="badge" style="background: {{ $e->category_color }}">{{ $e->start_date->format('d/m') }}</span>
                                <span class="min-w-0"><span class="dash-list__title d-block text-truncate">{{ $e->title }}</span><span class="dash-list__meta">{{ $e->level_label }} · {{ $e->relative_label }}</span></span>
                            </li>
                        @empty
                            <li class="small text-muted-d">Tidak ada agenda mendatang.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2>Berita paling banyak dibaca</h2></div>
                <div class="card-body py-2">
                    <ol class="mb-0 ps-3 small">
                        @foreach ($popular as $p)
                            <li class="py-1"><a href="{{ route('news.show', $p->slug) }}" target="_blank" class="text-body">{{ \Illuminate\Support\Str::limit($p->title, 55) }}</a> <span class="text-muted-d">· {{ $p->views }}x</span></li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
