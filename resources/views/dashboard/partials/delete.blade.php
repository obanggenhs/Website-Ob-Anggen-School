{{-- Tombol hapus kecil: @include('dashboard.partials.delete', ['url' => ..., 'label' => ...]) --}}
<form method="POST" action="{{ $url }}" class="d-inline" data-confirm="{{ $confirm ?? 'Hapus '.($label ?? 'data ini').'? Tindakan ini tidak bisa dibatalkan.' }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm {{ $class ?? 'btn-outline-danger btn-icon' }}" title="Hapus" data-bs-toggle="tooltip">
        <i class="bi bi-trash"></i>@isset($text) {{ $text }}@endisset
    </button>
</form>
