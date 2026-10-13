<aside class="dash-sidebar" id="dashSidebar">
    <a href="{{ route('dashboard.home') }}" class="dash-brand">
        <img src="{{ media_url(setting('logo_main')) }}" alt="">
        <span><strong>{{ setting('site_name') }}</strong><small>Dashboard pengelola</small></span>
    </a>

    <div class="dash-role">
        <i class="bi {{ $theme['icon'] }}"></i>
        <span><strong>{{ $theme['label'] }}</strong><small>{{ $theme['panel'] }}</small></span>
    </div>

    <nav class="dash-nav" aria-label="Menu dashboard">
        @foreach ($sidebarGroups as $group)
            @if ($group['label'])
                <div class="dash-nav__label">{{ $group['label'] }}</div>
            @endif
            @foreach ($group['items'] as $item)
                <a href="{{ $item['url'] }}" class="dash-nav__link {{ $item['active'] ? 'active' : '' }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span class="min-w-0">
                        {{ $item['label'] }}
                        @if (! empty($item['hint']))<span class="dash-nav__hint">{{ $item['hint'] }}</span>@endif
                    </span>
                    @if ($item['badge'])<span class="badge rounded-pill text-bg-warning">{{ $item['badge'] }}</span>@endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="dash-sidebar__foot">Masuk sebagai {{ auth()->user()->name }}</div>
</aside>
