@extends('layouts.dashboard')

@php
    $blockName = $block->title ?: 'Koleksi Kartu';
    $searching = request()->filled('cari');
    $offset = ($cards->currentPage() - 1) * $cards->perPage();
@endphp

@section('title', 'Kartu · '.$blockName)
@section('page_title', 'Kartu: '.$blockName)
@section('page_subtitle', 'Halaman "'.$menu->title.'" · '.$total.' kartu, '.$active.' tampil di website. Setiap kartu bisa diklik pengunjung untuk membuka detailnya.')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.studio.show', $menu) }}">{{ $menu->title }}</a></li>
    <li class="breadcrumb-item active">{{ $blockName }}</li>
@endsection
@section('actions')
    <a href="{{ route('dashboard.studio.show', $menu) }}#blok-{{ $block->id }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
    <a href="{{ route('dashboard.blocks.edit', [$menu, $block]) }}" class="btn btn-soft"><i class="bi bi-sliders"></i> Tampilan blok</a>
    <a href="{{ route('dashboard.cards.create', [$menu, $block]) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah kartu</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dash-card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h2 class="dash-card__title">Daftar kartu</h2>
                        <small class="text-muted-d">
                            @if ($searching)
                                Hasil pencarian "{{ request('cari') }}". Hapus pencarian untuk mengubah urutan.
                            @else
                                Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan tampil.
                            @endif
                        </small>
                    </div>
                    <form class="dash-filter" method="GET">
                        <input type="search" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari nama, kategori, tahun...">
                        <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
                        @if ($searching)
                            <a href="{{ route('dashboard.cards.index', [$menu, $block]) }}" class="btn btn-sm btn-light" title="Hapus pencarian"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </form>
                </div>
                <div class="card-body">
                    @if ($cards->isEmpty())
                        @include('dashboard.partials.empty', [
                            'icon' => 'bi-grid-3x3-gap',
                            'text' => $searching ? 'Tidak ada kartu yang cocok dengan pencarian.' : 'Belum ada kartu. Tambahkan kartu pertama, mis. data alumni, program, atau prestasi.',
                            'action' => $searching ? null : route('dashboard.cards.create', [$menu, $block]),
                            'actionLabel' => 'Tambah kartu',
                        ])
                    @else
                        <div class="card-list" @unless ($searching) id="blockList" data-reorder-url="{{ route('dashboard.cards.reorder', [$menu, $block]) }}?offset={{ $offset }}" @endunless>
                            @foreach ($cards as $card)
                                <div class="block-item {{ $card->is_active ? '' : 'is-hidden' }}" data-id="{{ $card->id }}">
                                    @unless ($searching)
                                        <span class="tree-handle"><i class="bi bi-grip-vertical"></i></span>
                                    @endunless
                                    @if ($card->image)
                                        <img src="{{ $card->image_url }}" class="dash-thumb flex-shrink-0" alt="">
                                    @else
                                        <span class="block-item__icon"><i class="bi {{ $card->video_url ? 'bi-play-btn' : 'bi-card-text' }}"></i></span>
                                    @endif
                                    <div class="min-w-0 flex-fill">
                                        <div class="block-item__title">
                                            {{ $card->title }}
                                            @if ($card->category)<span class="badge badge-soft ms-1">{{ $card->category }}</span>@endif
                                            @if ($card->year)<span class="badge text-bg-light border ms-1">{{ $card->year }}</span>@endif
                                            @unless ($card->is_active)<span class="badge text-bg-secondary ms-1">Disembunyikan</span>@endunless
                                        </div>
                                        <div class="block-item__preview">{{ $card->subtitle ?: ($card->summary ?: '—') }}</div>
                                    </div>
                                    <div class="dash-actions">
                                        @unless ($searching)
                                            <form method="POST" action="{{ route('dashboard.cards.move', [$menu, $block, $card, 'naik']) }}">@csrf<button class="btn btn-sm btn-light btn-icon" title="Naikkan" @disabled($loop->first && $cards->onFirstPage())><i class="bi bi-arrow-up"></i></button></form>
                                            <form method="POST" action="{{ route('dashboard.cards.move', [$menu, $block, $card, 'turun']) }}">@csrf<button class="btn btn-sm btn-light btn-icon" title="Turunkan" @disabled($loop->last && ! $cards->hasMorePages())><i class="bi bi-arrow-down"></i></button></form>
                                        @endunless
                                        <form method="POST" action="{{ route('dashboard.cards.toggle', [$menu, $block, $card]) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-light btn-icon" title="{{ $card->is_active ? 'Sembunyikan' : 'Tampilkan' }}"><i class="bi {{ $card->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i></button></form>
                                        @if ($card->is_active && $block->is_active && $menu->isVisible())
                                            <a href="{{ route('page.item', [$menu->slug, $card->slug]) }}" target="_blank" class="btn btn-sm btn-light btn-icon" title="Lihat detail di website"><i class="bi bi-box-arrow-up-right"></i></a>
                                        @endif
                                        <a href="{{ route('dashboard.cards.edit', [$menu, $block, $card]) }}" class="btn btn-sm btn-soft btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                                        @include('dashboard.partials.delete', ['url' => route('dashboard.cards.destroy', [$menu, $block, $card]), 'label' => 'kartu "'.$card->title.'"'])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @if ($cards->hasPages())<div class="card-footer">{{ $cards->links() }}</div>@endif
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dash-card mb-4">
                <div class="card-header"><h2 class="dash-card__title"><i class="bi bi-sliders me-1"></i> Tampilan blok ini</h2></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-6 fw-normal text-muted-d">Kartu per baris</dt><dd class="col-6 fw-semibold">{{ $block->cardSetting('columns') }} kartu</dd>
                        <dt class="col-6 fw-normal text-muted-d">Bentuk gambar</dt><dd class="col-6 fw-semibold">{{ \Illuminate\Support\Str::before(\App\Models\Block::CARD_SHAPES[$block->cardSetting('shape')] ?? '-', ' —') }}</dd>
                        <dt class="col-6 fw-normal text-muted-d">Ringkasan di kartu</dt><dd class="col-6 fw-semibold">{{ $block->cardSetting('excerpt') ? 'Ya' : 'Tidak' }}</dd>
                        <dt class="col-6 fw-normal text-muted-d">Teks tautan</dt><dd class="col-6 fw-semibold">{{ $block->cardSetting('button') }}</dd>
                        <dt class="col-6 fw-normal text-muted-d">Status blok</dt><dd class="col-6 mb-0">@include('dashboard.partials.status', ['active' => $block->is_active])</dd>
                    </dl>
                </div>
                <div class="card-footer d-flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.blocks.edit', [$menu, $block]) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Ubah tampilan</a>
                    <a href="{{ route('dashboard.studio.show', $menu) }}#fitur-halaman" class="btn btn-sm btn-light"><i class="bi bi-ui-checks-grid"></i> Pencarian & filter</a>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2 class="dash-card__title"><i class="bi bi-lightbulb me-1"></i> Tips mengisi</h2></div>
                <div class="card-body small">
                    <ul class="mb-0 ps-3">
                        <li class="mb-1">Isi <strong>Kategori</strong> (mis. <em>Angkatan 2020</em>) agar pengunjung bisa menyaring kartu.</li>
                        <li class="mb-1">Isi <strong>Tahun</strong> agar kartu bisa dicari dan disaring per tahun.</li>
                        <li class="mb-1"><strong>Info tambahan</strong> tampil rapi di halaman detail, satu baris satu info, mis. <code>Pekerjaan: Dokter</code>.</li>
                        <li>Gunakan tombol <strong>Simpan &amp; tambah lagi</strong> untuk mengisi banyak kartu sekaligus.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
