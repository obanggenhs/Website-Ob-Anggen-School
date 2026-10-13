@extends('layouts.web-full')

@php
    $levels = ['' => 'Semua', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'];
    $categories = \App\Models\CalendarEvent::CATEGORIES;
@endphp

@section('title', $menu?->title ?? 'Kalender Akademik')
@section('page_title', $menu?->title ?? 'Kalender Akademik')
@section('page_subtitle', ($menu?->subtitle ?: 'Agenda akademik sekolah').' · Tahun ajaran '.$year)

@section('page')
    {{-- Baris 1: jenjang & semester --}}
    <div class="oa-cal-toolbar no-print">
        <nav class="oa-seg" aria-label="Pilih jenjang">
            @foreach ($levels as $key => $label)
                <a href="{{ route('calendar.index', array_filter(['jenjang' => $key, 'tahun' => $years->count() > 1 ? $year : null])) }}"
                   class="{{ ($level ?? '') === $key ? 'active' : '' }}" data-level-link>{{ $label }}</a>
            @endforeach
        </nav>

        <div class="d-flex flex-wrap align-items-center gap-2">
            @if ($years->count() > 1)
                <select class="form-select form-select-sm rounded-pill w-auto" aria-label="Tahun ajaran" onchange="window.location.href = this.value">
                    @foreach ($years as $y)
                        <option value="{{ route('calendar.index', array_filter(['jenjang' => $level, 'tahun' => $y])) }}" @selected($y === $year)>{{ $y }}</option>
                    @endforeach
                </select>
            @endif
            <div class="oa-seg" role="group" aria-label="Semester">
                <button type="button" data-sem="ganjil">Semester ganjil</button>
                <button type="button" data-sem="genap">Semester genap</button>
            </div>
        </div>
    </div>

    {{-- Baris 2: kategori & unduh --}}
    <div class="oa-cal-toolbar no-print">
        <div class="oa-chips">
            <button type="button" class="oa-cal-chip active" data-cat="all">Semua kategori</button>
            @foreach ($categories as $key => $cat)
                <button type="button" class="oa-cal-chip" data-cat="{{ $key }}" style="--cat: {{ $cat['color'] }}">{{ $cat['label'] }}</button>
            @endforeach
        </div>
        <div class="dropdown">
            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download me-1"></i> Unduh
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 small">
                <li><button type="button" class="dropdown-item" data-download="ics"><i class="bi bi-calendar-plus me-2"></i>Kalender (.ics) untuk Google / Outlook / HP</button></li>
                <li><button type="button" class="dropdown-item" data-download="csv"><i class="bi bi-filetype-csv me-2"></i>Daftar agenda (Excel / CSV)</button></li>
                <li><button type="button" class="dropdown-item" data-download="print"><i class="bi bi-printer me-2"></i>Cetak / simpan PDF</button></li>
            </ul>
        </div>
    </div>

    {{-- Kalender bulanan (diisi oleh calendar.js) --}}
    <div class="oa-cal" id="oaCalendar">
        <div class="oa-cal__head">
            <h2 class="oa-cal__title" data-cal-title>{{ tgl($calendar['month'].'-01', 'F Y') }}</h2>
            <div class="oa-cal__nav no-print">
                <button type="button" class="btn btn-sm" data-cal-prev aria-label="Bulan sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <button type="button" class="btn btn-sm" data-cal-today>Hari ini</button>
                <button type="button" class="btn btn-sm" data-cal-next aria-label="Bulan berikutnya"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
        <div class="oa-cal__grid" aria-hidden="true">
            @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                <div class="oa-cal__dow">{{ $day }}</div>
            @endforeach
        </div>
        <div class="oa-cal__grid" data-cal-grid aria-live="polite"></div>
        <div class="oa-cal-pop" data-cal-pop role="dialog" aria-label="Detail agenda"></div>
    </div>

    {{-- Daftar agenda bulan yang sedang dibuka + ringkasan --}}
    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="oa-panel h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <h2 class="h6 mb-0" data-cal-list-title>Agenda bulan ini</h2>
                    <small class="text-muted-oa no-print">Klik agenda untuk melihatnya di kalender</small>
                </div>
                <ul class="oa-cal-list" data-cal-list></ul>

                <noscript>
                    <ul class="oa-cal-list">
                        @foreach ($events as $event)
                            <li style="--cat: {{ $event->category_color }}">
                                <span class="oa-date"><strong>{{ $event->start_date->format('d') }}</strong><small>{{ tgl($event->start_date, 'M') }}</small></span>
                                <span><strong>{{ $event->title }}</strong><small>{{ $event->date_label }} · {{ $event->level_label }}</small></span>
                            </li>
                        @endforeach
                    </ul>
                </noscript>
            </div>
        </div>

        <div class="col-lg-4 no-print">
            @if ($next)
                <div class="oa-widget mb-3" style="border-top: 3px solid var(--oa-accent)">
                    <h2 class="oa-widget__title">Agenda terdekat</h2>
                    <div class="d-flex gap-2 align-items-center" style="--cat: {{ $next->category_color }}">
                        <span class="oa-date"><strong>{{ $next->start_date->format('d') }}</strong><small>{{ tgl($next->start_date, 'M') }}</small></span>
                        <div class="min-w-0">
                            <strong class="d-block small">{{ $next->title }}</strong>
                            <small class="text-muted-oa">{{ $next->date_label }}</small>
                        </div>
                    </div>
                    <span class="oa-when mt-2 d-inline-block {{ $next->isOngoing() ? 'is-now' : '' }}">{{ $next->relative_label }}</span>
                </div>
            @endif

            <div class="oa-widget">
                <h2 class="oa-widget__title">Ringkasan tahun ajaran {{ $year }}</h2>
                <ul class="oa-info-list">
                    @foreach ($categories as $key => $cat)
                        <li>
                            <span><i class="bi bi-circle-fill me-1" style="color: {{ $cat['color'] }}; font-size: .55rem"></i> {{ $cat['label'] }}</span>
                            <span>{{ $events->where('category', $key)->count() }} agenda</span>
                        </li>
                    @endforeach
                    <li><span>Total</span><span>{{ $events->count() }} agenda</span></li>
                </ul>
            </div>
        </div>
    </div>

    <script type="application/json" id="oaCalendarData">@json($calendar)</script>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/calendar.js') }}"></script>
@endpush