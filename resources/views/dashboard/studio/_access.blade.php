{{-- Tab Akses Dashboard: menu ini ikut tampil di dashboard role mana --}}
@php
    $pubUsers = \App\Models\User::where('role', 'publikasi')->where('is_active', true)->pluck('name');
    $regUsers = \App\Models\User::where('role', 'pendaftaran')->where('is_active', true)->pluck('name');
    $permKey = \App\Support\Modules::permissionForMenu($menu->type, $menu->param);
    $permLabel = $permKey ? \App\Support\Modules::permissions()[$permKey]['label'] : null;
@endphp
<form method="POST" action="{{ route('dashboard.studio.access', $menu) }}" class="card dash-card">
    @csrf @method('PUT')
    <div class="card-header">
        <h2 class="dash-card__title">Siapa yang ikut mengelola "{{ $menu->title }}"?</h2>
        <small class="text-muted-d">Master Admin selalu bisa mengelola semuanya. Centang role lain bila menu ini perlu dikelola mereka.</small>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="access-card d-block" style="--access: #2F5D50" for="acc_pub">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="access-card__icon"><i class="bi bi-broadcast"></i></span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input fs-5" type="checkbox" role="switch" name="access_publikasi" id="acc_pub" value="1" @checked($menu->access_publikasi)>
                        </div>
                    </div>
                    <strong>Dashboard Admin Publikasi</strong>
                    <p class="small text-muted-d mb-2">Menu tampil di sidebar mereka pada bagian "Tugas dari Master".</p>
                    <div class="small">Akun aktif: {{ $pubUsers->isEmpty() ? 'belum ada' : $pubUsers->join(', ') }}</div>
                </label>
            </div>
            <div class="col-md-6">
                <label class="access-card d-block" style="--access: #1F4E79" for="acc_reg">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="access-card__icon"><i class="bi bi-clipboard-check"></i></span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input fs-5" type="checkbox" role="switch" name="access_pendaftaran" id="acc_reg" value="1" @checked($menu->access_pendaftaran)>
                        </div>
                    </div>
                    <strong>Dashboard Admin Pendaftaran</strong>
                    <p class="small text-muted-d mb-2">Menu tampil di sidebar mereka pada bagian "Tugas dari Master".</p>
                    <div class="small">Akun aktif: {{ $regUsers->isEmpty() ? 'belum ada' : $regUsers->join(', ') }}</div>
                </label>
            </div>
        </div>

        <div class="alert alert-light border small mt-4 mb-0">
            <strong>Yang bisa dilakukan role yang dicentang:</strong>
            <ul class="mb-0 mt-1">
                @if ($menu->isPage())
                    <li>Menambah, mengubah, mengurutkan, dan menyembunyikan blok isi halaman.</li>
                @endif
                @if (! in_array($menu->type, ['dropdown', 'tautan', 'beranda'], true))
                    <li>Mengubah header halaman (sub judul, gambar latar) dan teks pembuka.</li>
                @endif
                @if ($permLabel)
                    <li>Membuka modul data <strong>{{ $permLabel }}</strong> di dashboard mereka.</li>
                @endif
                <li>Tidak bisa mengubah nama, posisi, jenis, atau menghapus menu (tetap wewenang Master Admin).</li>
            </ul>
        </div>

        @if ($menu->isGroup() && $menu->children->isNotEmpty())
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="apply_children" id="apply_children" value="1">
                <label class="form-check-label" for="apply_children">Terapkan juga ke {{ $menu->children->count() }} submenu di dalamnya</label>
            </div>
        @endif
    </div>
    <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan akses</button></div>
</form>
