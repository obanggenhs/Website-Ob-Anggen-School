<div class="card dash-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="dash-card__title">Submenu di "{{ $menu->title }}"</h2>
            <small class="text-muted-d">Grup dropdown tidak punya halaman sendiri. Kelola isi masing-masing submenu.</small>
        </div>
        @if (auth()->user()->isMaster())
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#menuCreateModal" data-parent="{{ $menu->id }}"><i class="bi bi-plus-lg"></i> Tambah submenu</button>
        @endif
    </div>
    <div class="card-body">
        @if ($menu->children->isEmpty())
            @include('dashboard.partials.empty', ['icon' => 'bi-menu-button-wide', 'text' => 'Belum ada submenu. Grup kosong tidak ditampilkan di navbar.'])
        @else
            <div class="row g-3">
                @foreach ($menu->children as $child)
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.studio.show', $child) }}" class="block-item text-decoration-none text-body mb-0 h-100 {{ $child->is_active ? '' : 'is-hidden' }}">
                            <span class="block-item__icon"><i class="bi {{ $child->type_icon }}"></i></span>
                            <span class="min-w-0 flex-fill">
                                <span class="block-item__title d-block">{{ $child->title }}</span>
                                <span class="block-item__preview d-block">{{ $child->type_label }}{{ $child->param_label ? ' · '.$child->param_label : '' }}</span>
                            </span>
                            @foreach ($child->roleBadges() as $role)<span class="role-chip role-chip--{{ $role }}">{{ $role === 'publikasi' ? 'PUB' : 'DAF' }}</span>@endforeach
                            <i class="bi bi-chevron-right text-muted-d"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
