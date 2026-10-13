<a href="{{ route('staff.show', $person->slug) }}" class="oa-card oa-person">
    <div class="oa-card__img oa-card__img--portrait"><img src="{{ $person->photo_url }}" alt="{{ $person->name }}" loading="lazy"></div>
    <div class="oa-card__body">
        <h3 class="oa-card__title">{{ $person->name }}</h3>
        <span class="oa-person__role">{{ $person->position_title ?: $person->category_label }}</span>
        @if ($person->branch)<span class="oa-card__meta justify-content-center"><span><i class="bi bi-geo-alt"></i>{{ $person->branch->name }}</span></span>@endif
    </div>
</a>
