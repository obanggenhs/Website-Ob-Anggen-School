@php
    // Grup dropdown tanpa submenu tidak ditampilkan
    $visibleMenus = $navMenus->reject(fn ($m) => $m->isGroup() && $m->children->isEmpty())->values();
    $menusNormal = $visibleMenus->reject(fn ($m) => $m->is_highlight)->values();
    $menusHighlight = $visibleMenus->filter(fn ($m) => $m->is_highlight)->values();
    $phone = setting('contact_phone');
    $email = setting('contact_email');
@endphp

<header class="oa-header" id="oaHeader">
    <div class="oa-topbar d-none d-lg-block">
        <div class="container oa-container d-flex justify-content-between align-items-center gap-3">
            <div class="oa-topbar__info">
                @if ($phone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="oa-topbar__item"><i class="bi bi-telephone"></i> {{ $phone }}</a>
                @endif
                @if ($email)
                    <a href="mailto:{{ $email }}" class="oa-topbar__item"><i class="bi bi-envelope"></i> {{ $email }}</a>
                @endif
                <span class="oa-topbar__item"><i class="bi bi-geo-alt"></i> {{ \Illuminate\Support\Str::limit(setting('contact_address'), 48) }}</span>
            </div>
            @include('web.partials.social', ['class' => 'oa-social'])
        </div>
    </div>

    <nav class="navbar navbar-expand-xl oa-navbar" aria-label="Navigasi utama">
        <div class="container oa-container oa-navbar__inner">
            {{-- Logo & nama sekolah: ukurannya tetap, tidak pernah terdesak oleh menu --}}
            <a class="navbar-brand oa-brand" href="{{ route('home') }}" title="{{ setting('site_name') }}">
                <span class="oa-brand__logos">
                    @foreach (['logo_sd' => 'SD', 'logo_smp' => 'SMP', 'logo_sma' => 'SMA'] as $key => $alt)
                        <img src="{{ media_url(setting($key)) }}" alt="Logo {{ $alt }}" width="30" height="30">
                    @endforeach
                </span>
                <span class="oa-brand__text">
                    <strong>{{ setting('site_name') }}</strong>
                    <small>{{ setting('site_tagline') }}</small>
                </span>
            </a>

            <button class="navbar-toggler oa-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#oaNav" aria-controls="oaNav" aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>

            <div class="offcanvas offcanvas-end oa-offcanvas" tabindex="-1" id="oaNav" aria-labelledby="oaNavLabel">
                <div class="offcanvas-header">
                    <a href="{{ route('home') }}" class="oa-offcanvas__brand" id="oaNavLabel">
                        <img src="{{ media_url(setting('logo_main')) }}" alt="" width="34" height="34">
                        <strong>{{ setting('site_name') }}</strong>
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
                </div>

                <div class="offcanvas-body oa-navbar__body">
                    {{--
                        Menu utama. Di layar lebar, menu yang tidak muat dipindah otomatis
                        ke dropdown "Lainnya" (diatur main.js), sehingga navbar selalu satu baris rapi
                        berapa pun jumlah menu yang ditambahkan Master Admin.
                    --}}
                    <ul class="navbar-nav oa-nav-main" data-nav-main>
                        @foreach ($menusNormal as $i => $item)
                            @include('web.partials.nav-item', ['menu' => $item, 'index' => $i])
                        @endforeach

                        <li class="nav-item dropdown oa-nav-more" data-nav-more hidden>
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-grid-3x3-gap"></i><span class="oa-nav-label">Lainnya</span>
                            </a>
                            <ul class="dropdown-menu oa-nav-more__menu">
                                @foreach ($menusNormal as $i => $item)
                                    @if ($item->isGroup())
                                        <li data-more="{{ $i }}" hidden><h6 class="dropdown-header"><i class="bi {{ $item->icon ?: $item->type_icon }}"></i> {{ $item->title }}</h6></li>
                                        @foreach ($item->children as $child)
                                            <li data-more="{{ $i }}" hidden>
                                                <a class="dropdown-item oa-nav-more__child {{ $child->isCurrent() ? 'active' : '' }}" href="{{ $child->link }}" target="{{ $child->target }}">
                                                    <i class="bi {{ $child->type_icon }}"></i> {{ $child->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li data-more="{{ $i }}" hidden>
                                            <a class="dropdown-item {{ $item->isCurrent() ? 'active' : '' }}" href="{{ $item->link }}" target="{{ $item->target }}">
                                                <i class="bi {{ $item->icon ?: $item->type_icon }}"></i> {{ $item->title }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    {{-- Tombol menonjol (mis. Pendaftaran) selalu terlihat di ujung kanan --}}
                    @if ($menusHighlight->isNotEmpty())
                        <ul class="navbar-nav oa-nav-actions">
                            @foreach ($menusHighlight as $item)
                                @include('web.partials.nav-item', ['menu' => $item, 'cta' => true])
                            @endforeach
                        </ul>
                    @endif

                    <div class="oa-offcanvas__contact d-xl-none">
                        @if ($phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}"><i class="bi bi-telephone me-2"></i>{{ $phone }}</a>@endif
                        @if ($email)<a href="mailto:{{ $email }}"><i class="bi bi-envelope me-2"></i>{{ $email }}</a>@endif
                        @include('web.partials.social', ['class' => 'oa-social'])
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>