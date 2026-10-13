@extends('layouts.dashboard')

@php
    $editing = $card->exists;
    $blockName = $block->title ?: 'Koleksi Kartu';
    $videoSource = old('video_source', $card->videoSource());
@endphp

@section('title', ($editing ? 'Ubah' : 'Tambah').' kartu')
@section('page_title', $editing ? 'Ubah kartu: '.$card->title : 'Tambah kartu')
@section('page_subtitle', 'Blok "'.$blockName.'" di halaman "'.$menu->title.'". Kartu tampil di daftar, dan isinya tampil saat kartu diklik.')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.studio.show', $menu) }}">{{ $menu->title }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cards.index', [$menu, $block]) }}">{{ $blockName }}</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Tambah' }}</li>
@endsection
@section('actions')
    <a href="{{ route('dashboard.cards.index', [$menu, $block]) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Daftar kartu</a>
    @if ($editing && $card->is_active)
        <a href="{{ route('page.item', [$menu->slug, $card->slug]) }}" target="_blank" class="btn btn-soft"><i class="bi bi-box-arrow-up-right"></i> Lihat di website</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.cards.update', [$menu, $block, $card]) : route('dashboard.cards.store', [$menu, $block]) }}" enctype="multipart/form-data"
          data-media-form>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card dash-card mb-4">
                    <div class="card-header"><h2 class="dash-card__title"><i class="bi bi-card-heading me-1"></i> Tampil di kartu</h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <x-form.input name="title" label="Nama / judul kartu" :value="$card->title" required placeholder="mis. Maria Kogoya, Program Literasi, Juara 1 Olimpiade" />
                            <x-form.input name="subtitle" label="Keterangan singkat (di bawah judul)" :value="$card->subtitle" placeholder="mis. Lulusan SMA · Kini kuliah Kedokteran" />
                            <x-form.input name="category" label="Kategori / kelompok" :value="$card->category" col="col-md-7" list="categoryList" autocomplete="off"
                                placeholder="mis. Angkatan 2020" help="Dipakai untuk tombol filter di halaman. Pilih yang sudah ada atau ketik baru." />
                            <x-form.input name="year" label="Tahun" :value="$card->year" col="col-md-5" inputmode="numeric" maxlength="4" placeholder="mis. 2024"
                                help="Dipakai untuk pencarian & filter tahun." />
                            <datalist id="categoryList">
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"></option>
                                @endforeach
                            </datalist>
                            <x-form.textarea name="excerpt" label="Ringkasan di kartu (opsional)" :value="$card->excerpt" rows="2"
                                help="Bila kosong, diambil otomatis dari awal isi detail." maxlength="500" />
                        </div>
                    </div>
                </div>

                <div class="card dash-card mb-4">
                    <div class="card-header"><h2 class="dash-card__title"><i class="bi bi-file-earmark-richtext me-1"></i> Isi detail (tampil saat kartu diklik)</h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <x-form.editor name="body" label="Isi lengkap" :value="$card->body" :height="260" />
                            <x-form.textarea name="meta" label="Info tambahan (opsional)" :value="$card->meta" rows="4"
                                :placeholder="implode(PHP_EOL, ['Angkatan: 2020', 'Jenjang: SMA', 'Pekerjaan: Dokter', 'Kota: Wamena'])"
                                help="Satu baris satu info dengan format Label: Nilai. Tampil sebagai daftar rapi di halaman detail." />
                        </div>
                    </div>
                </div>

                <div class="card dash-card mb-4">
                    <div class="card-header">
                        <h2 class="dash-card__title"><i class="bi bi-file-earmark-pdf me-1"></i> Dokumen / PDF (opsional)</h2>
                        <small class="text-muted-d">Mis. isi Year Book, brosur, atau laporan. PDF bisa dibaca langsung di halaman detail kartu dan diunduh.</small>
                    </div>
                    <div class="card-body">
                        @include('dashboard.studio._media-document', [
                            'path' => $card->document, 'name' => $card->document_name, 'size' => $card->document_size,
                            'pdfOnly' => false, 'removable' => true, 'label' => 'Unggah dokumen',
                        ])
                        <div class="mt-3">
                            <x-form.toggle name="document_download" label="Izinkan pengunjung mengunduh dokumen" :checked="(bool) $card->document_download" />
                        </div>
                    </div>
                </div>

                <div class="card dash-card mb-4">
                    <div class="card-header">
                        <h2 class="dash-card__title"><i class="bi bi-play-btn me-1"></i> Video (opsional)</h2>
                        <small class="text-muted-d">Tampil di halaman detail kartu.</small>
                    </div>
                    <div class="card-body">
                        <div class="media-switch media-switch--3 mb-3" role="radiogroup" aria-label="Sumber video">
                            @foreach (['none' => ['Tidak ada video', 'bi-slash-circle'], 'youtube' => ['Link YouTube', 'bi-youtube'], 'upload' => ['Video dari komputer', 'bi-upload']] as $key => [$label, $icon])
                                <input type="radio" class="btn-check" name="video_source" id="vs_{{ $key }}" value="{{ $key }}" data-media-type @checked($videoSource === $key)>
                                <label class="media-switch__item" for="vs_{{ $key }}"><i class="bi {{ $icon }}"></i> {{ $label }}</label>
                            @endforeach
                        </div>
                        <div class="media-panel" data-media-panel="none">
                            <div class="text-muted-d small"><i class="bi bi-info-circle me-1"></i> Kartu ini tidak menampilkan video.</div>
                        </div>
                        <div class="media-panel" data-media-panel="youtube">
                            @include('dashboard.studio._media-youtube', ['url' => $card->video_url])
                        </div>
                        <div class="media-panel" data-media-panel="upload">
                            @include('dashboard.studio._media-video', ['path' => $card->video_file])
                        </div>
                    </div>
                </div>

                <div class="card dash-card">
                    <div class="card-header"><h2 class="dash-card__title"><i class="bi bi-images me-1"></i> Foto dokumentasi (opsional)</h2></div>
                    <div class="card-body">
                        <label class="form-label" for="f_photos">Tambah foto (boleh pilih banyak sekaligus)</label>
                        <input type="file" name="photos[]" id="f_photos" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror" accept="image/*" multiple data-count-label="photosCount">
                        @error('photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('photos.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text" id="photosCount">Tanpa batas jumlah dan ukuran. Tampil sebagai galeri di halaman detail.</div>

                        @if (! empty($card->photos))
                            <label class="form-label mt-3">Foto saat ini <span class="fw-normal text-muted-d">— centang untuk menghapus</span></label>
                            <div class="photo-grid">
                                @foreach ($card->photos as $i => $photo)
                                    <label class="photo-grid__item">
                                        <img src="{{ media_url($photo) }}" alt="">
                                        <span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_photos[]" value="{{ $i }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card dash-card mb-4">
                    <div class="card-header"><h2 class="dash-card__title">Foto utama</h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <x-form.image name="image" label="Foto kartu" :value="$card->image" remove="remove_image"
                                :help="'Bentuk di kartu: '.\Illuminate\Support\Str::before(\App\Models\Block::CARD_SHAPES[$block->cardSetting('shape')] ?? '-', ' —').'. JPG/PNG/WEBP, tanpa batas ukuran.'" />
                            <x-form.toggle name="is_active" label="Tampilkan di website" :checked="$card->is_active" />
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> {{ $editing ? 'Simpan perubahan' : 'Simpan kartu' }}</button>
                    @unless ($editing)
                        <button class="btn btn-soft" name="after" value="lagi"><i class="bi bi-plus-circle me-1"></i> Simpan &amp; tambah lagi</button>
                    @endunless
                    <a href="{{ route('dashboard.cards.index', [$menu, $block]) }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
