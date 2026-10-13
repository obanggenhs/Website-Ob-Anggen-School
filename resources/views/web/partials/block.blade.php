{{--
    Satu blok isi halaman. Diatur dari Studio › Tampilan blok:
    lebar, posisi, gaya, perataan judul, dan perataan isi.
    Posisi Kiri: blok-blok kiri yang muat tampil berdampingan. Tengah / Kanan: blok berada di barisnya sendiri.
--}}
<div class="{{ $b->columnClass() }} oa-block-col oa-place--{{ $b->placementKey() }}">
    <div class="{{ $b->layoutClass() }} h-100" id="blok-{{ $b->id }}">
        @switch($b->type)
            @case('teks')
                @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                <div class="prose">{!! $b->body !!}</div>
                @break

            @case('gambar_teks')
                {{-- Media di samping teks: foto, video lokal, YouTube, atau PDF --}}
                <div class="row g-3 align-items-center {{ $b->image_position === 'left' ? 'flex-md-row-reverse' : '' }}">
                    <div class="col-md-7">
                        @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                        <div class="prose">{!! $b->body !!}</div>
                    </div>
                    <div class="col-md-5 oa-block__media {{ in_array($b->mediaKind(), ['video', 'youtube', 'pdf'], true) ? 'oa-block__media--free' : '' }}">
                        @switch($b->mediaKind())
                            @case('video')
                                @include('web.partials.video', ['url' => $b->video_file ? media_url($b->video_file) : null, 'title' => $b->title ?: 'Video', 'class' => 'oa-video--side'])
                                @break
                            @case('youtube')
                                @include('web.partials.video', ['url' => $b->video_url, 'title' => $b->title ?: 'Video', 'class' => 'oa-video--side'])
                                @break
                            @case('pdf')
                                @if ($b->document)
                                    @include('web.partials.document', [
                                        'path' => $b->document, 'name' => $b->document_name, 'size' => $b->document_size,
                                        'title' => \App\Support\Media::displayName($b->document_name, $b->document, $b->title ?: 'Dokumen PDF'), 'mode' => 'card', 'layout' => 'stack',
                                        'download' => (bool) $b->docSetting('download'),
                                    ])
                                @endif
                                @break
                            @default
                                <a href="{{ media_url($b->image) }}" class="glightbox"><img src="{{ media_url($b->image) }}" alt="{{ $b->title }}" loading="lazy"></a>
                        @endswitch
                    </div>
                </div>
                @break

            @case('video')
                @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                @include('web.partials.video', ['url' => $b->videoLink(), 'title' => $b->title ?: 'Video', 'class' => 'oa-video--block'])
                @if ($b->caption)<p class="oa-caption">{{ $b->caption }}</p>@endif
                @break

            @case('dokumen')
                @php
                    // PDF tampil langsung: judul blok & keterangan di atas pembaca.
                    // Kartu dokumen & file Word/Excel/PowerPoint: judul & keterangan ada di dalam kartunya.
                    $docInline = \App\Support\Media::isPdf($b->document) && $b->docSetting('display') === 'reader';
                    $docDesc = \Illuminate\Support\Str::limit(trim(strip_tags((string) $b->body)), 260);
                @endphp
                @if ($docInline || ! $b->document)
                    @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                    @if (strip_tags((string) $b->body) !== '')<div class="prose mb-3">{!! $b->body !!}</div>@endif
                @endif
                @if ($b->document)
                    @include('web.partials.document', [
                        'path' => $b->document, 'name' => $b->document_name, 'size' => $b->document_size,
                        'title' => match (true) {
                            $docInline && $b->title => $b->document_name ?: \App\Support\Media::displayName(null, $b->document),
                            ! $b->title             => \App\Support\Media::displayName($b->document_name, $b->document),
                            default                 => $b->title,
                        },
                        'desc' => $docInline ? null : ($docDesc ?: null), 'mode' => $b->docSetting('display'),
                        'cover' => $b->image ? media_url($b->image) : null, 'download' => (bool) $b->docSetting('download'),
                    ])
                @endif
                @break

            @case('daftar')
                @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                <ol class="oa-numbered">
                    @foreach (lines($b->items) as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ol>
                @break

            @case('kartu')
                @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                @php
                    // Kolom kartu mengikuti lebar blok: di blok sempit (setengah atau kurang) kartu tersusun satu kolom
                    $cardCol = match ($b->displayWidth()) {
                        12      => 'col-md-6 col-xl-'.(count($b->cardItems()) % 3 === 0 ? 4 : 6),
                        8       => 'col-md-6',
                        3       => 'col-12',
                        default => 'col-md-6 col-lg-12',
                    };
                @endphp
                <div class="row g-2 g-md-3">
                    @foreach ($b->cardItems() as $card)
                        <div class="{{ $cardCol }}">
                            <div class="oa-feature">
                                <i class="bi {{ $card['icon'] }}"></i>
                                <div>
                                    <h3>{{ $card['title'] }}</h3>
                                    @if ($card['text'])<p>{{ $card['text'] }}</p>@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @break

            @case('galeri')
                @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                <div class="oa-gallery">
                    @foreach ($b->images ?? [] as $img)
                        @if (\App\Models\Block::isVideoPath($img))
                            {{-- Video: diputar di jendela pemutar saat diklik --}}
                            <a href="{{ media_url($img) }}" class="oa-gallery__video" data-video-modal title="Putar video">
                                <video src="{{ media_url($img) }}#t=0.5" muted playsinline preload="metadata"></video>
                            </a>
                        @else
                            <a href="{{ media_url($img) }}" class="glightbox" data-gallery="blok-{{ $b->id }}"><img src="{{ media_url($img) }}" alt="" loading="lazy"></a>
                        @endif
                    @endforeach
                </div>
                @break

            @case('kutipan')
                @if ($b->title)<h2 class="oa-block__title">{{ $b->title }}</h2>@endif
                <blockquote class="oa-quote">{{ strip_tags($b->body) }}</blockquote>
                @if ($b->caption)<p class="oa-caption mt-2">— {{ $b->caption }}</p>@endif
                @break

            @case('koleksi')
                @include('web.partials.card-collection', ['b' => $b])
                @break

            @case('tombol')
                <div class="oa-cta {{ $b->style === 'plain' ? 'oa-panel' : '' }}">
                    <div class="min-w-0">
                        @if ($b->title)<h2 class="oa-block__title mb-1">{{ $b->title }}</h2>@endif
                        <div class="prose">{!! $b->body !!}</div>
                    </div>
                    <a href="{{ $b->button_url }}" class="btn {{ $b->style === 'accent' ? 'btn-accent' : 'btn-primary' }} flex-none">{{ $b->button_label }} <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                @break
        @endswitch
    </div>
</div>
