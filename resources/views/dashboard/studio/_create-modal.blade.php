{{-- Modal tambah Menu Utama / Submenu --}}
@php
    $groups = $tree->where('type', 'dropdown');
    $paramMap = collect($types)->mapWithKeys(fn ($t, $key) => [$key => \App\Support\Modules::paramOptions($key)]);
    $rootOnly = collect($types)->filter(fn ($t) => ! empty($t['root_only']))->keys()->values();
    $usedSingle = \App\Models\Menu::whereIn('type', collect($types)->filter(fn ($t) => ! empty($t['single']))->keys())->pluck('type')->all();
@endphp
<div class="modal fade" id="menuCreateModal" tabindex="-1" aria-labelledby="menuCreateLabel" aria-hidden="true" @if ($errors->any() && old('_form') === 'create-menu') data-auto-open @endif>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form method="POST" action="{{ route('dashboard.studio.store') }}" class="modal-content" data-type-param data-params='@json($paramMap)' data-root-only='@json($rootOnly)'>
            @csrf
            <input type="hidden" name="_form" value="create-menu">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="menuCreateLabel"><i class="bi bi-plus-square me-1"></i> Tambah menu</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label" for="new_title">1. Nama menu <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="new_title" value="{{ old('title') }}" class="form-control" placeholder="mis. Prestasi Siswa" required maxlength="80">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="new_parent">2. Letakkan di</label>
                        <select name="parent_id" id="new_parent" class="form-select">
                            <option value="">Menu utama (tampil langsung di navbar)</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}" @selected(old('parent_id') == $g->id)>Submenu di dalam "{{ $g->title }}"</option>
                            @endforeach
                        </select>
                        <div class="form-text">Submenu hanya bisa ditaruh di menu bertipe Grup Dropdown.</div>
                    </div>
                </div>

                <label class="form-label">3. Jenis isi menu <span class="text-danger">*</span></label>
                <div class="type-grid mb-4">
                    @foreach ($types as $key => $t)
                        @php $taken = in_array($key, $usedSingle, true); @endphp
                        <div class="type-option">
                            <input type="radio" name="type" id="type_{{ $key }}" value="{{ $key }}" @checked(old('type', 'halaman') === $key) @disabled($taken)>
                            <label for="type_{{ $key }}">
                                <i class="bi {{ $t['icon'] }}"></i>
                                <span><strong>{{ $t['label'] }}</strong><small>{{ $taken ? 'Sudah ada (hanya boleh satu)' : $t['desc'] }}</small></span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="row g-3">
                    <div class="col-md-6 d-none" data-param-wrap>
                        <label class="form-label" for="new_param">Tampilkan</label>
                        <select name="param" id="new_param" class="form-select" data-value="{{ old('param') }}"></select>
                    </div>
                    <div class="col-md-6 d-none" data-url-wrap>
                        <label class="form-label" for="new_url">Alamat tautan</label>
                        <input type="text" name="url" id="new_url" value="{{ old('url') }}" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">4. Ikut dikelola oleh <span class="fw-normal text-muted-d">(bisa diubah nanti di tab Akses Dashboard)</span></label>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="access_publikasi" id="new_pub" value="1" @checked(old('access_publikasi'))>
                                <label class="form-check-label" for="new_pub"><span class="role-chip role-chip--publikasi">PUB</span> Admin Publikasi</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="access_pendaftaran" id="new_reg" value="1" @checked(old('access_pendaftaran'))>
                                <label class="form-check-label" for="new_reg"><span class="role-chip role-chip--pendaftaran">DAF</span> Admin Pendaftaran</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Buat menu</button>
            </div>
        </form>
    </div>
</div>
