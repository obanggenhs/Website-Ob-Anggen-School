{{--
    Link YouTube + pratinjau thumbnail.
    @include('dashboard.studio._media-youtube', ['url' => $block->video_url])
--}}
@php
    $ytId = youtube_id(old('video_url', $url));
@endphp
<div class="row g-3 align-items-start">
    <div class="col-md-5">
        <div class="media-preview media-preview--wide">
            <img src="{{ $ytId ? 'https://img.youtube.com/vi/'.$ytId.'/hqdefault.jpg' : '' }}" alt="" id="mediaYtPreview" class="{{ $ytId ? '' : 'd-none' }}">
            <span class="media-preview__play {{ $ytId ? '' : 'd-none' }}" data-yt-play><i class="bi bi-youtube"></i></span>
            <div class="media-preview__empty {{ $ytId ? 'd-none' : '' }}" data-yt-empty><i class="bi bi-youtube"></i>Tempel link YouTube</div>
        </div>
    </div>
    <div class="col-md-7">
        <label class="form-label" for="f_video_url">Link YouTube</label>
        <input type="url" name="video_url" id="f_video_url" value="{{ old('video_url', $url) }}"
               class="form-control @error('video_url') is-invalid @enderror" placeholder="https://www.youtube.com/watch?v=..." data-yt-input="mediaYtPreview">
        @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Link biasa, youtu.be, atau Shorts. Video diputar di website tanpa perlu membuka YouTube.</div>
    </div>
</div>
