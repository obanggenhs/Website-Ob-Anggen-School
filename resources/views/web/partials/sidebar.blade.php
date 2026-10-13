<div class="oa-sidebar">
    @if (\App\Models\Menu::moduleEnabled('berita') && $sidebarNews->isNotEmpty())
        <div class="oa-widget">
            <h2 class="oa-widget__title">Berita terbaru</h2>
            @foreach ($sidebarNews as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="oa-mini-post">
                    <img src="{{ $item->cover_url }}" alt="" loading="lazy">
                    <span class="min-w-0">
                        <strong>{{ $item->title }}</strong>
                        <small><i class="bi bi-calendar3"></i> {{ tgl($item->published_at, 'd M Y') }}</small>
                    </span>
                </a>
            @endforeach
            <a href="{{ route('news.index') }}" class="oa-link-more mt-2">Semua berita <i class="bi bi-arrow-right"></i></a>
        </div>
    @endif

    <div class="oa-widget">
        <h2 class="oa-widget__title">Hubungi kami</h2>
        <ul class="oa-contact-list">
            <li>
                <a href="{{ setting('maps_url') ?: 'https://maps.google.com/?q='.urlencode(setting('contact_address')) }}" target="_blank" rel="noopener">
                    <i class="bi bi-geo-alt"></i><span><small>Alamat</small>{{ setting('contact_address') }}</span>
                </a>
            </li>
            @if (setting('contact_phone'))
                <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone')) }}"><i class="bi bi-telephone"></i><span><small>Telepon</small>{{ setting('contact_phone') }}</span></a></li>
            @endif
            @if (setting('contact_whatsapp'))
                <li><a href="{{ wa_link(setting('contact_whatsapp'), 'Halo '.setting('site_name')) }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i><span><small>WhatsApp</small>{{ setting('contact_whatsapp') }}</span></a></li>
            @endif
            @if (setting('contact_email'))
                <li><a href="mailto:{{ setting('contact_email') }}"><i class="bi bi-envelope"></i><span><small>Email</small>{{ setting('contact_email') }}</span></a></li>
            @endif
        </ul>
        <div class="mt-3">@include('web.partials.social', ['class' => 'oa-social-btns'])</div>
    </div>

    @stack('sidebar')
</div>
