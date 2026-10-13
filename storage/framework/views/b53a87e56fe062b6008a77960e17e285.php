<?php $__env->startSection('title', 'Jalur & Formulir'); ?>
<?php $__env->startSection('page_title', 'Jalur Pendaftaran & Formulir'); ?>
<?php $__env->startSection('page_subtitle', 'Buka/tutup pendaftaran, atur kuota, persyaratan, dan isian formulir setiap jalur.'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.registration-types.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Jalur baru</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3">
        <?php $__empty_1 = true; $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-xl-4">
                <div class="card dash-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <div>
                                <span class="badge badge-soft"><?php echo e(\App\Models\RegistrationType::AUDIENCES[$t->audience]); ?><?php echo e($t->level_label ? ' · '.$t->level_label : ''); ?></span>
                                <h3 class="h6 mt-2 mb-0"><?php echo e($t->name); ?></h3>
                            </div>
                            <form method="POST" action="<?php echo e(route('dashboard.registration-types.toggle', $t)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <div class="form-check form-switch m-0" title="Buka / tutup pendaftaran">
                                    <input class="form-check-input fs-5" type="checkbox" role="switch" onchange="this.form.submit()" <?php if($t->is_open): echo 'checked'; endif; ?>>
                                </div>
                            </form>
                        </div>
                        <div class="small text-muted-d mb-3">
                            <?php echo e($t->status_label); ?><?php echo e($t->period_label ? ' · '.$t->period_label : ''); ?><?php echo e($t->quota ? ' · kuota '.$t->quota : ''); ?>

                        </div>
                        <div class="d-flex gap-3 small">
                            <span><strong class="fs-5 d-block"><?php echo e($t->registrations_count); ?></strong>pendaftar</span>
                            <span><strong class="fs-5 d-block text-warning"><?php echo e($t->new_count); ?></strong>baru</span>
                            <span><strong class="fs-5 d-block"><?php echo e($t->fields_count); ?></strong>isian form</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex flex-wrap gap-1 justify-content-end">
                        <a href="<?php echo e(route('dashboard.applicants.index', ['jalur' => $t->id])); ?>" class="btn btn-sm btn-light">Pendaftar</a>
                        <a href="<?php echo e(route('dashboard.registration-types.edit', [$t, 'tab' => 'formulir'])); ?>" class="btn btn-sm btn-light"><i class="bi bi-ui-checks"></i> Formulir</a>
                        <a href="<?php echo e(route('dashboard.registration-types.edit', $t)); ?>" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Info</a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12"><div class="card dash-card"><?php echo $__env->make('dashboard.partials.empty', ['text' => 'Belum ada jalur pendaftaran.', 'action' => route('dashboard.registration-types.create'), 'actionLabel' => 'Buat jalur'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/registration-types/index.blade.php ENDPATH**/ ?>