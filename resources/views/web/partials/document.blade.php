{{--
    Dokumen di website.
    - PDF   : pembaca PDF langsung di halaman (mode "reader") atau kartu dokumen yang dibuka saat diklik (mode "card").
              Pembaca: pindah halaman, mode buku 2 halaman, perbesar/perkecil, daftar halaman, layar penuh, unduh.
    - Lainnya (Word/Excel/PowerPoint): kartu file dengan tombol unduh.

    @include('web.partials.document', [
        'path' => $block->document, 'name' => $block->document_name, 'size' => $block->document_size,
        'title' => 'Year Book 2025', 'desc' => null, 'cover' => null, 'mode' => 'reader', 'layout' => 'row', 'download' => true,
    ])
--}}
@php
    $isPdf = \App\Support\Media::isPdf($path);
    $url = media_url($path);
    $docType = \App\Support\Media::docType($path);
    $title = $title ?? ($name ?: 'Dokumen');
    $fileName = \App\Support\Media::downloadName($name ?? null, $path, $title);
    $sizeText = \App\Support\Media::sizeLabel($size ?? null);
    $download = $download ?? true;
    $mode = $isPdf ? (($mode ?? 'reader') === 'card' ? 'card' : 'reader') : 'file';
    $layout = $layout ?? 'row';
    $cover = $cover ?? null;
    $desc = $desc ?? null;
    $pdfjs = $isPdf ? \App\Support\Media::pdfjs() : [];
@endphp

@if ($mode === 'file')
    {{-- Word / Excel / PowerPoint: kartu unduh --}}
    <div class="oa-doc oa-doc--file">
        <span class="oa-doc__icon oa-doc__icon--{{ $docType['tone'] }}"><i class="bi {{ $docType['icon'] }}"></i></span>
        <div class="oa-doc__text">
            <strong class="oa-doc__title">{{ $title }}</strong>
            @if ($desc)<p class="oa-doc__desc">{{ $desc }}</p>@endif
            <span class="oa-doc__meta">{{ $docType['label'] }}{{ $sizeText ? ' · '.$sizeText : '' }}</span>
        </div>
        <a href="{{ $url }}" download="{{ $fileName }}" class="btn btn-primary btn-sm flex-shrink-0 text-nowrap"><i class="bi bi-download me-1"></i> Unduh</a>
    </div>
