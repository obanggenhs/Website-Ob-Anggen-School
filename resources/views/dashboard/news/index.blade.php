@extends('layouts.dashboard')

@php
    $isRegistration = $channel === 'pendaftaran';
    $heading = $isRegistration ? 'Berita Pendaftaran' : ($channel === 'umum' ? 'Berita & Pengumuman' : 'Semua Berita');
@endphp

@section('title', $heading)
@section('page_title', $heading)
@section('page_subtitle', $isRegistration ? 'Pengumuman resmi panitia, tampil di halaman Pendaftaran dan daftar berita.' : 'Tulis, terbitkan, dan atur berita sekolah.')
@section('actions')
    <a href="{{ route('dashboard.news.create', array_filter(['kanal' => $channel])) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tulis berita</a>
@endsection

@section('content')
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                @if (count($channels) > 1)
                    <select name="kanal" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua kanal</option>
                        @foreach ($channels as $c)<option value="{{ $c }}" @selected($channel === $c)>{{ \App\Models\News::CHANNELS[$c] }}</option>@endforeach
                    </select>
                @else
                    <input type="hidden" name="kanal" value="{{ $channel }}">
                @endif
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua status ({{ $counts['published'] + $counts['draft'] }})</option>
                    <option value="published" @selected(request('status') === 'published')>Terbit ({{ $counts['published'] }})</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draf ({{ $counts['draft'] }})</option>
                </select>
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)<option value="{{ $cat->id }}" @selected(request('kategori') == $cat->id)>{{ $cat->name }}</option>@endforeach
                </select>
                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari judul...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th colspan="2">Berita</th><th>Kategori</th><th>Status</th><th>Dibaca</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($news as $n)
                        <tr>
                            <td style="width:64px"><img src="{{ $n->cover_url }}" alt="" class="dash-thumb"></td>
                            <td>
                                <a href="{{ route('dashboard.news.edit', $n) }}" class="fw-bold text-body text-decoration-none">{{ \Illuminate\Support\Str::limit($n->title, 80) }}</a>
                                @if ($n->is_featured)<span class="badge text-bg-warning ms-1"><i class="bi bi-star-fill"></i> Penting</span>@endif
                                <div class="small text-muted-d">
                                    {{ tgl($n->published_at ?? $n->created_at, 'd M Y, H.i') }} · {{ $n->author?->name ?? '-' }}
                                    @if (count($channels) > 1) · <span class="role-chip role-chip--{{ $n->channel === 'pendaftaran' ? 'pendaftaran' : 'publikasi' }}">{{ \App\Models\News::CHANNELS[$n->channel] }}</span>@endif
                                </div>
                            </td>
                            <td class="small">{{ $n->category?->name ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('dashboard.news.status', $n) }}">
                                    @csrf @method('PATCH')
                                    <button class="badge border-0 {{ $n->status === 'published' ? 'text-bg-success' : 'text-bg-secondary' }}" title="Klik untuk {{ $n->status === 'published' ? 'menarik jadi draf' : 'menerbitkan' }}">
                                        {{ $n->status === 'published' ? 'Terbit' : 'Draf' }}
                                    </button>
                                </form>
                            </td>
                            <td class="small">{{ number_format($n->views, 0, ',', '.') }}</td>
                            <td>
                                <div class="dash-actions">
                                    <a href="{{ route('news.show', $n->slug) }}" target="_blank" class="btn btn-sm btn-light btn-icon" title="Lihat"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('dashboard.news.edit', $n) }}" class="btn btn-sm btn-soft btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                                    @include('dashboard.partials.delete', ['url' => route('dashboard.news.destroy', $n), 'label' => 'berita ini'])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">@include('dashboard.partials.empty', ['icon' => 'bi-newspaper', 'text' => 'Belum ada berita.', 'action' => route('dashboard.news.create', array_filter(['kanal' => $channel])), 'actionLabel' => 'Tulis berita pertama'])</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($news->hasPages())<div class="card-footer">{{ $news->links() }}</div>@endif
    </div>
@endsection
