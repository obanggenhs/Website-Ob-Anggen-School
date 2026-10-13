<?php
    $levels = ['' => 'Semua', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'];
    $categories = \App\Models\CalendarEvent::CATEGORIES;
?>

<?php $__env->startSection('title', $menu?->title ?? 'Kalender Akademik'); ?>
<?php $__env->startSection('page_title', $menu?->title ?? 'Kalender Akademik'); ?>
<?php $__env->startSection('page_subtitle', ($menu?->subtitle ?: 'Agenda akademik sekolah').' · Tahun ajaran '.$year); ?>

<?php $__env->startSection('page'); ?>
    
    <div class="oa-cal-toolbar no-print">
        <nav class="oa-seg" aria-label="Pilih jenjang">
            <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('calendar.index', array_filter(['jenjang' => $key, 'tahun' => $years->count() > 1 ? $year : null]))); ?>"
                   class="<?php echo e(($level ?? '') === $key ? 'active' : ''); ?>" data-level-link><?php echo e($label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <?php if($years->count() > 1): ?>
                <select class="form-select form-select-sm rounded-pill w-auto" aria-label="Tahun ajaran" onchange="window.location.href = this.value">
                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(route('calendar.index', array_filter(['jenjang' => $level, 'tahun' => $y]))); ?>" <?php if($y === $year): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>
            <div class="oa-seg" role="group" aria-label="Semester">
                <button type="button" data-sem="ganjil">Semester ganjil</button>
                <button type="button" data-sem="genap">Semester genap</button>
            </div>
        </div>
    </div>

    
    <div class="oa-cal-toolbar no-print">
        <div class="oa-chips">
            <button type="button" class="oa-cal-chip active" data-cat="all">Semua kategori</button>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="oa-cal-chip" data-cat="<?php echo e($key); ?>" style="--cat: <?php echo e($cat['color']); ?>"><?php echo e($cat['label']); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

    
    <div class="oa-cal" id="oaCalendar">
        <div class="oa-cal__head">
            <h2 class="oa-cal__title" data-cal-title><?php echo e(tgl($calendar['month'].'-01', 'F Y')); ?></h2>
            <div class="oa-cal__nav no-print">
                <button type="button" class="btn btn-sm" data-cal-prev aria-label="Bulan sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <button type="button" class="btn btn-sm" data-cal-today>Hari ini</button>
                <button type="button" class="btn btn-sm" data-cal-next aria-label="Bulan berikutnya"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
        <div class="oa-cal__grid" aria-hidden="true">
            <?php $__currentLoopData = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="oa-cal__dow"><?php echo e($day); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="oa-cal__grid" data-cal-grid aria-live="polite"></div>
        <div class="oa-cal-pop" data-cal-pop role="dialog" aria-label="Detail agenda"></div>
    </div>

    
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
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li style="--cat: <?php echo e($event->category_color); ?>">
                                <span class="oa-date"><strong><?php echo e($event->start_date->format('d')); ?></strong><small><?php echo e(tgl($event->start_date, 'M')); ?></small></span>
                                <span><strong><?php echo e($event->title); ?></strong><small><?php echo e($event->date_label); ?> · <?php echo e($event->level_label); ?></small></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </noscript>
            </div>
        </div>

        <div class="col-lg-4 no-print">
            <?php if($next): ?>
                <div class="oa-widget mb-3" style="border-top: 3px solid var(--oa-accent)">
                    <h2 class="oa-widget__title">Agenda terdekat</h2>
                    <div class="d-flex gap-2 align-items-center" style="--cat: <?php echo e($next->category_color); ?>">
                        <span class="oa-date"><strong><?php echo e($next->start_date->format('d')); ?></strong><small><?php echo e(tgl($next->start_date, 'M')); ?></small></span>
                        <div class="min-w-0">
                            <strong class="d-block small"><?php echo e($next->title); ?></strong>
                            <small class="text-muted-oa"><?php echo e($next->date_label); ?></small>
                        </div>
                    </div>
                    <span class="oa-when mt-2 d-inline-block <?php echo e($next->isOngoing() ? 'is-now' : ''); ?>"><?php echo e($next->relative_label); ?></span>
                </div>
            <?php endif; ?>

            <div class="oa-widget">
                <h2 class="oa-widget__title">Ringkasan tahun ajaran <?php echo e($year); ?></h2>
                <ul class="oa-info-list">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <span><i class="bi bi-circle-fill me-1" style="color: <?php echo e($cat['color']); ?>; font-size: .55rem"></i> <?php echo e($cat['label']); ?></span>
                            <span><?php echo e($events->where('category', $key)->count()); ?> agenda</span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li><span>Total</span><span><?php echo e($events->count()); ?> agenda</span></li>
                </ul>
            </div>
        </div>
    </div>

    <script type="application/json" id="oaCalendarData"><?php echo json_encode($calendar, 15, 512) ?></script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/calendar.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.web-full', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/calendar/index.blade.php ENDPATH**/ ?>