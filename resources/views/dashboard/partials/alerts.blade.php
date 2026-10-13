@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex gap-2 align-items-start" role="alert">
        <i class="bi bi-check-circle-fill"></i><div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-start" role="alert">
        <i class="bi bi-exclamation-octagon-fill"></i><div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Periksa kembali isian berikut:</strong>
        <ul class="mb-0 mt-1 small">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
