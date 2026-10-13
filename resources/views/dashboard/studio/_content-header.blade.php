{{--
    Header halaman & teks pembuka (semua menu yang punya halaman) + latar header.
    Untuk Beranda, bagian ini menjadi "Latar belakang hero Beranda".
    Latar header bisa: Foto · Video lokal · YouTube · Slide foto
--}}
@php
    $isHome = $menu->type === 'beranda';
    $current = old('banner_type', $menu->banner_type ?: 'image');
    $slides = array_values((array) $menu->banner_slides);
    $ytId = youtube_id(old('banner_youtube', $menu->banner_youtube));
    $active = $menu->headerMedia();
    $defaultImage = media_url($isHome ? setting('hero_image') : setting('page_header_image'));

    // Lapisan warna: "ikut" = mengikuti pengaturan semua halaman
    $overlays = \App\Models\Menu::OVERLAYS;
    $globalOverlay = \App\Models\Menu::defaultOverlay();
    $overlayValue = old('banner_overlay', $menu->banner_overlay ?: 'ikut');
    $previewImage = $active['poster'] ?? $defaultImage;
@endphp

<div class="card dash-card mb-4" id="header-halaman">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title">{{ $isHome ? 'Latar belakang hero Beranda' : 'Header halaman & teks pembuka' }}</h2>
            <small class="text-muted-d">
                {{ $isHome ? 'Bagian paling atas Beranda di website.' : 'Tampil di bagian atas halaman "'.$menu->title.'" di website.' }}
            </small>
        </div>
        <span class="badge badge-soft">
            <i class="bi {{ $active ? \App\Models\Menu::BANNER_TYPES[$active['type']]['icon'] : 'bi-image' }}"></i>
            Sedang tampil: {{ $active ? \App\Models\Menu::BANNER_TYPES[$active['type']]['label'] : 'Gambar bawaan' }}
        </span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('dashboard.studio.content', $menu) }}" enctype="multipart/form-data" data-media-form>
            @csrf @method('PUT')
            <div class="row g-3">
                @unless ($isHome)
                    <x-form.input name="subtitle" label="Sub judul di bawah nama halaman" :value="$menu->subtitle" placeholder="mis. Kabar terbaru dari kampus Ob Anggen" />
                @endunless

                <div class="col-12">
                    <label class="form-label">Latar header</label>

                    {{-- Pilihan jenis latar --}}
                    <div class="media-switch mb-3" role="radiogroup" aria-label="Jenis latar header">
                        @foreach (\App\Models\Menu::BANNER_TYPES as $key => $type)
                            <input type="radio" class="btn-check" name="banner_type" id="bt_{{ $key }}" value="{{ $key }}" @checked($current === $key)>
                            <label class="media-switch__item" for="bt_{{ $key }}"><i class="bi {{ $type['icon'] }}"></i> {{ $type['label'] }}</label>
                        @endforeach
                    </div>

                    {{-- 1. Foto --}}
                    <div class="media-panel" data-media-panel="image">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-5">
                                <div class="media-preview">
                                    <img src="{{ $menu->banner ? media_url($menu->banner) : $defaultImage }}" alt="" id="bannerPreview">
                                    @unless ($menu->banner)<span class="media-preview__tag">Gambar bawaan</span>@endunless
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label" for="f_banner">Unggah foto</label>
                                <input type="file" name="banner" id="f_banner" accept="image/*" class="form-control @error('banner') is-invalid @enderror" data-preview="bannerPreview">
                                @error('banner')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">JPG / PNG / WEBP, tanpa batas ukuran. Ukuran ideal 1600×500 px. Foto ini juga dipakai sebagai sampul sementara saat video sedang dimuat.</div>
                                @if ($menu->banner)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_banner" value="1" id="f_remove_banner">
                                        <label class="form-check-label small" for="f_remove_banner">Hapus foto ini (kembali ke gambar bawaan)</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 2. Video lokal --}}
                    <div class="media-panel" data-media-panel="video">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-5">
                                <div class="media-preview">
                                    <video id="videoPreview" muted loop playsinline preload="metadata" @if ($menu->banner_video) src="{{ media_url($menu->banner_video) }}" @endif
                                           poster="{{ $menu->banner ? media_url($menu->banner) : '' }}" class="{{ $menu->banner_video ? '' : 'd-none' }}"></video>
                                    <div class="media-preview__empty {{ $menu->banner_video ? 'd-none' : '' }}" data-video-empty><i class="bi bi-camera-video"></i>Belum ada video</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label" for="f_banner_video">Unggah video dari komputer</label>
                                <input type="file" name="banner_video" id="f_banner_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" class="form-control @error('banner_video') is-invalid @enderror" data-video-input="videoPreview">
                                @error('banner_video')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">MP4 / WEBM / MOV, tanpa batas ukuran. Video diputar otomatis tanpa suara dan berulang. Pilih video pendek (10–30 detik) agar halaman tetap ringan.</div>
                                @if ($menu->banner_video)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="f_remove_video">
                                        <label class="form-check-label small" for="f_remove_video">Hapus video ini</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 3. YouTube --}}
                    <div class="media-panel" data-media-panel="youtube">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-5">
                                <div class="media-preview">
                                    <img src="{{ $ytId ? 'https://img.youtube.com/vi/'.$ytId.'/hqdefault.jpg' : '' }}" alt="" id="ytPreview" class="{{ $ytId ? '' : 'd-none' }}">
                                    <span class="media-preview__play {{ $ytId ? '' : 'd-none' }}" data-yt-play><i class="bi bi-youtube"></i></span>
                                    <div class="media-preview__empty {{ $ytId ? 'd-none' : '' }}" data-yt-empty><i class="bi bi-youtube"></i>Tempel link YouTube</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label" for="f_banner_youtube">Link YouTube</label>
                                <input type="url" name="banner_youtube" id="f_banner_youtube" value="{{ old('banner_youtube', $menu->banner_youtube) }}"
                                       class="form-control @error('banner_youtube') is-invalid @enderror" placeholder="https://www.youtube.com/watch?v=..." data-yt-input="ytPreview">
                                @error('banner_youtube')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Link biasa, youtu.be, atau Shorts. Video diputar otomatis tanpa suara dan berulang di latar header.</div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Slide foto --}}
                    <div class="media-panel" data-media-panel="slider">
                        @if ($slides)
                            <div class="photo-grid mb-3" style="grid-template-columns: repeat(auto-fill, minmax(110px, 1fr))">
                                @foreach ($slides as $i => $slide)
                                    <label class="photo-grid__item" title="Centang untuk menghapus">
                                        <img src="{{ media_url($slide) }}" alt="">
                                        <span class="photo-grid__badge badge text-bg-dark">{{ $i + 1 }}</span>
                                        <span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_slides[]" value="{{ $i }}"></span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="form-text mb-2">{{ count($slides) }} slide tersimpan. Centang slide yang ingin dihapus.</div>
                        @endif
                        <label class="form-label" for="f_slides">Tambah foto slide (boleh pilih banyak sekaligus)</label>
                        <input type="file" name="slides[]" id="f_slides" accept="image/*" multiple class="form-control @error('slides') is-invalid @enderror @error('slides.*') is-invalid @enderror" data-slides-input="slidesNew">
                        @error('slides')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('slides.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Minimal 2 foto, tanpa batas jumlah dan ukuran. Foto bergantian otomatis setiap 5 detik.</div>
                        <div class="photo-grid mt-2" id="slidesNew" style="grid-template-columns: repeat(auto-fill, minmax(90px, 1fr))"></div>
                    </div>
                </div>

                {{-- Lapisan warna di atas latar + pratinjau langsung --}}
                <div class="col-12">
                    <div class="overlay-picker" data-global="{{ $globalOverlay }}">
                        <label class="form-label mb-0">Lapisan warna di atas latar</label>
                        <div class="form-text mt-0 mb-2">Atur seberapa gelap warna di atas foto/video. Tulisan selalu diberi bayangan halus sehingga tetap jelas dibaca di semua pilihan.</div>
                        <div class="row g-3 align-items-stretch">
                            <div class="col-lg-6">
                                <div class="overlay-options" role="radiogroup" aria-label="Lapisan warna di atas latar">
                                    <input type="radio" class="btn-check" name="banner_overlay" id="ov_ikut" value="ikut" @checked($overlayValue === 'ikut')>
                                    <label class="overlay-option" for="ov_ikut">
                                        <span class="overlay-option__swatch overlay-option__swatch--{{ $globalOverlay }}" aria-hidden="true"></span>
                                        <span class="min-w-0"><strong>Ikuti semua halaman</strong><small>Saat ini: {{ $overlays[$globalOverlay]['label'] }}</small></span>
                                    </label>
                                    @foreach ($overlays as $key => $o)
                                        <input type="radio" class="btn-check" name="banner_overlay" id="ov_{{ $key }}" value="{{ $key }}" @checked($overlayValue === $key)>
                                        <label class="overlay-option" for="ov_{{ $key }}">
                                            <span class="overlay-option__swatch overlay-option__swatch--{{ $key }}" aria-hidden="true"></span>
                                            <span class="min-w-0"><strong>{{ $o['label'] }}</strong><small>{{ $o['desc'] }}</small></span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('banner_overlay')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                @if (auth()->user()->isMaster())
                                    <div class="form-check mt-2 overlay-all">
                                        <input class="form-check-input" type="checkbox" name="overlay_all" value="1" id="f_overlay_all">
                                        <label class="form-check-label small" for="f_overlay_all">
                                            Pakai pilihan ini untuk <strong>semua halaman</strong>, termasuk Beranda
                                            <span class="d-block text-muted-d">Pilihan yang sudah diatur sendiri di halaman lain ikut diganti.</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="overlay-preview {{ $isHome ? 'overlay-preview--hero' : '' }}"
                                     style="--pv-img: url('{{ $previewImage }}'); --pv-dark: {{ setting('color_primary_dark') }}; --pv-primary: {{ setting('color_primary') }}; --pv-accent: {{ setting('color_accent') }}">
                                    <span class="overlay-preview__tag">Pratinjau</span>
                                    @if ($isHome)
                                        <span class="overlay-preview__eyebrow">{{ setting('site_tagline') }}</span>
                                        <strong class="overlay-preview__title">{{ setting('hero_title') }}</strong>
                                        <span class="overlay-preview__sub">{{ \Illuminate\Support\Str::limit((string) setting('hero_subtitle'), 110) }}</span>
                                    @else
                                        <span class="overlay-preview__crumb"><i class="bi bi-house-door"></i> Beranda{{ $menu->parent ? ' / '.$menu->parent->title : '' }} / {{ $menu->title }}</span>
                                        <strong class="overlay-preview__title">{{ $menu->title }}</strong>
                                        @if ($menu->subtitle)<span class="overlay-preview__sub">{{ $menu->subtitle }}</span>@endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @unless ($isHome)
                    <x-form.editor name="intro" label="Teks pembuka (opsional)" :value="$menu->intro" :height="140"
                        :help="$menu->isPage() ? 'Tampil sebelum blok pertama.' : 'Tampil di atas daftar '.\Illuminate\Support\Str::lower($menu->type_label).'.'" />
                @endunless
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top pt-3 mt-3">
                <a href="{{ $menu->link }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat hasilnya di website</a>
                <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> {{ $isHome ? 'Simpan latar hero' : 'Simpan header' }}</button>
            </div>
        </form>
    </div>
</div>