{{--
    Video yang otomatis diputar (tanpa suara) saat kursor diarahkan, dan berhenti saat kursor keluar.
    Pakai: @include('web.partials.video', ['url' => $link, 'title' => 'Judul', 'class' => 'oa-video--block'])
    Mendukung link YouTube (biasa / pendek / shorts) dan file video langsung (.mp4 / .webm).
--}}
@if (! empty($url))
    <div class="oa-video {{ $class ?? '' }}" data-hover-video>
        <span class="oa-video__hint"><i class="bi bi-cursor-fill"></i> Arahkan kursor untuk memutar</span>
        <div class="ratio ratio-16x9">
            @if (is_video_file($url))
                <video src="{{ $url }}" muted loop playsinline controls preload="metadata"></video>
            @else
                <iframe src="{{ youtube_embed($url) }}" title="{{ $title ?? 'Video' }}" loading="lazy"
                        allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>
            @endif
        </div>
    </div>
@endif