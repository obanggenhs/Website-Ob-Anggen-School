{{--
    Unggah video dari komputer (MP4 / WEBM / OGG / MOV) + pratinjau. Tanpa batas ukuran (diunggah bertahap).
    @include('dashboard.studio._media-video', ['path' => $block->video_file])
--}}
<div class="row g-3 align-items-start">
    <div class="col-md-5">
        <div class="media-preview media-preview--wide">
            <video id="mediaVideoPreview" controls muted playsinline preload="metadata" @if ($path) src="{{ media_url($path) }}" @endif class="{{ $path ? '' : 'd-none' }}"></video>
            <div class="media-preview__empty {{ $path ? 'd-none' : '' }}" data-video-empty><i class="bi bi-camera-video"></i>Belum ada video</div>
        </div>
    </div>
    <div class="col-md-7">
        <label class="form-label" for="f_video_file">{{ $path ? 'Ganti video' : 'Unggah video dari komputer' }}</label>
        <input type="file" name="video_file" id="f_video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" class="form-control @error('video_file') is-invalid @enderror"
               data-video-input="mediaVideoPreview" data-file-label="f_video_file_pick">
        @error('video_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="file-pick d-none" id="f_video_file_pick"></div>
        <div class="form-text">MP4 / WEBM / OGG / MOV, tanpa batas ukuran. Di website, pengunjung memutar video dengan tombol play.</div>
        @if ($path)
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="f_remove_video">
                <label class="form-check-label small" for="f_remove_video">Hapus video ini</label>
            </div>
        @endif
    </div>
</div>
