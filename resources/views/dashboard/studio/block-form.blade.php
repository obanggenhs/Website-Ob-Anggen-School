@extends('layouts.dashboard')

@php
    $type = $block->type;
    $info = \App\Models\Block::TYPES[$type];
    $editing = $block->exists;
    // Pilihan media yang sedang aktif (Media + Teks: foto/video/youtube/pdf, Video: youtube/upload)
    $media = old('media_type', $type === 'video' ? $block->videoSource() : $block->mediaKind());
    $docDownload = $editing ? (bool) $block->docSetting('download') : true;
@endphp

@section('title', ($editing ? 'Ubah' : 'Tambah').' blok '.$info['label'])
@section('page_title', ($editing ? 'Ubah' : 'Tambah').' blok: '.$info['label'])
@section('page_subtitle', 'Halaman "'.$menu->title.'" · '.$info['desc'])
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.studio.show', $menu) }}">{{ $menu->title }}</a></li>
    <li class="breadcrumb-item active">Blok</li>
@endsection
@section('actions')
    <a href="{{ route('dashboard.studio.show', $menu) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali ke halaman</a>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.blocks.update', [$menu, $block]) : route('dashboard.blocks.store', $menu) }}" enctype="multipart/form-data"
          data-media-form>
        @csrf
        @if ($editing) @method('PUT') @else <input type="hidden" name="type" value="{{ $type }}"> @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card dash-card">
                    <div class="card-body">
                        <div class="row g-3">
                            <x-form.input name="title" :label="$type === 'tombol' ? 'Judul ajakan' : 'Judul blok (opsional)'" :value="$block->title" />

                            @if (in_array($type, ['teks', 'gambar_teks', 'tombol'], true))
                                <x-form.editor name="body" label="Isi" :value="$block->body" :required="$type === 'teks'" :height="$type === 'tombol' ? 120 : 260" />
                            @endif

                            @if ($type === 'kutipan')
                                <x-form.textarea name="body" label="Kalimat kutipan" :value="strip_tags((string) $block->body)" rows="4" required />
                                <x-form.input name="caption" label="Sumber (opsional)" :value="$block->caption" placeholder="mis. Amsal 22:6" />
                            @endif

                            @if ($type === 'gambar_teks')
                                {{-- Media di samping teks: Foto · Video lokal · YouTube · PDF --}}
                                <div class="col-12">
                                    <label class="form-label">Media di samping teks</label>
                                    <div class="media-switch media-switch--wrap mb-3" role="radiogroup" aria-label="Jenis media">
                                        @foreach (\App\Models\Block::MEDIA_TYPES as $key => $m)
                                            <input type="radio" class="btn-check" name="media_type" id="mt_{{ $key }}" value="{{ $key }}" data-media-type @checked($media === $key)>
                                            <label class="media-switch__item" for="mt_{{ $key }}"><i class="bi {{ $m['icon'] }}"></i> {{ $m['label'] }}</label>
                                        @endforeach
                                    </div>

                                    <div class="media-panel" data-media-panel="image">
                                        <div class="row g-3">
                                            <x-form.image name="image" label="Foto" :value="$block->image" remove="remove_image" />
                                        </div>
                                    </div>
                                    <div class="media-panel" data-media-panel="video">
                                        @include('dashboard.studio._media-video', ['path' => $block->video_file])
                                    </div>
                                    <div class="media-panel" data-media-panel="youtube">
                                        @include('dashboard.studio._media-youtube', ['url' => $block->video_url])
                                    </div>
                                    <div class="media-panel" data-media-panel="pdf">
                                        @include('dashboard.studio._media-document', [
                                            'path' => $block->document, 'name' => $block->document_name, 'size' => $block->document_size,
                                            'pdfOnly' => true, 'removable' => false, 'label' => 'Unggah PDF',
                                        ])
                                        <div class="mt-3">
                                            <x-form.toggle name="doc_download" label="Izinkan pengunjung mengunduh PDF" :checked="$docDownload" />
                                        </div>
                                        <div class="form-text">Di samping teks tampil kartu dokumen dengan sampul halaman pertama. Pengunjung menekan <strong>Baca</strong> untuk membuka PDF di website.</div>
                                    </div>
                                </div>
                                <x-form.select name="image_position" label="Posisi media" :options="['right' => 'Di kanan teks', 'left' => 'Di kiri teks']" :value="$block->image_position" col="col-md-5" />
                            @endif

                            @if ($type === 'video')
                                {{-- Sumber video: YouTube atau file dari komputer --}}
                                <div class="col-12">
                                    <label class="form-label">Sumber video</label>
                                    <div class="media-switch media-switch--2 mb-3" role="radiogroup" aria-label="Sumber video">
                                        @foreach (\App\Models\Block::VIDEO_SOURCES as $key => $m)
                                            <input type="radio" class="btn-check" name="media_type" id="mt_{{ $key }}" value="{{ $key }}" data-media-type @checked($media === $key)>
                                            <label class="media-switch__item" for="mt_{{ $key }}"><i class="bi {{ $m['icon'] }}"></i> {{ $m['label'] }}</label>
                                        @endforeach
                                    </div>
                                    <div class="media-panel" data-media-panel="youtube">
                                        @include('dashboard.studio._media-youtube', ['url' => $block->video_url])
                                    </div>
                                    <div class="media-panel" data-media-panel="upload">
                                        @include('dashboard.studio._media-video', ['path' => $block->video_file])
                                    </div>
                                </div>
                                <x-form.input name="caption" label="Keterangan di bawah video" :value="$block->caption" />
                            @endif

                            @if ($type === 'dokumen')
                                <x-form.editor name="body" label="Keterangan (opsional)" :value="$block->body" :height="140" />
                                <div class="col-12">
                                    @include('dashboard.studio._media-document', [
                                        'path' => $block->document, 'name' => $block->document_name, 'size' => $block->document_size,
                                        'pdfOnly' => false, 'removable' => false, 'label' => 'Unggah dokumen',
                                    ])
                                </div>
                            @endif

                            @if ($type === 'daftar')
                                <x-form.textarea name="items" label="Daftar poin (satu baris = satu poin)" :value="$block->items" rows="8" required />
                            @endif

                            @if ($type === 'kartu')
                                <x-form.textarea name="items" label="Kartu (satu baris = satu kartu)" :value="$block->items" rows="8" required
                                    help="Format: Judul | Keterangan | ikon. Contoh: Iman | Bertumbuh setiap hari | bi-heart" />
                            @endif

                            @if ($type === 'galeri')
                                <div class="col-12">
                                    <label class="form-label" for="f_images">Tambah foto atau video (boleh pilih banyak sekaligus)</label>
                                    <input type="file" name="images[]" id="f_images" class="form-control @error('images.*') is-invalid @enderror" accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" multiple
                                           data-count-label="imagesCount">
                                    @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text" id="imagesCount"></div>
                                    <div class="form-text">Foto JPG/PNG/WEBP dan video MP4/WEBM/MOV, tanpa batas jumlah dan ukuran. Di website, foto dan video bisa diperbesar dan video diputar langsung.</div>
                                </div>
                                @if (! empty($block->images))
                                    <div class="col-12">
                                        <label class="form-label">Isi galeri saat ini ({{ $block->galleryCount() }}) <span class="fw-normal text-muted-d">— centang untuk menghapus</span></label>
                                        <div class="photo-grid">
                                            @foreach ($block->images as $i => $img)
                                                <label class="photo-grid__item">
                                                    @if (\App\Models\Block::isVideoPath($img))
                                                        <video src="{{ media_url($img) }}#t=0.5" muted playsinline preload="metadata"></video>
                                                        <span class="photo-grid__badge badge text-bg-dark"><i class="bi bi-play-fill"></i> Video</span>
                                                    @else
                                                        <img src="{{ media_url($img) }}" alt="">
                                                    @endif
                                                    <span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_images[]" value="{{ $i }}"></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if ($type === 'tombol')
                                <x-form.input name="button_label" label="Teks tombol" :value="$block->button_label" required col="col-md-5" />
                                <x-form.input name="button_url" label="Link tombol" :value="$block->button_url" required col="col-md-7" placeholder="/pendaftaran atau https://..." />
                            @endif

                            @if ($type === 'koleksi')
                                <x-form.editor name="body" label="Teks pembuka di atas kartu (opsional)" :value="$block->body" :height="140" />
                            @endif
                        </div>
                    </div>
                </div>

                @if ($type === 'dokumen')
                    <div class="card dash-card mt-4">
                        <div class="card-header">
                            <h2 class="dash-card__title"><i class="bi bi-file-earmark-pdf me-1"></i> Tampilan dokumen di website</h2>
                            <small class="text-muted-d">Berlaku untuk PDF. File Word, Excel, dan PowerPoint selalu tampil sebagai kartu unduh.</small>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <x-form.select name="doc_display" label="Cara menampilkan PDF" :options="\App\Models\Block::DOC_DISPLAYS" :value="$block->docSetting('display')" col="col-md-7"
                                    help="Langsung tampil: pembaca PDF terbuka di halaman. Kartu dokumen: lebih ringkas, PDF dibuka saat pengunjung menekan Baca." />
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="w-100"><x-form.toggle name="doc_download" label="Izinkan pengunjung mengunduh" :checked="$docDownload" /></div>
                                </div>
                                <x-form.image name="image" label="Sampul (opsional)" :value="$block->image" remove="remove_image" col="col-md-7"
                                    help="Tampil di kartu dokumen. Bila kosong, halaman pertama PDF dipakai sebagai sampul." />
                            </div>
                        </div>
                    </div>
                @endif

                @if ($type === 'koleksi')
                    <div class="card dash-card mt-4">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h2 class="dash-card__title"><i class="bi bi-grid-3x3-gap me-1"></i> Tampilan kartu</h2>
                                <small class="text-muted-d">Setiap kartu bisa diklik pengunjung untuk membuka halaman detailnya.</small>
                            </div>
                            @if ($editing)
                                <a href="{{ route('dashboard.cards.index', [$menu, $block]) }}" class="btn btn-sm btn-soft"><i class="bi bi-collection"></i> Kelola kartu ({{ $block->cards()->count() }})</a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <x-form.select name="card_columns" label="Kartu per baris (layar lebar)" :options="['2' => '2 kartu', '3' => '3 kartu', '4' => '4 kartu', '6' => '6 kartu']" :value="(string) $block->cardSetting('columns')" col="col-md-6" required />
                                <x-form.select name="card_shape" label="Bentuk gambar kartu" :options="\App\Models\Block::CARD_SHAPES" :value="$block->cardSetting('shape')" col="col-md-6" required />
                                <x-form.input name="card_button" label="Teks tautan di kartu" :value="$block->cardSetting('button')" col="col-md-6" placeholder="Lihat detail" />
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="w-100">
                                        <x-form.toggle name="card_excerpt" label="Tampilkan ringkasan di kartu" :checked="(bool) $block->cardSetting('excerpt')" />
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-light border small mt-3 mb-0 d-flex gap-2">
                                <i class="bi bi-info-circle text-primary"></i>
                                <div>
                                    @if ($editing)
                                        Isi kartu (nama, foto, kategori, tahun, isi detail) diubah lewat tombol <strong>Kelola kartu</strong>.
                                    @else
                                        Setelah blok ini disimpan, Anda langsung diarahkan untuk menambah kartu pertama.
                                    @endif
                                    Pencarian, filter, dan urutan untuk pengunjung diatur di bagian <strong>Fitur bantuan pengunjung</strong> pada halaman ini.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                @php
                    // Pilihan tampilan blok yang sedang aktif (ikut nilai lama bila formulir gagal disimpan)
                    $previous = $previous ?? null;   // blok sebelumnya yang tampil di website
                    $lpWidth = (string) old('width', $block->widthKey());
                    $lpPlace = (string) old('placement', $block->placement ?: 'left');
                    $lpPair = (string) old('pairing', $block->pairingKey());
                    $lpStyle = (string) old('style', $block->style ?: 'plain');
                    $lpAlign = (string) old('align', $block->contentAlign());
                    $lpTitle = (string) old('title_align', $block->title_align ?: 'ikut');
                    $styleIcons = ['plain' => 'bi-square', 'card' => 'bi-card-heading', 'accent' => 'bi-square-fill'];
                @endphp
                <div class="card dash-card mb-4" data-block-layout>
                    <div class="card-header">
                        <h2 class="dash-card__title"><i class="bi bi-layout-text-window-reverse me-1"></i> Tampilan blok</h2>
                        <small class="text-muted-d">Pratinjau di bawah mengikuti pilihan Anda.</small>
                    </div>
                    <div class="card-body">
                        {{-- Pratinjau kecil letak blok di halaman website (bersama blok sebelumnya bila ada) --}}
                        <div class="layout-preview" aria-hidden="true">
                            <div class="layout-preview__page">
                                <div class="layout-preview__line" data-lp-line="1">
                                    @if ($previous)
                                        <div class="layout-preview__cell" data-lp-prev-cell data-w="{{ $previous->widthKey() }}" data-place="{{ $previous->placementKey() }}">
                                            <div class="layout-preview__block layout-preview__block--prev">
                                                <span class="layout-preview__title">{{ \Illuminate\Support\Str::limit($previous->title ?: $previous->type_label, 30) }}</span>
                                                <span class="layout-preview__text"><i></i><i></i><i></i></span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="layout-preview__cell" data-lp-cell>
                                        <div class="layout-preview__block" data-lp-block>
                                            <span class="layout-preview__title" data-lp-title>Judul blok</span>
                                            <span class="layout-preview__text"><i></i><i></i><i></i><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="layout-preview__line" data-lp-line="2" hidden></div>
                            </div>
                            @if ($previous)
                                <div class="layout-preview__legend"><span><i class="is-prev"></i> Blok sebelumnya</span><span><i></i> Blok ini</span></div>
                            @endif
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="f_width" class="form-label">Lebar</label>
                                <select name="width" id="f_width" class="form-select @error('width') is-invalid @enderror">
                                    @foreach (\App\Models\Block::WIDTHS as $key => $w)
                                        <option value="{{ $key }}" @selected($lpWidth === (string) $key)>{{ $w['label'] }}{{ $key === 12 ? ' (selebar halaman)' : ' ('.$w['short'].' halaman)' }}</option>
                                    @endforeach
                                </select>
                                @error('width')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Dua blok setengah tampil berdampingan. Di layar HP, blok selalu tampil penuh.</div>
                            </div>

                            <div class="col-12 {{ $previous ? '' : 'is-off' }}" data-lp-pairing>
                                <label class="form-label">Susunan dengan blok sebelumnya</label>
                                <div class="seg-choice seg-choice--4" role="radiogroup" aria-label="Susunan dengan blok sebelumnya">
                                    @foreach (\App\Models\Block::PAIRINGS as $key => $o)
                                        <input type="radio" class="btn-check" name="pairing" id="pr_{{ $key }}" value="{{ $key }}" @checked($lpPair === $key)>
                                        <label class="seg-choice__item" for="pr_{{ $key }}" title="{{ $o['desc'] }}"><i class="bi {{ $o['icon'] }}"></i><span>{{ $o['label'] }}</span></label>
                                    @endforeach
                                </div>
                                @error('pairing')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                @if ($previous)
                                    <div class="form-text pair-prev">
                                        <i class="bi bi-link-45deg"></i> Blok sebelumnya: <strong>{{ \Illuminate\Support\Str::limit($previous->title ?: $previous->type_label, 40) }}</strong>
                                        <span class="text-muted-d">({{ $previous->type_label }} · {{ $previous->layoutLabel() }})</span>
                                    </div>
                                    <div class="form-text" data-pair-hint="auto">Mengikuti lebar &amp; posisi. Blok Kiri yang lebarnya muat otomatis tampil berdampingan.</div>
                                    <div class="form-text" data-pair-hint="right" hidden><strong>Bersebelahan:</strong> blok ini di kanan blok sebelumnya. Bila total lebar keduanya lebih dari Penuh, keduanya dibuat sama lebar.</div>
                                    <div class="form-text" data-pair-hint="left" hidden><strong>Bersebelahan:</strong> blok ini di depan (kiri) blok sebelumnya. Bila total lebar keduanya lebih dari Penuh, keduanya dibuat sama lebar.</div>
                                    <div class="form-text" data-pair-hint="below" hidden><strong>Atas-bawah:</strong> blok ini selalu mulai di baris baru, di bawah blok sebelumnya.</div>
                                @else
                                    <div class="form-text"><i class="bi bi-info-circle"></i> Ini blok pertama yang tampil di halaman, jadi belum ada blok sebelumnya. Pilihan ini berlaku mulai blok kedua.</div>
                                @endif
                            </div>

                            <div class="col-12" data-lp-placement>
                                <label class="form-label">Posisi blok</label>
                                <div class="seg-choice" role="radiogroup" aria-label="Posisi blok">
                                    @foreach (\App\Models\Block::PLACEMENTS as $key => $o)
                                        <input type="radio" class="btn-check" name="placement" id="pl_{{ $key }}" value="{{ $key }}" @checked($lpPlace === $key)>
                                        <label class="seg-choice__item" for="pl_{{ $key }}"><i class="bi {{ $o['icon'] }}"></i><span>{{ $o['label'] }}</span></label>
                                    @endforeach
                                </div>
                                <div class="form-text seg-note seg-note--on">Kiri: blok-blok Kiri yang lebarnya muat tampil berdampingan. Tengah &amp; Kanan: blok tampil di barisnya sendiri.</div>
                                <div class="form-text seg-note seg-note--off"><i class="bi bi-info-circle"></i> Pilih lebar selain <strong>Penuh</strong> agar posisi blok berlaku.</div>
                                <div class="form-text seg-note seg-note--pair"><i class="bi bi-info-circle"></i> Saat bersebelahan, letak kedua blok mengikuti posisi blok sebelumnya.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Gaya</label>
                                <div class="seg-choice" role="radiogroup" aria-label="Gaya blok">
                                    @foreach (\App\Models\Block::STYLES as $key => $label)
                                        <input type="radio" class="btn-check" name="style" id="st_{{ $key }}" value="{{ $key }}" @checked($lpStyle === $key)>
                                        <label class="seg-choice__item" for="st_{{ $key }}" title="{{ $label }}"><i class="bi {{ $styleIcons[$key] }}"></i><span>{{ \Illuminate\Support\Str::before($label, ' (') }}</span></label>
                                    @endforeach
                                </div>
                                @error('style')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Perataan judul</label>
                                <div class="seg-choice seg-choice--4" role="radiogroup" aria-label="Perataan judul">
                                    @foreach (\App\Models\Block::TITLE_ALIGNS as $key => $o)
                                        <input type="radio" class="btn-check" name="title_align" id="ta_{{ $key }}" value="{{ $key }}" @checked($lpTitle === $key)>
                                        <label class="seg-choice__item" for="ta_{{ $key }}"><i class="bi {{ $o['icon'] }}"></i><span>{{ $o['label'] }}</span></label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Perataan isi / paragraf</label>
                                <div class="seg-choice seg-choice--4" role="radiogroup" aria-label="Perataan isi">
                                    @foreach (\App\Models\Block::ALIGNS as $key => $o)
                                        <input type="radio" class="btn-check" name="align" id="al_{{ $key }}" value="{{ $key }}" @checked($lpAlign === $key)>
                                        <label class="seg-choice__item" for="al_{{ $key }}" title="{{ $o['label'] }}"><i class="bi {{ $o['icon'] }}"></i><span>{{ $key === 'justify' ? 'Kiri-kanan' : ucfirst(\Illuminate\Support\Str::after($o['label'], 'Rata ')) }}</span></label>
                                    @endforeach
                                </div>
                                @error('align')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div class="form-text">Berlaku untuk seluruh isi blok. Perataan satu paragraf saja bisa diatur dari tombol perataan di editor.</div>
                            </div>

                            <x-form.toggle name="is_active" label="Tampilkan blok ini" :checked="$block->is_active" />
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> {{ $editing ? 'Simpan perubahan' : ($type === 'koleksi' ? 'Simpan & tambah kartu' : 'Tambahkan blok') }}</button>
                    <a href="{{ route('dashboard.studio.show', $menu) }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
