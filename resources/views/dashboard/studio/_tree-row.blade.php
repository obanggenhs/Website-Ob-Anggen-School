<div class="tree-row {{ isset($menu) && $menu?->id === $item->id ? 'is-current' : '' }} {{ $item->is_active ? '' : 'is-hidden' }}">
    <span class="tree-handle" title="Seret untuk memindah"><i class="bi bi-grip-vertical"></i></span>
    <a href="{{ route('dashboard.studio.show', $item) }}" class="tree-link" title="{{ $item->type_label }}">
        <i class="bi {{ $item->type_icon }}"></i>
        <span>{{ $item->title }}</span>
    </a>
    @if ($item->is_highlight)<i class="bi bi-star-fill text-warning small"></i>@endif
    @unless ($item->is_active)<i class="bi bi-eye-slash text-muted-d small"></i>@endunless
    @foreach ($item->roleBadges() as $role)
        <span class="role-chip role-chip--{{ $role }}">{{ $role === 'publikasi' ? 'PUB' : 'DAF' }}</span>
    @endforeach
    @if ($item->isPage())
        <span class="tree-type">{{ $item->blocks_count ?? $item->blocks()->count() }} blok</span>
    @endif
</div>
