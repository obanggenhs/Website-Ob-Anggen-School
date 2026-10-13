<a href="{{ route('news.show', $post->slug) }}" class="oa-card">
    <div class="oa-card__img">
        <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" loading="lazy">
        @if ($post->is_featured)<span class="oa-card__badge oa-badge oa-badge--accent"><i class="bi bi-star-fill"></i> Penting</span>@endif
    </div>
    <div class="oa-card__body">
        <span class="oa-card__meta">
            <span><i class="bi bi-calendar3"></i>{{ tgl($post->published_at, 'd M Y') }}</span>
            @if ($post->category)<span><i class="bi bi-tag"></i>{{ $post->category->name }}</span>@endif
        </span>
        <h3 class="oa-card__title">{{ $post->title }}</h3>
        <p class="oa-card__text">{{ $post->summary }}</p>
        <span class="oa-card__foot oa-link-more">Baca berita <i class="bi bi-arrow-right"></i></span>
    </div>
</a>