@else
    <div class="oa-doc oa-doc--{{ $mode }} {{ $mode === 'card' && $layout === 'stack' ? 'oa-doc--stack' : '' }}"
         data-pdf="{{ $url }}" data-title="{{ $title }}"
         data-pdfjs-lib="{{ $pdfjs['lib'] }}" data-pdfjs-worker="{{ $pdfjs['worker'] }}"
         data-pdfjs-cmaps="{{ $pdfjs['cmaps'] }}" data-pdfjs-fonts="{{ $pdfjs['fonts'] }}">

        @if ($mode === 'card')
            {{-- Kartu dokumen: sampul + keterangan, PDF dibuka saat diklik --}}
            <button type="button" class="oa-doc__cover {{ $cover ? 'has-image' : '' }}" data-pdf-act="expand" aria-label="Baca {{ $title }}">
                @if ($cover)
                    <img src="{{ $cover }}" alt="" loading="lazy">
                @else
                    <canvas data-pdf-cover></canvas>
                    <span class="oa-doc__cover-ph"><i class="bi bi-file-earmark-pdf"></i></span>
                @endif
                <span class="oa-doc__cover-badge">PDF</span>
                <span class="oa-doc__cover-open"><i class="bi bi-book"></i> Baca</span>
            </button>
            <div class="oa-doc__text">
                <strong class="oa-doc__title">{{ $title }}</strong>
                @if ($desc)<p class="oa-doc__desc">{{ $desc }}</p>@endif
                <span class="oa-doc__meta"><span data-pdf-pages>PDF</span>{{ $sizeText ? ' · '.$sizeText : '' }}</span>
                <div class="oa-doc__actions">
                    <button type="button" class="btn btn-primary btn-sm" data-pdf-act="expand"><i class="bi bi-book me-1"></i> Baca dokumen</button>
                    @if ($download)
                        <a href="{{ $url }}" download="{{ $fileName }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i> Unduh</a>
                    @endif
                </div>
            </div>
        @else
            {{-- Kepala pembaca: nama dokumen + tombol --}}
            <div class="oa-doc__head">
                <span class="oa-doc__icon oa-doc__icon--pdf"><i class="bi bi-file-earmark-pdf"></i></span>
                <div class="oa-doc__info">
                    <strong class="oa-doc__title">{{ $title }}</strong>
                    <span class="oa-doc__meta"><span data-pdf-pages>PDF</span>{{ $sizeText ? ' · '.$sizeText : '' }}</span>
                </div>
                <div class="oa-doc__actions">
                    <button type="button" class="btn btn-primary btn-sm" data-pdf-act="expand"><i class="bi bi-arrows-fullscreen"></i><span class="ms-1">Layar penuh</span></button>
                    <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i><span class="ms-1">Buka</span></a>
                    @if ($download)
                        <a href="{{ $url }}" download="{{ $fileName }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download"></i><span class="ms-1">Unduh</span></a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Pembaca PDF (mode kartu: tersembunyi sampai dibuka) --}}
        <div class="oa-doc__reader" @if ($mode === 'card') hidden @endif role="region" aria-label="Pembaca dokumen {{ $title }}">
            <div class="oa-doc__bar">
                <strong class="oa-doc__bar-title">{{ $title }}</strong>
                <div class="oa-doc__nav">
                    <button type="button" data-pdf-act="prev" title="Halaman sebelumnya" aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></button>
                    <label class="oa-doc__pageno">
                        <input type="text" inputmode="numeric" value="1" data-pdf-page aria-label="Nomor halaman">
                        <span>/ <span data-pdf-total>–</span></span>
                    </label>
                    <button type="button" data-pdf-act="next" title="Halaman berikutnya" aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></button>
                </div>
                <div class="oa-doc__tools">
                    <button type="button" data-pdf-act="zoom-out" title="Perkecil" aria-label="Perkecil"><i class="bi bi-zoom-out"></i></button>
                    <span class="oa-doc__zoom" data-pdf-zoom>100%</span>
                    <button type="button" data-pdf-act="zoom-in" title="Perbesar" aria-label="Perbesar"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" data-pdf-act="spread" class="oa-doc__spread" aria-pressed="false" title="Tampilan buku (2 halaman)" aria-label="Tampilan buku 2 halaman"><i class="bi bi-book"></i></button>
                    <button type="button" data-pdf-act="thumbs" aria-pressed="false" title="Daftar halaman" aria-label="Daftar halaman"><i class="bi bi-grid-3x3-gap"></i></button>
                    <button type="button" data-pdf-act="expand" class="oa-doc__only-inline" title="Layar penuh" aria-label="Layar penuh"><i class="bi bi-arrows-fullscreen"></i></button>
                    @if ($download)
                        <a href="{{ $url }}" download="{{ $fileName }}" class="oa-doc__only-expanded" title="Unduh" aria-label="Unduh"><i class="bi bi-download"></i></a>
                    @endif
                    <button type="button" data-pdf-act="close" class="oa-doc__only-expanded oa-doc__close" title="Tutup (Esc)" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="oa-doc__frame">
                <div class="oa-doc__thumbs" data-pdf-thumbs hidden></div>
                <div class="oa-doc__view" data-pdf-view tabindex="0">
                    <div class="oa-doc__sheet" data-pdf-sheet></div>
                    <button type="button" class="oa-doc__flip oa-doc__flip--prev" data-pdf-act="prev" aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></button>
                    <button type="button" class="oa-doc__flip oa-doc__flip--next" data-pdf-act="next" aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></button>
                    <div class="oa-doc__status" data-pdf-status>
                        <span><span class="spinner-border spinner-border-sm me-2"></span>Memuat dokumen...</span>
                    </div>
                    <noscript><iframe class="oa-doc__native" src="{{ $url }}#view=FitH" title="{{ $title }}"></iframe></noscript>
                </div>
            </div>
        </div>
    </div>
@endif
