{{--
    Lapisan latar bergerak untuk header halaman & hero Beranda.
    $media berasal dari Menu::headerMedia(): jenis image / video / youtube / slider.
    Untuk jenis "image" tidak perlu lapisan: foto sudah dipasang sebagai background CSS.
--}}
@if (! empty($media) && $media['type'] !== 'image')
    <div class="oa-headmedia oa-headmedia--{{ $media['type'] }}" aria-hidden="true" data-headmedia>
        @switch($media['type'])
            @case('video')
                <video src="{{ $media['src'] }}" @if (! empty($media['poster'])) poster="{{ $media['poster'] }}" @endif
                       autoplay muted loop playsinline preload="auto" disablepictureinpicture tabindex="-1"></video>
                @break

            @case('youtube')
                <iframe src="{{ $media['src'] }}" title="Video latar" tabindex="-1" loading="lazy"
                        allow="autoplay; encrypted-media; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                @break

            @case('slider')
                @foreach ($media['slides'] as $i => $slide)
                    <div class="oa-headmedia__slide {{ $i === 0 ? 'is-active' : '' }}" style="background-image: url('{{ $slide }}')"></div>
                @endforeach
                @if (count($media['slides']) > 1)
                    <div class="oa-headmedia__dots">
                        @foreach ($media['slides'] as $i => $slide)
                            <span class="{{ $i === 0 ? 'is-active' : '' }}"></span>
                        @endforeach
                    </div>
                @endif
                @break
        @endswitch
    </div>
@endif