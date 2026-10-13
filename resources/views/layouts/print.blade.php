<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #222; padding: 24px; }
        .print-head { display: flex; align-items: center; gap: 14px; border-bottom: 3px double #7A2B25; padding-bottom: 12px; margin-bottom: 18px; }
        .print-head img { width: 60px; height: 60px; }
        .print-head h1 { font-size: 18px; margin: 0; color: #7A2B25; }
        .print-head p { margin: 0; font-size: 12px; color: #555; }
        table.data td { padding: 5px 8px; vertical-align: top; border-bottom: 1px solid #eee; }
        table.data td:first-child { width: 35%; color: #666; }
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="print-head">
        <img src="{{ media_url(setting('logo_main')) }}" alt="">
        <div>
            <h1>{{ setting('site_name') }}</h1>
            <p>{{ setting('contact_address') }}</p>
            <p>{{ setting('contact_phone') }} · {{ setting('contact_email') }}</p>
        </div>
    </div>
    @yield('content')
    <div class="no-print mt-4"><button onclick="window.print()" class="btn btn-sm btn-dark">Cetak</button> <button onclick="window.close()" class="btn btn-sm btn-outline-secondary">Tutup</button></div>
</body>
</html>
