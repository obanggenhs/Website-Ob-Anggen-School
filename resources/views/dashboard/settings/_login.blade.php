{{--
    Pengaturan Website > Halaman Login
    Latar belakang halaman login bisa berupa Foto, Slide foto, atau Video.
--}}
@php
    $current = old('login_bg_type', setting('login_bg_type') ?: 'image');
    $overlay = old('login_overlay', setting('login_overlay') ?: 'sedang');
    $image = setting('login_bg_image');
    $video = setting('login_bg_video');
    $slides = \App\Support\LoginBackground::slides();
    $active = \App\Support\LoginBackground::media();
@endphp

<form method="POST" action="{{ route('dashboard.settings.update', 'login') }}" enctype="multipart/form-data" class="card dash-card" data-media-form>
    @csrf @method('PUT')

    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2><i class="bi {{ $tabInfo['icon'] }} me-1"></i> {{ $tabInfo['label'] }}</h2>
            <small class="text-muted-d">Latar belakang di sisi kiri halaman masuk dashboard (/login).</small>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge badge-soft"><i class="bi {{ \App\Support\LoginBackground::TYPES[$active['type']]['icon'] }}"></i> Sedang tampil: {{ \App\Support\LoginBackground::TYPES[$active['type']]['label'] }}</span>
            <a href="{{ route('dashboard.settings.login-preview') }}" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-eye"></i> Lihat pratinjau</a>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-4">
            {{-- Kiri: pilihan jenis latar & isinya --}}
            <div class="col-xl-7">
                <label class="form-label">Jenis latar</label>
                <div class="media-switch media-switch--3 mb-3" role="radiogroup" aria-label="Jenis latar halaman login">
                    @foreach (\App\Support\LoginBackground::TYPES as $key => $type)
                        <input type="radio" class="btn-check" name="login_bg_type" id="lb_{{ $key }}" value="{{ $key }}" data-media-type @checked($current === $key)>
                        <label class="media-switch__item" for="lb_{{ $key }}"><i class="bi {{ $type['icon'] }}"></i> {{ $type['label'] }}</label>
                    @endforeach
                </div>

                {{-- 1. Foto --}}
                <div class="media-panel" data-media-panel="image">
                    <div class="media-preview mb-2">
                        <img src="{{ $image ? media_url($image) : \App\Support\LoginBackground::fallbackImage() }}" alt="" id="loginImagePreview">
                        @unless ($image)<span class="media-preview__tag">Foto bawaan (latar Beranda)</span>@endunless
                    </div>
                    <label class="form-label" for="f_login_bg_image">Unggah foto</label>
                    <input type="file" name="login_bg_image" id="f_login_bg_image" accept="image/*" class="form-control @error('login_bg_image') is-invalid @enderror" data-preview="loginImagePreview">
                    @error('login_bg_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">JPG / PNG / WEBP, tanpa batas ukuran. Disarankan foto tegak atau persegi (mis. 1200×1400 px). Foto ini juga dipakai sebagai sampul sementara saat video sedang dimuat.</div>
                    @if ($image)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="f_remove_image">
                            <label class="form-check-label small" for="f_remove_image">Hapus foto ini (kembali ke foto bawaan)</label>
                        </div>
                    @endif
                </div>

                {{-- 2. Slide foto --}}
                <div class="media-panel" data-media-panel="slider">
                    @if ($slides)
                        <div class="photo-grid mb-2" style="grid-template-columns: repeat(auto-fill, minmax(100px, 1fr))">
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
                    <label class="form-label" for="f_login_slides">Tambah foto slide (boleh pilih banyak sekaligus)</label>
                    <input type="file" name="slides[]" id="f_login_slides" accept="image/*" multiple class="form-control @error('slides') is-invalid @enderror @error('slides.*') is-invalid @enderror" data-slides-input="loginSlidesNew">
                    @error('slides')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('slides.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Minimal 2 foto, tanpa batas jumlah dan ukuran. Foto bergantian otomatis setiap 5 detik.</div>
                    <div class="photo-grid mt-2" id="loginSlidesNew" style="grid-template-columns: repeat(auto-fill, minmax(84px, 1fr))"></div>
                </div>

                {{-- 3. Video --}}
                <div class="media-panel" data-media-panel="video">
                    <div class="media-preview mb-2">
                        <video id="loginVideoPreview" muted loop playsinline preload="metadata" @if ($video) src="{{ media_url($video) }}" @endif
                               poster="{{ $image ? media_url($image) : '' }}" class="{{ $video ? '' : 'd-none' }}"></video>
                        <div class="media-preview__empty {{ $video ? 'd-none' : '' }}" data-video-empty><i class="bi bi-camera-video"></i>Belum ada video</div>
                    </div>
                    <label class="form-label" for="f_login_bg_video">Unggah video dari komputer</label>
                    <input type="file" name="login_bg_video" id="f_login_bg_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" class="form-control @error('login_bg_video') is-invalid @enderror" data-video-input="loginVideoPreview">
                    @error('login_bg_video')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">MP4 / WEBM / MOV, tanpa batas ukuran. Diputar otomatis tanpa suara dan berulang. Pilih video pendek (10–30 detik) agar halaman login tetap ringan.</div>
                    @if ($video)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="f_remove_video">
                            <label class="form-check-label small" for="f_remove_video">Hapus video ini</label>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kanan: gambaran tampilan & lapisan warna --}}
            <div class="col-xl-5">
                <label class="form-label">Gambaran halaman login saat ini</label>
                <div class="login-mock login-mock--{{ $active['overlay'] }}" data-login-mock>
                    <div class="login-mock__visual" style="background-image: url('{{ $active['poster'] }}')">
                        @if ($active['type'] === 'video')
                            <video src="{{ $active['src'] }}" poster="{{ $active['poster'] }}" muted loop playsinline autoplay preload="metadata"></video>
                        @endif
                        <div class="login-mock__brand">
                            <img src="{{ media_url(setting('logo_main')) }}" alt="">
                            <span>{{ setting('site_name') }}</span>
                        </div>
                        @if ($active['type'] === 'slider')
                            <span class="login-mock__tag"><i class="bi bi-collection-play"></i> {{ count($active['slides']) }} slide</span>
                        @elseif ($active['type'] === 'video')
                            <span class="login-mock__tag"><i class="bi bi-play-fill"></i> Video</span>
                        @endif
                    </div>
                    <div class="login-mock__form">
                        <span class="login-mock__line login-mock__line--title"></span>
                        <span class="login-mock__line"></span>
                        <span class="login-mock__input"></span>
                        <span class="login-mock__input"></span>
                        <span class="login-mock__button"></span>
                    </div>
                </div>
                <div class="form-text mb-3">Gambaran ini menampilkan latar yang sudah tersimpan. Klik <strong>Lihat pratinjau</strong> untuk membuka halaman login sungguhan.</div>

                <label class="form-label" for="f_login_overlay">Lapisan warna di atas latar</label>
                <select name="login_overlay" id="f_login_overlay" class="form-select @error('login_overlay') is-invalid @enderror" data-overlay-select>
                    @foreach (\App\Support\LoginBackground::OVERLAYS as $key => $label)
                        <option value="{{ $key }}" @selected($overlay === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="form-text">Lapisan memakai warna utama sekolah. Pilih <em>Pekat</em> bila foto/video sangat terang agar tulisan tetap mudah dibaca.</div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a href="{{ route('dashboard.settings.login-preview') }}" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Buka pratinjau halaman login</a>
        <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan latar login</button>
    </div>
</form>

@push('scripts')
    <script>
        // Gambaran langsung berubah saat pilihan lapisan warna diganti
        (function () {
            var select = document.querySelector('[data-overlay-select]');
            var mock = document.querySelector('[data-login-mock]');
            if (!select || !mock) return;
            select.addEventListener('change', function () {
                mock.className = mock.className.replace(/login-mock--\w+/, 'login-mock--' + select.value);
            });
        })();
    </script>
@endpush
