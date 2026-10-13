<?php $__env->startSection('title', 'Beranda Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="dash-hero">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center position-relative" style="z-index:1">
            <div>
                <h1>Meja Pendaftaran</h1>
                <p>Pantau pendaftar dari berkas masuk sampai pengumuman. Klik setiap tahap untuk langsung memproses.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('dashboard.applicants.index', ['status' => 'baru'])); ?>" class="btn btn-light"><i class="bi bi-inbox"></i> Proses pendaftar baru</a>
                <a href="<?php echo e(route('dashboard.news.create', ['kanal' => 'pendaftaran'])); ?>" class="btn btn-outline-light"><i class="bi bi-megaphone"></i> Buat pengumuman</a>
            </div>
        </div>
    </div>

    <div class="pipeline mb-4">
        <?php $__currentLoopData = $pipeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('dashboard.applicants.index', ['status' => $p['key']])); ?>" class="pipeline__item" style="border-top-color: var(--bs-<?php echo e($p['color']); ?>)">
                <small><i class="bi <?php echo e($p['icon']); ?>"></i> <?php echo e($p['label']); ?></small>
                <strong><?php echo e($p['count']); ?></strong>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dash-card mb-4">
                <div class="card-header"><h2>Pendaftar masuk 14 hari terakhir</h2></div>
                <div class="card-body">
                    <?php $max = max(1, $daily->max('value')); ?>
                    <div class="mini-chart">
                        <?php $__currentLoopData = $daily; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mini-chart__bar" title="<?php echo e($d['label']); ?>: <?php echo e($d['value']); ?> pendaftar">
                                <?php if($d['value']): ?><b><?php echo e($d['value']); ?></b><?php endif; ?>
                                <span style="height: <?php echo e(max(3, ($d['value'] / $max) * 100)); ?>%"></span>
                                <small><?php echo e($d['label']); ?></small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Pendaftar terbaru</h2>
                    <a href="<?php echo e(route('dashboard.applicants.index')); ?>" class="small">Semua pendaftar <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <thead><tr><th>Nomor</th><th>Nama</th><th>Jalur</th><th>Masuk</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="small fw-bold"><?php echo e($r->number); ?></td>
                                    <td><a href="<?php echo e(route('dashboard.applicants.show', $r)); ?>" class="fw-bold text-body"><?php echo e($r->full_name); ?></a></td>
                                    <td class="small"><?php echo e($r->type->name); ?></td>
                                    <td class="small text-muted-d"><?php echo e($r->created_at->diffForHumans()); ?></td>
                                    <td><span class="badge text-bg-<?php echo e($r->status_info['color']); ?>"><?php echo e($r->status_label); ?></span></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5"><?php echo $__env->make('dashboard.partials.empty', ['text' => 'Belum ada pendaftar.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Jalur pendaftaran</h2>
                    <a href="<?php echo e(route('dashboard.registration-types.index')); ?>" class="small">Atur <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <span class="min-w-0 flex-fill">
                                    <a href="<?php echo e(route('dashboard.applicants.index', ['jalur' => $t->id])); ?>" class="dash-list__title d-block text-truncate"><?php echo e($t->name); ?></a>
                                    <span class="dash-list__meta"><?php echo e($t->registrations_count); ?> pendaftar · <?php echo e($t->new_count); ?> baru · <?php echo e($t->accepted_count); ?> diterima<?php echo e($t->quota ? ' / kuota '.$t->quota : ''); ?></span>
                                </span>
                                <form method="POST" action="<?php echo e(route('dashboard.registration-types.toggle', $t)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="badge border-0 <?php echo e($t->is_open ? 'text-bg-success' : 'text-bg-secondary'); ?>" title="Klik untuk <?php echo e($t->is_open ? 'menutup' : 'membuka'); ?>"><?php echo e($t->is_open ? 'Dibuka' : 'Ditutup'); ?></button>
                                </form>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2><i class="bi bi-calendar-event me-1"></i> Jadwal tes / wawancara</h2></div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <span class="badge text-bg-primary"><?php echo e($s->schedule_at->format('d/m')); ?></span>
                                <span class="min-w-0">
                                    <a href="<?php echo e(route('dashboard.applicants.show', $s)); ?>" class="dash-list__title d-block text-truncate"><?php echo e($s->full_name); ?></a>
                                    <span class="dash-list__meta"><?php echo e($s->schedule_at->format('H.i')); ?> WIT · <?php echo e($s->schedule_place); ?></span>
                                </span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="small text-muted-d">Belum ada jadwal.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <?php if($assigned->isNotEmpty()): ?>
                <div class="card dash-card" style="border-top: 4px solid var(--dash-primary)">
                    <div class="card-header"><h2><i class="bi bi-person-check me-1"></i> Tugas dari Master Admin</h2></div>
                    <div class="card-body py-2">
                        <ul class="dash-list">
                            <?php $__currentLoopData = $assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <i class="bi <?php echo e($m->type_icon); ?> text-primary"></i>
                                    <span class="min-w-0 flex-fill">
                                        <a href="<?php echo e(route('dashboard.studio.show', $m)); ?>" class="dash-list__title d-block"><?php echo e($m->title); ?></a>
                                        <span class="dash-list__meta"><?php echo e($m->type_label); ?></span>
                                    </span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/home/pendaftaran.blade.php ENDPATH**/ ?>