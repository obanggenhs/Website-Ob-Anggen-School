{{--
    Satu kartu Koleksi Kartu (mirip kartu Fasilitas). Diklik → halaman detail kartu.
    $card  : App\Models\BlockItem      $url   : alamat detail
    $shape : landscape | square | portrait | none
    $excerpt : tampilkan ringkasan       $button : teks tautan
--}}
@php
    $shape = $shape ?? 'landscape';
    $imgClass = ['square' => 'oa-card__img--square', 'portrait' => 'oa-card__img--portrait'][$shape] ?? '';
@endphp
<a href="{{ $url }}" class="oa-card oa-collection-card {{ $shape === 'none' ? 'oa-card--noimg' : '' }}">
    @if ($shape !== 'none')
        <div class="oa-card__img {{ $imgClass }}">
            <img src="{{ $card->image_url }}" alt="{{ $card->title }}" loading="lazy">
            @if ($card->category)<span class="oa-card__badge oa-badge">{{ $card->category }}</span>@endif
            @if ($card->document || $card->video_url || $card->video_file)
                <span class="oa-card__mediatags">
                    @if ($card->document)<span title="Ada dokumen"><i class="bi {{ \App\Support\Media::docType($card->document)['icon'] }}"></i></span>@endif
                    @if ($card->video_url || $card->video_file)<span title="Ada video"><i class="bi bi-play-fill"></i></span>@endif
                </span>
            @endif
        </div>
    @endif
    <div class="oa-card__body">
        @if ($shape === 'none' && $card->category)<span class="oa-badge align-self-start">{{ $card->category }}</span>@endif
        <h3 class="oa-card__title">{{ $card->title }}</h3>
        @if ($card->subtitle || $card->year)
            <span class="oa-card__meta">
                @if ($card->subtitle)<span><i class="bi bi-person-badge"></i>{{ $card->subtitle }}</span>@endif
                @if ($card->year)<span><i class="bi bi-calendar3"></i>{{ $card->year }}</span>@endif
            </span>
        @endif
        @if (($excerpt ?? true) && $card->summary)
            <p class="oa-card__text">{{ $card->summary }}</p>
        @endif
        <span class="oa-card__foot oa-link-more">{{ $button ?? 'Lihat detail' }} <i class="bi bi-arrow-right"></i></span>
    </div>
</a>
