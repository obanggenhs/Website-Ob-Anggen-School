<?php $__env->startSection('title', $facility->name); ?>
<?php $__env->startSection('page_title', $facility->name); ?>
<?php $__env->startSection('page_subtitle', ($facility->label ? $facility->label.' · ' : '').($facility->branch?->name ? 'Kampus '.$facility->branch->name : '')); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('facilities.index', ['jenis' => $facility->type, 'kampus' => $facility->branch_id])); ?>"><?php echo e($menu?->title ?? \App\Models\Facility::TYPES[$facility->type]); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e($facility->name); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('no_intro', true); ?>

<?php $__env->startSection('page'); ?>
    <div class="oa-panel">
        <a href="<?php echo e($facility->image_url); ?>" class="glightbox" data-gallery="fasilitas">
            <img src="<?php echo e($facility->image_url); ?>" alt="<?php echo e($facility->name); ?>" class="oa-cover mb-3">
        </a>
        <div class="row g-4">
            <div class="col-md-7">
                <div class="prose"><?php echo $facility->detail ?: e($facility->description); ?></div>
            </div>
            <div class="col-md-5">
                <ul class="oa-info-list">
                    <li><span>Kampus</span><span><?php echo e($facility->branch?->name ?? '-'); ?></span></li>
                    <li><span>Jenjang</span><span><?php echo e($facility->level_label); ?></span></li>
                    <?php if($facility->capacity): ?><li><span>Kapasitas</span><span><?php echo e($facility->capacity); ?></span></li><?php endif; ?>
                    <?php if($facility->area): ?><li><span>Luas</span><span><?php echo e($facility->area); ?></span></li><?php endif; ?>
                    <?php if($facility->used_for): ?><li><span>Digunakan untuk</span><span><?php echo e($facility->used_for); ?></span></li><?php endif; ?>
                </ul>
            </div>
        </div>
        <?php if(! empty($facility->photos)): ?>
            <div class="oa-gallery mt-3">
                <?php $__currentLoopData = $facility->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(media_url($photo)); ?>" class="glightbox" data-gallery="fasilitas"><img src="<?php echo e(media_url($photo)); ?>" alt="" loading="lazy"></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($others->isNotEmpty()): ?>
        <div class="oa-sechead mt-4"><h2>Fasilitas lain di kampus ini</h2></div>
        <div class="row g-3">
            <?php $__currentLoopData = $others; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-3">
                    <a href="<?php echo e(route('facilities.show', $o->slug)); ?>" class="oa-card">
                        <div class="oa-card__img"><img src="<?php echo e($o->image_url); ?>" alt="<?php echo e($o->name); ?>" loading="lazy"></div>
                        <div class="oa-card__body"><h3 class="oa-card__title"><?php echo e($o->name); ?></h3></div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/facilities/show.blade.php ENDPATH**/ ?>