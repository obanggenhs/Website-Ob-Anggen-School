@extends('layouts.dashboard')

@section('title', 'Pengaturan Website')
@section('page_title', 'Pengaturan Website')
@section('page_subtitle', 'Identitas, kontak, warna, halaman login, dan pengaturan umum. Isi Beranda diatur dari Studio › Beranda.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card dash-card">
                <div class="list-group list-group-flush rounded-3">
                    @foreach ($tabs as $key => $t)
                        <a href="{{ route('dashboard.settings.edit', $key) }}" class="list-group-item list-group-item-action d-flex gap-2 align-items-center {{ $tab === $key ? 'active' : '' }}" @if ($tab === $key) style="background: var(--dash-primary); border-color: var(--dash-primary)" @endif>
                            <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            @if (! empty($tabs[$tab]['view']))
                {{-- Tab dengan tampilan khusus, mis. Halaman Login (foto / slide foto / video) --}}
                @include($tabs[$tab]['view'], ['tabInfo' => $tabs[$tab]])
            @else
                <form method="POST" action="{{ route('dashboard.settings.update', $tab) }}" enctype="multipart/form-data" class="card dash-card">
                    @csrf @method('PUT')
                    <div class="card-header"><h2><i class="bi {{ $tabs[$tab]['icon'] }} me-1"></i> {{ $tabs[$tab]['label'] }}</h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($tabs[$tab]['fields'] as $key => $f)
                                @php $col = 'col-md-'.($f['col'] ?? 12); $val = setting($key); @endphp
                                @switch($f['type'])
                                    @case('image')
                                        <x-form.image :name="$key" :label="$f['label']" :value="$val" :col="isset($f['col']) ? $col : 'col-md-6'" :logo="str_starts_with($key, 'logo') || $key === 'favicon'" help="PNG / JPG / WEBP." />
                                        @break
                                    @case('color')
                                        <div class="{{ $col }}">
                                            <label class="form-label" for="c_{{ $key }}">{{ $f['label'] }}</label>
                                            <div class="input-group">
                                                <input type="color" class="form-control form-control-color" value="{{ $val }}" data-color-sync="c_{{ $key }}" title="Pilih warna">
                                                <input type="text" name="{{ $key }}" id="c_{{ $key }}" value="{{ old($key, $val) }}" class="form-control" maxlength="7">
                                            </div>
                                        </div>
                                        @break
                                    @case('textarea')
                                        <x-form.textarea :name="$key" :label="$f['label']" :value="$val" :col="$col" />
                                        @break
                                    @case('toggle')
                                        <x-form.toggle :name="$key" :label="$f['label']" :checked="(bool) $val" :col="$col" />
                                        @break
                                    @case('select')
                                        <x-form.select :name="$key" :label="$f['label']" :options="$f['options']" :value="$val" :col="$col" />
                                        @break
                                    @default
                                        <x-form.input :name="$key" :label="$f['label']" :type="in_array($f['type'], ['email', 'url', 'number']) ? $f['type'] : 'text'" :value="$val" :col="$col" />
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan pengaturan</button></div>
                </form>
                @if (! empty($tabs[$tab]['after']))
                    @include($tabs[$tab]['after'])
                @endif
            @endif
        </div>
    </div>
@endsection
