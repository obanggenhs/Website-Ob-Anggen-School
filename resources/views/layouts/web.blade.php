<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') | @endif{{ setting('site_name') }}</title>
    <meta name="description" content="@yield('description', setting('meta_description'))">
    <meta property="og:title" content="@yield('title', setting('site_name'))">
    <meta property="og:description" content="@yield('description', setting('meta_description'))">
    <link rel="icon" href="{{ media_url(setting('favicon')) }}">
    <script>
        // Tandai bahwa konten akan dianimasikan; cadangan 2,5 detik bila skrip gagal dimuat
        document.documentElement.classList.add('oa-anim-pending');
        setTimeout(function () { document.documentElement.classList.remove('oa-anim-pending'); }, 2500);
    </script>

    @php
        $fontHeading = setting('font_heading');
        $fontBody = setting('font_body');
        $localFonts = ['Lexend' => 'lexend/lexend.css', 'Nunito Sans' => 'nunito-sans/nunito-sans.css'];
    @endphp
    @foreach (array_unique([$fontHeading, $fontBody]) as $font)
        @if (isset($localFonts[$font]))
            <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/'.$localFonts[$font]) }}">
        @else
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ urlencode($font) }}:wght@400;600;700&display=swap">
        @endif
    @endforeach

    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        :root {
            --oa-primary: {{ setting('color_primary') }};
            --oa-primary-dark: {{ setting('color_primary_dark') }};
            --oa-accent: {{ setting('color_accent') }};
            --oa-secondary: {{ setting('color_secondary') }};
            --oa-bg: {{ setting('color_bg') }};
            --oa-footer: {{ setting('color_footer') }};
            --oa-font-heading: '{{ $fontHeading }}', system-ui, sans-serif;
            --oa-font-body: '{{ $fontBody }}', system-ui, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="oa-body @yield('body_class')">

    @include('web.partials.navbar')

    <main id="main">
        @yield('content')
    </main>

    @include('web.partials.footer')

    <a href="#" class="oa-backtop" id="oaBackTop" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></a>

    @if (session('success') || session('error'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast align-items-center border-0 text-bg-{{ session('success') ? 'success' : 'danger' }}" role="alert" data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') ?? session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
                </div>
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>