{{-- Pohon struktur website: Menu Utama → Submenu. Bisa diseret untuk mengubah urutan. --}}
<div class="card dash-card studio-tree">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title">Struktur website</h2>
            <small class="text-muted-d">Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#menuCreateModal" data-parent="">
            <i class="bi bi-plus-lg"></i> Menu utama
        </button>
    </div>
    <div class="card-body">
        <ul class="tree-list" id="studioTree" data-reorder-url="{{ route('dashboard.studio.reorder') }}">
            @foreach ($tree as $root)
                <li class="tree-item" data-id="{{ $root->id }}">
                    @include('dashboard.studio._tree-row', ['item' => $root])
                    @if ($root->isGroup())
                        <ul class="tree-children">
                            @foreach ($root->children as $child)
                                <li class="tree-item" data-id="{{ $child->id }}">
                                    @include('dashboard.studio._tree-row', ['item' => $child])
                                </li>
                            @endforeach
                        </ul>
                        <a href="#" class="tree-add ms-4 mb-1" data-bs-toggle="modal" data-bs-target="#menuCreateModal" data-parent="{{ $root->id }}">
                            <i class="bi bi-plus-circle"></i> Submenu di {{ $root->title }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    <div class="card-footer">
        <div class="tree-legend">
            <span><span class="role-chip role-chip--publikasi">PUB</span> Admin Publikasi</span>
            <span><span class="role-chip role-chip--pendaftaran">DAF</span> Admin Pendaftaran</span>
            <span><i class="bi bi-eye-slash"></i> disembunyikan</span>
            <span><i class="bi bi-star-fill text-warning"></i> tombol menonjol</span>
        </div>
    </div>
</div>
