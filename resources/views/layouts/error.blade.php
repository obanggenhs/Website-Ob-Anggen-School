<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · {{ rescue(fn () => setting('site_name'), 'Ob Anggen School', false) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/lexend/lexend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/nunito-sans/nunito-sans.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; background: #FBF5F1; font-family: 'Nunito Sans', sans-serif; color: #2B2422; padding: 1.5rem; }
        .err { max-width: 480px; text-align: center; }
        .err__code { font-family: 'Lexend', sans-serif; font-size: 5.5rem; font-weight: 700; line-height: 1; color: #7A2B25; }
        .err h1 { font-family: 'Lexend', sans-serif; font-size: 1.35rem; margin: 1rem 0 .5rem; }
        .err p { color: #7a6d69; }
        .btn-oa { background: #7A2B25; color: #fff; border-radius: 999px; padding: .55rem 1.3rem; font-weight: 700; }
        .btn-oa:hover { background: #5C1F1A; color: #fff; }
    </style>
</head>
<body>
    <div class="err">
        <div class="err__code">@yield('code')</div>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <div class="d-flex gap-2 justify-content-center mt-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill">Kembali</a>
            <a href="{{ url('/') }}" class="btn btn-oa"><i class="bi bi-house-door"></i> Beranda</a>
        </div>
    </div>
</body>
</html>
