{{--
    Unggah dokumen (PDF, Word, Excel, PowerPoint, ZIP, dan file lain) + info dokumen yang tersimpan.
    Tanpa batas ukuran; file besar diunggah bertahap oleh dashboard.js.
    @include('dashboard.studio._media-document', [
        'path' => $block->document, 'name' => $block->document_name, 'size' => $block->document_size,
        'pdfOnly' => false, 'removable' => true, 'label' => 'Unggah dokumen',
    ])
--}}
@php
    $pdfOnly = $pdfOnly ?? false;
    $docType = \App\Support\Media::docType($path);
    $accept = $pdfOnly ? '.pdf,application/pdf' : null;   // selain PDF: semua jenis file boleh
@endphp

@if ($path)
    <div class="doc-current mb-3">
        <span class="doc-current__icon doc-tone--{{ $docType['tone'] }}"><i class="bi {{ $docType['icon'] }}"></i></span>
        <div class="min-w-0 flex-fill">
            <strong class="d-block text-truncate">{{ $name ?: basename($path) }}</strong>
            <small class="text-muted-d">{{ $docType['label'] }}{{ $size ? ' · '.\App\Support\Media::sizeLabel($size) : '' }} · tersimpan</small>
        </div>
        <a href="{{ media_url($path) }}" target="_blank" rel="noopener" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat</a>
    </div>
@endif

<label class="form-label" for="f_document">{{ $path ? 'Ganti dokumen' : ($label ?? 'Unggah dokumen') }}</label>
<input type="file" name="document" id="f_document" @if ($accept) accept="{{ $accept }}" @endif class="form-control @error('document') is-invalid @enderror"
       data-file-label="f_document_pick">
@error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
<div class="file-pick d-none" id="f_document_pick"></div>
<div class="form-text">
    {{ $pdfOnly ? 'File PDF' : 'PDF, Word, Excel, PowerPoint, ZIP, atau file lain' }}, tanpa batas ukuran.
    {{ $pdfOnly ? 'Pengunjung bisa membaca PDF langsung di website dan mengunduhnya.' : 'PDF bisa dibaca langsung di website; file lain tampil sebagai tombol unduh.' }}
</div>

@if ($path && ($removable ?? true))
    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="remove_document" value="1" id="f_remove_document">
        <label class="form-check-label small" for="f_remove_document">Hapus dokumen ini</label>
    </div>
@endif
