{{-- Tab Pengaturan Menu (khusus Master Admin) --}}
@php
    $paramMap = collect($types)->mapWithKeys(fn ($t, $key) => [$key => \App\Support\Modules::paramOptions($key)]);
    $iconSuggestions = ['bi-house-door', 'bi-info-circle', 'bi-book', 'bi-mortarboard', 'bi-newspaper', 'bi-images', 'bi-calendar3', 'bi-people', 'bi-building', 'bi-pencil-square', 'bi-trophy', 'bi-heart', 'bi-telephone', 'bi-globe'];
@endphp
<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('dashboard.studio.update', $menu) }}" class="card dash-card" data-type-param data-params='@json($paramMap)' data-root-only='[]'>
            @csrf @method('PUT')
            <div class="card-header"><h2 class="dash-card__title">Pengaturan menu</h2></div>
            <div class="card-body">
                <div class="row g-3">
                    <x-form.input name="title" label="Nama menu" :value="$menu->title" required col="col-md-6" />
                    <x-form.input name="slug" label="Alamat (slug)" :value="$menu->slug" required col="col-md-6"
                        help="{{ $menu->type === 'halaman' ? url('/halaman').'/'.$menu->slug : 'Dipakai sebagai pengenal unik menu.' }}" />

                    <div class="col-md-6">
                        <label class="form-label" for="f_type">Jenis isi</label>
                        <select name="type" id="f_type" class="form-select" @disabled($menu->type === 'beranda')>
                            @foreach ($types as $key => $t)
                                <option value="{{ $key }}" @selected(old('type', $menu->type) === $key)>{{ $t['label'] }}</option>
                            @endforeach
                        </select>
                        @if ($menu->type === 'beranda')<input type="hidden" name="type" value="beranda">@endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="f_parent_id">Letak</label>
                        <select name="parent_id" id="f_parent_id" class="form-select" @disabled($menu->isGroup() || $menu->type === 'beranda')>
                            <option value="">Menu utama</option>
                            @foreach ($roots as $root)
                                <option value="{{ $root->id }}" @selected((int) old('parent_id', $menu->parent_id) === $root->id)>Submenu di "{{ $root->title }}"</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-none" data-param-wrap>
                        <label class="form-label" for="f_param">Tampilkan</label>
                        <select name="param" id="f_param" class="form-select" data-value="{{ old('param', $menu->param) }}"></select>
                    </div>
                    <div class="col-md-6 d-none" data-url-wrap>
                        <x-form.input name="url" label="Alamat tautan" :value="$menu->url" col="" placeholder="https://..." />
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="f_icon">Ikon (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i id="iconPreview"></i></span>
                            <input type="text" name="icon" id="f_icon" value="{{ old('icon', $menu->icon) }}" class="form-control" placeholder="bi-star" data-icon-input="iconPreview">
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            @foreach ($iconSuggestions as $icon)
                                <button type="button" class="btn btn-sm btn-light btn-icon" data-icon-pick="f_icon" data-icon="{{ $icon }}" title="{{ $icon }}"><i class="bi {{ $icon }}"></i></button>
                            @endforeach
                        </div>
                        <div class="form-text">Kosongkan untuk memakai ikon bawaan jenis isi. Daftar lengkap: icons.getbootstrap.com</div>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>
                    <x-form.toggle name="is_active" label="Tampilkan menu ini di website" :checked="$menu->is_active" col="col-md-6" />
                    <x-form.toggle name="show_sidebar" label="Tampilkan sidebar (berita & kontak)" :checked="$menu->show_sidebar" col="col-md-6" />
                    @if (! $menu->parent_id)
                        <x-form.toggle name="is_highlight" label="Jadikan tombol menonjol di ujung navbar" :checked="$menu->is_highlight" col="col-md-6" />
                    @endif
                    <x-form.toggle name="open_new_tab" label="Buka di tab baru" :checked="$menu->open_new_tab" col="col-md-6" />
                </div>
            </div>
            <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan pengaturan</button></div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card dash-card mb-4">
            <div class="card-header"><h2 class="dash-card__title">Urutan</h2></div>
            <div class="card-body d-flex gap-2">
                <form method="POST" action="{{ route('dashboard.studio.move', [$menu, 'naik']) }}" class="flex-fill">@csrf<button class="btn btn-light w-100"><i class="bi bi-arrow-up"></i> Naikkan</button></form>
                <form method="POST" action="{{ route('dashboard.studio.move', [$menu, 'turun']) }}" class="flex-fill">@csrf<button class="btn btn-light w-100"><i class="bi bi-arrow-down"></i> Turunkan</button></form>
            </div>
        </div>

        @if ($menu->type !== 'beranda')
            <div class="card dash-card border border-danger-subtle">
                <div class="card-header"><h2 class="dash-card__title text-danger">Hapus menu</h2></div>
                <div class="card-body">
                    <p class="small text-muted-d">
                        Menghapus menu juga menghapus isi halamannya{{ $menu->children->isNotEmpty() ? ' dan '.$menu->children->count().' submenu di dalamnya' : '' }}.
                        Data modul (berita, galeri, dll.) tetap aman.
                    </p>
                    @include('dashboard.partials.delete', ['url' => route('dashboard.studio.destroy', $menu), 'class' => 'btn-danger w-100', 'text' => 'Hapus "'.$menu->title.'"', 'confirm' => 'Hapus menu "'.$menu->title.'" beserta isinya?'])
                </div>
            </div>
        @endif
    </div>
</div>
