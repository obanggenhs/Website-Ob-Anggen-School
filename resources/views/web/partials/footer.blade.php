<footer class="oa-footer">
    <div class="container oa-container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4">
                <a href="{{ route('home') }}" class="oa-footer__brand">
                    <img src="{{ media_url(setting('logo_main')) }}" alt="">
                    <strong>{{ setting('site_name') }}</strong>
                </a>
                <p>{{ setting('footer_about') }}</p>
                @include('web.partials.social', ['class' => 'oa-social-btns'])
            </div>

            @foreach ($navMenus->filter(fn ($m) => $m->isGroup() && $m->children->isNotEmpty())->take(3) as $group)
                <div class="col-6 col-lg-2">
                    <h3>{{ $group->title }}</h3>
                    <ul class="oa-footer__links">
                        @foreach ($group->children as $child)
                            <li><a href="{{ $child->link }}" target="{{ $child->target }}">{{ $child->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="col-6 col-lg-2">
                <h3>Kontak</h3>
                <ul class="oa-footer__links">
                    @if (setting('contact_phone'))<li><a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone')) }}">{{ setting('contact_phone') }}</a></li>@endif
                    @if (setting('contact_email'))<li><a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></li>@endif
                    <li>{{ setting('contact_address') }}</li>
                </ul>
            </div>
        </div>

        <div class="oa-footer__bottom">
            <span>&copy; {{ date('Y') }} {{ setting('site_name') }}. Hak cipta dilindungi.</span>
            <a href="{{ route('login') }}"><i class="bi bi-lock"></i> Masuk admin</a>
        </div>
    </div>
</footer>
