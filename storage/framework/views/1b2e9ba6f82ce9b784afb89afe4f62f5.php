<?php $__env->startSection('title', $type->name); ?>
<?php $__env->startSection('page_title', $type->name); ?>
<?php $__env->startSection('page_subtitle', $type->summary); ?>
<?php $__env->startSection('page_banner', $type->image_url); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('registration.index', ['untuk' => $type->audience])); ?>"><?php echo e($menu?->title ?? 'Pendaftaran'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e($type->name); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('no_intro', true); ?>

<?php $__env->startSection('page'); ?>
    <?php $open = $type->isAccepting(); ?>

    <div class="oa-panel mb-4 d-flex flex-wrap gap-3 align-items-center justify-content-between" style="border-left: 4px solid <?php echo e($open ? '#2f9e6a' : '#9a8f8b'); ?>">
        <div>
            <span class="oa-badge <?php echo e($open ? 'oa-badge--green' : 'oa-badge--grey'); ?> mb-1"><?php echo e($type->status_label); ?></span>
            <div class="small text-muted-oa">
                <?php if($type->period_label): ?><i class="bi bi-calendar-range me-1"></i> Periode <?php echo e($type->period_label); ?><?php endif; ?>
                <?php if($type->quota): ?> · Kuota <?php echo e($type->quota); ?> orang <?php endif; ?>
                <?php if($type->academic_year): ?> · Tahun ajaran <?php echo e($type->academic_year); ?> <?php endif; ?>
            </div>
        </div>
        <?php if($open): ?>
            <a href="<?php echo e(route('registration.form', $type->slug)); ?>" class="btn btn-accent btn-lg"><i class="bi bi-pencil-square me-1"></i> Isi formulir</a>
        <?php endif; ?>
    </div>

    <?php if($type->description): ?>
        <div class="oa-panel mb-4">
            <h2 class="h5 mb-3">Tentang jalur ini</h2>
            <div class="prose"><?php echo $type->description; ?></div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if($type->requirements): ?>
            <div class="col-md-6">
                <div class="oa-panel h-100">
                    <h2 class="h6 mb-3"><i class="bi bi-folder-check me-1 text-primary"></i> Dokumen yang disiapkan</h2>
                    <ul class="oa-checklist"><?php $__currentLoopData = lines($type->requirements); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($doc); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                </div>
            </div>
        <?php endif; ?>
        <?php if($type->steps): ?>
            <div class="col-md-6">
                <div class="oa-panel h-100">
                    <h2 class="h6 mb-3"><i class="bi bi-signpost-split me-1 text-primary"></i> Tahapan seleksi</h2>
                    <ol class="oa-steps"><?php $__currentLoopData = lines($type->steps); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($step); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ol>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($others->isNotEmpty()): ?>
        <div class="oa-sechead mt-5"><h2>Jalur lainnya</h2></div>
        <div class="oa-chips">
            <?php $__currentLoopData = $others; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('registration.show', $o->slug)); ?>" class="oa-chip"><?php echo e($o->name); ?> <span class="text-muted-oa">· <?php echo e($o->status_label); ?></span></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/registration/show.blade.php ENDPATH**/ ?>