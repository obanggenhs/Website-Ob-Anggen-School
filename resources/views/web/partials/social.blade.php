@php
    $links = [
        'social_facebook'  => 'bi-facebook',
        'social_instagram' => 'bi-instagram',
        'social_youtube'   => 'bi-youtube',
        'social_tiktok'    => 'bi-tiktok',
    ];
@endphp
<span class="{{ $class ?? 'oa-social' }}">
    @foreach ($links as $key => $icon)
        @if (setting($key))
            <a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst(str_replace('social_', '', $key)) }}"><i class="bi {{ $icon }}"></i></a>
        @endif
    @endforeach
    @if (setting('contact_whatsapp'))
        <a href="{{ wa_link(setting('contact_whatsapp'), 'Halo '.setting('site_name').', saya ingin bertanya.') }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
    @endif
</span>
