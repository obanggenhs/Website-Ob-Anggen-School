<?php $__env->startSection('title', $item->title); ?>
<?php $__env->startSection('page_title', $item->title); ?>
<?php $__env->startSection('page_subtitle', $item->excerpt); ?>
<?php $__env->startSection('page_banner', $item->image_url); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('uniqueness.index')); ?>"><?php echo e($menu?->title ?? 'Keunikan'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e($item->title); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('no_intro', true); ?>

<?php $__env->startSection('page'); ?>
    <article class="oa-panel">
        <?php if($item->video_url): ?>
            <div class="mb-3">
                <?php echo $__env->make('web.partials.video', ['url' => $item->video_url, 'title' => $item->title], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endif; ?>
        <div class="prose"><?php echo $item->content; ?></div>

        <?php if(! empty($item->photos)): ?>
            <h2 class="h6 mt-3 mb-2">Dokumentasi</h2>
            <div class="oa-gallery">
                <?php $__currentLoopData = $item->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(media_url($photo)); ?>" class="glightbox" data-gallery="keunikan"><img src="<?php echo e(media_url($photo)); ?>" alt="" loading="lazy"></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </article>

    <?php if($others->isNotEmpty()): ?>
        <div class="oa-sechead mt-4"><h2>Keunikan lainnya</h2></div>
        <div class="oa-chips">
            <?php $__currentLoopData = $others; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('uniqueness.show', $o->slug)); ?>" class="oa-chip"><i class="bi <?php echo e($o->icon ?: 'bi-stars'); ?>"></i> <?php echo e($o->title); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/uniqueness/show.blade.php ENDPATH**/ ?>