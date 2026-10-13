@if ($active)
    <span class="badge text-bg-success-subtle text-success-emphasis"><i class="bi bi-eye"></i> {{ $on ?? 'Tampil' }}</span>
@else
    <span class="badge text-bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-eye-slash"></i> {{ $off ?? 'Disembunyikan' }}</span>
@endif
