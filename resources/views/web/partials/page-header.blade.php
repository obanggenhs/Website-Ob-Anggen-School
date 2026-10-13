@php
    $menu = $menu ?? null;
    $override = trim(View::getSection('page_banner') ?? '');

    // Halaman detail (mis. berita, album) memakai fotonya sendiri; halaman lain memakai latar header menu
    $media = $override !== '' ? ['type' => 'image', 'src' => $override, 'poster' => $override] : $menu?->headerMedia();
    $banner = $media['poster'] ?? media_url(setting('page_header_image'));
    $subtitle = trim(View::getSection('page_subtitle') ?? '') ?: $menu?->subtitle;

    // Lapisan warna di atas latar (tipis / sedang / pekat), diatur di Studio per halaman atau untuk semua halaman
    $overlay = $menu?->headerOverlay() ?? \App\Models\Menu::defaultOverlay();
@endphp
<header class="oa-pagehead oa-overlay--{{ $overlay }} {{ $media && $media['type'] !== 'image' ? 'has-media' : '' }}" style="--oa-pagehead-img: url('{{ $banner }}')">
    @include('web.partials.header-media', ['media' => $media])

    <div class="container oa-container position-relative" style="z-index:3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb oa-breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door"></i> Beranda</a></li>
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    @if ($menu?->parent)
                        <li class="breadcrumb-item">{{ $menu->parent->title }}</li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">@yield('page_title', $menu?->title)</li>
                @endif
            </ol>
        </nav>
        <h1 class="oa-pagehead__title">@yield('page_title', $menu?->title)</h1>
        @if ($subtitle)
            <p class="oa-pagehead__sub">{{ $subtitle }}</p>
        @endif
        @if ($menu && ! $menu->isVisible() && auth()->check())
            <span class="oa-preview-flag"><i class="bi bi-eye-slash"></i> Pratinjau admin: menu ini sedang disembunyikan</span>
        @endif
    </div>
    @include('web.partials.ridge')
</header>