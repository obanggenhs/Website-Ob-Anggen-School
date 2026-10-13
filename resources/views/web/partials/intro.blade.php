@if (! empty($menu?->intro) && strip_tags($menu->intro) !== '' && ! View::hasSection('no_intro'))
    <div class="oa-panel oa-intro prose" data-reveal>
        {!! $menu->intro !!}
    </div>
@endif
