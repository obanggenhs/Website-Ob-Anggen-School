{{-- Isi Beranda: disimpan sebagai pengaturan (tabel settings) --}}
@php
    $groups = [
        'Hero (bagian paling atas)' => ['hero_title', 'hero_subtitle', 'hero_button', 'hero_button_url'],
        'Angka statistik' => ['stat_campus', 'stat_students', 'stat_teachers'],
        'Sambutan' => ['welcome_title', 'welcome_text', 'welcome_link', 'welcome_image'],
        'Video profil' => ['video_title', 'video_text', 'video_url', 'video_bg'],
        'Bagian yang ditampilkan' => ['home_show_quick', 'home_show_branches', 'home_show_agenda', 'home_show_news', 'home_show_video', 'home_show_testimonials', 'home_blocks_position'],
    ];
@endphp
<form method="POST" action="{{ route('dashboard.studio.home', $menu) }}" enctype="multipart/form-data" class="card dash-card mb-4">
    @csrf @method('PUT')
    <div class="card-header d-flex justify-content-between align-items-center">
        <div><h2 class="dash-card__title">Isi Beranda</h2><small class="text-muted-d">Klik setiap bagian untuk membuka.</small></div>
        <button class="btn btn-primary btn-sm"><i class="bi bi-check2-circle me-1"></i> Simpan Beranda</button>
    </div>
    <div class="accordion accordion-flush" id="homeAccordion">
        @foreach ($groups as $label => $keys)
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#home{{ $loop->index }}">{{ $label }}</button>
                </h3>
                <div id="home{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#homeAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            @foreach ($keys as $key)
                                @php $f = $homeFields[$key]; $val = setting($key); @endphp
                                @switch($f['type'])
                                    @case('image')
                                        <x-form.image :name="$key" :label="$f['label']" :value="$val" col="col-md-6" />
                                        @break
                                    @case('editor')
                                        <x-form.editor :name="$key" :label="$f['label']" :value="$val" :height="160" />
                                        @break
                                    @case('textarea')
                                        <x-form.textarea :name="$key" :label="$f['label']" :value="$val" />
                                        @break
                                    @case('toggle')
                                        <x-form.toggle :name="$key" :label="$f['label']" :checked="(bool) $val" col="col-md-6" />
                                        @break
                                    @case('select')
                                        <x-form.select :name="$key" :label="$f['label']" :options="$f['options']" :value="$val" col="col-md-6" />
                                        @break
                                    @case('number')
                                        <x-form.input :name="$key" :label="$f['label']" type="number" :value="$val" col="col-md-4" min="0" />
                                        @break
                                    @default
                                        <x-form.input :name="$key" :label="$f['label']" :type="$f['type'] === 'url' ? 'url' : 'text'" :value="$val" col="col-md-6" />
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan Beranda</button></div>
</form>