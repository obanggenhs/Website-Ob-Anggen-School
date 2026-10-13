<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ setting('site_name') }}</title>
    <link rel="icon" href="{{ media_url(setting('favicon')) }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/lexend/lexend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/nunito-sans/nunito-sans.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
</head>
@php
    // Latar diatur di Dashboard > Pengaturan Website > Halaman Login (foto / slide foto / video)
    $bg = \App\Support\LoginBackground::media();
@endphp
<body class="auth-body" style="--dash-primary: {{ setting('color_primary') }}">
    <div class="auth-wrap">
        <div class="auth-visual auth-visual--{{ $bg['overlay'] }} {{ $bg['type'] !== 'image' ? 'has-media' : '' }}" style="background-image: url('{{ $bg['poster'] }}')">
            @if ($bg['type'] === 'video')
                <div class="auth-media" aria-hidden="true">
                    <video src="{{ $bg['src'] }}" poster="{{ $bg['poster'] }}" autoplay muted loop playsinline preload="auto" disablepictureinpicture tabindex="-1"></video>
                </div>
            @elseif ($bg['type'] === 'slider')
                <div class="auth-media" aria-hidden="true" data-auth-slider>
                    @foreach ($bg['slides'] as $i => $slide)
                        <div class="auth-media__slide {{ $i === 0 ? 'is-active' : '' }}" style="background-image: url('{{ $slide }}')"></div>
                    @endforeach
                    <div class="auth-media__dots">
                        @foreach ($bg['slides'] as $i => $slide)
                            <span class="{{ $i === 0 ? 'is-active' : '' }}"></span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="auth-visual__inner">
                <img src="{{ media_url(setting('logo_main')) }}" alt="" width="64" height="64" class="auth-visual__logo">
                <h1>{{ setting('site_name') }}</h1>
                <p>{{ setting('site_tagline') }}</p>
                <ul class="auth-roles">
                    <li><i class="bi bi-shield-lock"></i><span><strong>Master Admin</strong> mengatur struktur, isi website, dan akun.</span></li>
                    <li><i class="bi bi-broadcast"></i><span><strong>Admin Publikasi</strong> mengelola berita, galeri, kelulusan, dan kalender.</span></li>
                    <li><i class="bi bi-clipboard-check"></i><span><strong>Admin Pendaftaran</strong> memproses pendaftar sampai pengumuman.</span></li>
                </ul>
            </div>
        </div>
        <div class="auth-form">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Tombol lihat / sembunyikan kata sandi
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-toggle-password'));
                input.type = input.type === 'password' ? 'text' : 'password';
                btn.querySelector('i').className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
            });
        });

        // Latar halaman login: slide foto berganti setiap 5 detik, video diputar tanpa suara
        (function () {
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            var video = document.querySelector('.auth-media video');
            if (video) {
                video.muted = true;
                if (reduce) video.pause();
                else { var p = video.play(); if (p && p.catch) p.catch(function () {}); }
            }

            var slider = document.querySelector('[data-auth-slider]');
            if (!slider || reduce) return;
            var slides = slider.querySelectorAll('.auth-media__slide');
            var dots = slider.querySelectorAll('.auth-media__dots span');
            var index = 0;
            if (slides.length < 2) return;
            setInterval(function () {
                if (document.hidden) return;
                slides[index].classList.remove('is-active');
                dots[index].classList.remove('is-active');
                index = (index + 1) % slides.length;
                slides[index].style.animation = 'none';
                void slides[index].offsetWidth;   // mulai ulang efek zoom pelan
                slides[index].style.animation = '';
                slides[index].classList.add('is-active');
                dots[index].classList.add('is-active');
            }, 5000);
        })();
    </script>
</body>
</html>