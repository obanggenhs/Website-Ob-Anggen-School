{{--
    Daftar blok isi halaman.
    - Halaman Konten & Beranda: isi utama halaman.
    - Halaman modul ($extra = true): isi tambahan yang tampil di atas/bawah daftar (mis. PDF Year Book di halaman Kelulusan).
--}}
@php
    $extra = $extra ?? false;
    $blockTypes = $menu->isPage() ? \App\Models\Block::TYPES : array_diff_key(\App\Models\Block::TYPES, ['koleksi' => true]);
@endphp
<div class="card dash-card mb-4" @if ($extra) id="isi-tambahan" @endif>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title">{{ $blocksTitle ?? 'Isi halaman' }}</h2>
            <small class="text-muted-d">
                @if ($extra)
                    Teks, foto, video, YouTube, atau PDF yang tampil bersama daftar {{ \Illuminate\Support\Str::lower($menu->type_label) }}. Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan.
                @else
                    Blok tampil berurutan dari atas ke bawah. Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan.
                @endif
            </small>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if ($extra)
                <form method="POST" action="{{ route('dashboard.studio.blocks-place', $menu) }}" class="d-flex align-items-center gap-2">
                    @csrf @method('PUT')
                    <label for="blocks_at" class="small text-muted-d text-nowrap">Letak</label>
                    <select name="blocks_at" id="blocks_at" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="atas" @selected($menu->blocksAt() === 'atas')>Di atas daftar</option>
                        <option value="bawah" @selected($menu->blocksAt() === 'bawah')>Di bawah daftar</option>
                    </select>
                </form>
            @endif
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-plus-lg"></i> Tambah blok</button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 block-type-menu">
                    @foreach ($blockTypes as $key => $t)
                        <li>
                            <a class="dropdown-item d-flex gap-2 py-2" href="{{ route('dashboard.blocks.create', [$menu, 'jenis' => $key]) }}">
                                <i class="bi {{ $t['icon'] }} text-primary fs-5"></i>
                                <span><strong class="d-block small">{{ $t['label'] }}</strong><small class="text-muted-d">{{ $t['desc'] }}</small></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if ($menu->blocks->isEmpty())
            @include('dashboard.partials.empty', [
                'icon' => 'bi-bricks',
                'text' => $extra
                    ? 'Belum ada isi tambahan. Bila perlu, tambahkan blok seperti Dokumen / PDF, Video, atau Teks.'
                    : 'Halaman ini belum punya isi. Mulai dengan menambah blok Teks, Media + Teks, atau Dokumen / PDF.',
            ])
        @else
            <div id="blockList" data-reorder-url="{{ route('dashboard.blocks.reorder', $menu) }}">
                @foreach ($menu->blocks as $block)
                    <div class="block-item {{ $block->is_active ? '' : 'is-hidden' }}" data-id="{{ $block->id }}" data-anchor="blok-{{ $block->id }}">
                        <span class="tree-handle"><i class="bi bi-grip-vertical"></i></span>
                        <span class="block-item__icon"><i class="bi {{ $block->type_icon }}"></i></span>
                        <div class="min-w-0 flex-fill">
                            <div class="block-item__title">
                                {{ $block->title ?: $block->type_label }}
                                <span class="badge badge-soft ms-1">{{ $block->type_label }}</span>
                                <span class="badge text-bg-light border ms-1" title="Lebar & posisi blok"><i class="bi {{ \App\Models\Block::PLACEMENTS[$block->placementKey()]['icon'] }} me-1"></i>{{ $block->layoutLabel() }}</span>
                                @if ($block->pairingKey() !== 'auto' && ! $loop->first)
                                    <span class="badge text-bg-light border ms-1" title="{{ \App\Models\Block::PAIRINGS[$block->pairingKey()]['desc'] }}"><i class="bi {{ \App\Models\Block::PAIRINGS[$block->pairingKey()]['icon'] }} me-1"></i>{{ \App\Models\Block::PAIRINGS[$block->pairingKey()]['label'] }} blok sebelumnya</span>
                                @endif
                                @if ($block->contentAlign() !== 'left')
                                    <span class="badge text-bg-light border ms-1" title="Perataan isi"><i class="bi {{ \App\Models\Block::ALIGNS[$block->contentAlign()]['icon'] }}"></i></span>
                                @endif
                                @unless ($block->is_active)<span class="badge text-bg-secondary ms-1">Disembunyikan</span>@endunless
                            </div>
                            <div class="block-item__preview">{{ $block->preview ?: '—' }}</div>
                        </div>
                        <div class="dash-actions">
                            @if ($block->type === 'koleksi')
                                <a href="{{ route('dashboard.cards.index', [$menu, $block]) }}" class="btn btn-sm btn-primary" title="Tambah & ubah kartu di blok ini"><i class="bi bi-grid-3x3-gap"></i> Kelola kartu</a>
                            @endif
                            <form method="POST" action="{{ route('dashboard.blocks.move', [$menu, $block, 'naik']) }}">@csrf<button class="btn btn-sm btn-light btn-icon" title="Naikkan" @disabled($loop->first)><i class="bi bi-arrow-up"></i></button></form>
                            <form method="POST" action="{{ route('dashboard.blocks.move', [$menu, $block, 'turun']) }}">@csrf<button class="btn btn-sm btn-light btn-icon" title="Turunkan" @disabled($loop->last)><i class="bi bi-arrow-down"></i></button></form>
                            <form method="POST" action="{{ route('dashboard.blocks.toggle', [$menu, $block]) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-light btn-icon" title="{{ $block->is_active ? 'Sembunyikan' : 'Tampilkan' }}"><i class="bi {{ $block->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i></button></form>
                            <a href="{{ route('dashboard.blocks.edit', [$menu, $block]) }}" class="btn btn-sm btn-soft btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                            @include('dashboard.partials.delete', ['url' => route('dashboard.blocks.destroy', [$menu, $block]), 'label' => 'blok '.$block->type_label])
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
