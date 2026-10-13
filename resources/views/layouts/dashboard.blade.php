{{--
    Layout dashboard tunggal untuk Master Admin, Admin Publikasi, dan Admin Pendaftaran.
    Warna, sidebar, dan label panel berubah sesuai role (lihat App\Support\DashboardMenu).
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Unggah bertahap: file besar dikirim per potongan agar tidak terbentur batas php.ini --}}
    <meta name="upload-url" content="{{ route('dashboard.upload.chunk') }}">
    <meta name="upload-chunk-size" content="{{ \App\Support\ChunkUpload::chunkSize() }}">
    <title>@yield('title', 'Dashboard') | {{ $theme['label'] }} · {{ setting('site_name') }}</title>
    <link rel="icon" href="{{ media_url(setting('favicon')) }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/lexend/lexend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/nunito-sans/nunito-sans.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/summernote/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <style>:root { --dash-primary: {{ $theme['color'] }}; }</style>
    @stack('styles')
</head>
<body class="dash-body theme-{{ auth()->user()->role }}">

    @include('dashboard.partials.sidebar')
    <div class="dash-overlay" data-sidebar-toggle></div>

    <div class="dash-main">
        @include('dashboard.partials.topbar')

        <main class="dash-content">
            @hasSection('page_title')
                <div class="dash-pagehead">
                    <div>
                        @hasSection('breadcrumb')
                            <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-1">@yield('breadcrumb')</ol></nav>
                        @endif
                        <h1>@yield('page_title')</h1>
                        @hasSection('page_subtitle')<p>@yield('page_subtitle')</p>@endif
                    </div>
                    <div class="d-flex flex-wrap gap-2">@yield('actions')</div>
                </div>
            @endif

            @include('dashboard.partials.alerts')

            @yield('content')
        </main>

        <footer class="dash-footer">
            <span>&copy; {{ date('Y') }} {{ setting('site_name') }}</span>
            <span>{{ $theme['label'] }} · {{ $theme['panel'] }}</span>
        </footer>
    </div>

    {{-- Modal diletakkan langsung di <body> (di luar area yang dianimasikan)
         agar tidak tertutup lapisan gelap (backdrop) dan tetap bisa diklik --}}
    @stack('modals')

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="dashFlash"></div>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/summernote/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/summernote/summernote-id-ID.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sortablejs/Sortable.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
