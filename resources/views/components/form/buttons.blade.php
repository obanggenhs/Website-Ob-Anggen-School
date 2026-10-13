@props(['back', 'label' => 'Simpan'])
<div class="d-flex flex-wrap gap-2 justify-content-end border-top pt-3 mt-4">
    <a href="{{ $back }}" class="btn btn-light">Batal</a>
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1"></i> {{ $label }}</button>
</div>
