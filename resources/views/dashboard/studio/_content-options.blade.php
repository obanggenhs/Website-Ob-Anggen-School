{{--
    Fitur bantuan pengunjung untuk halaman ini (pencarian, filter, urutkan, dst.)
    Tampil di Studio > [menu] > Isi Konten untuk halaman konten & halaman modul yang mendukung.
--}}
@php
    $optType = $menu->type === 'beranda' ? 'halaman' : $menu->type;
    $current = $menu->pageOptions();
    $collections = $menu->isPage() ? $menu->blocks->where('type', 'koleksi')->count() : null;
@endphp

<form method="POST" action="{{ route('dashboard.studio.options', $menu) }}" class="card dash-card mb-4" id="fitur-halaman">
    @csrf @method('PUT')
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title"><i class="bi bi-ui-checks-grid me-1"></i> Fitur bantuan pengunjung</h2>
            <small class="text-muted-d">
                @if ($menu->isPage())
                    Berlaku untuk blok <strong>Koleksi Kartu</strong> di halaman ini. Pilih fitur yang memudahkan pengunjung mencari isi.
                @else
                    Berlaku untuk daftar {{ \Illuminate\Support\Str::lower($menu->type_label) }} di halaman ini.
                @endif
            </small>
        </div>
        <span class="badge badge-soft">{{ collect($current)->except('per_page')->filter()->count() }} fitur aktif</span>
    </div>

    <div class="card-body">
        @if ($collections === 0)
            <div class="alert alert-light border small d-flex gap-2 align-items-start">
                <i class="bi bi-lightbulb text-warning"></i>
                <div>Halaman ini belum punya blok <strong>Koleksi Kartu</strong>. Pilihan di bawah akan dipakai setelah blok tersebut ditambahkan lewat tombol <em>Tambah blok › Koleksi Kartu</em>.</div>
            </div>
        @endif

        <div class="option-grid">
            @foreach (\App\Support\PageOptions::SUPPORT[$optType] as $key)
                @continue($key === 'per_page')
                <label class="option-tile" for="opt_{{ $key }}">
                    <span class="option-tile__icon"><i class="bi {{ \App\Support\PageOptions::OPTIONS[$key]['icon'] }}"></i></span>
                    <span class="option-tile__text">
                        <strong>{{ \App\Support\PageOptions::label($optType, $key) }}</strong>
                        <small>{{ \App\Support\PageOptions::OPTIONS[$key]['desc'] }}</small>
                    </span>
                    <span class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" role="switch" name="opt_{{ $key }}" id="opt_{{ $key }}" value="1" @checked($current[$key])>
                    </span>
                </label>
            @endforeach
        </div>

        @if (in_array('per_page', \App\Support\PageOptions::SUPPORT[$optType], true))
            <div class="row g-2 align-items-center mt-3">
                <div class="col-sm-auto"><label class="form-label mb-0" for="per_page"><i class="bi {{ \App\Support\PageOptions::OPTIONS['per_page']['icon'] }} me-1"></i> {{ \App\Support\PageOptions::OPTIONS['per_page']['label'] }}</label></div>
                <div class="col-sm-auto">
                    <select name="per_page" id="per_page" class="form-select form-select-sm option-perpage">
                        @foreach (\App\Support\PageOptions::PER_PAGE as $n)
                            <option value="{{ $n }}" @selected($current['per_page'] === $n)>{{ $n }} per halaman</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm"><small class="text-muted-d">{{ \App\Support\PageOptions::OPTIONS['per_page']['desc'] }}</small></div>
            </div>
        @endif
    </div>

    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
        @if ($menu->type !== 'dropdown')
            <a href="{{ $menu->link }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat hasilnya di website</a>
        @endif
        <button class="btn btn-primary btn-sm"><i class="bi bi-check2-circle me-1"></i> Simpan fitur halaman</button>
    </div>
</form>
