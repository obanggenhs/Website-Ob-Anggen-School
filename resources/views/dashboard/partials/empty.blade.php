<div class="dash-empty">
    <i class="bi {{ $icon ?? 'bi-inbox' }}"></i>
    <div>{{ $text ?? 'Belum ada data.' }}</div>
    @isset($action)<a href="{{ $action }}" class="btn btn-sm btn-primary mt-3"><i class="bi bi-plus-lg"></i> {{ $actionLabel ?? 'Tambah' }}</a>@endisset
</div>
