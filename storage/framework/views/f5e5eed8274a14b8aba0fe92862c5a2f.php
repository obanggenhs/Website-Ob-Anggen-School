<?php $__env->startSection('title', $menu?->title ?? \App\Models\Facility::TYPES[$jenis]); ?>
<?php $__env->startSection('page_title', ($menu?->title ?? \App\Models\Facility::TYPES[$jenis]).($branch ? ' · '.$branch->name : '')); ?>

<?php $__env->startSection('page'); ?>
    <div class="oa-filterbar">
        <div class="oa-chips">
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('facilities.index', ['jenis' => $jenis, 'kampus' => $b->id])); ?>" class="oa-chip <?php echo e($branch?->id === $b->id ? 'active' : ''); ?>"><i class="bi bi-geo-alt"></i> <?php echo e($b->name); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if($levels->count() > 1): ?>
            <div class="oa-chips">
                <a href="<?php echo e(route('facilities.index', ['jenis' => $jenis, 'kampus' => $branch?->id])); ?>" class="oa-chip <?php echo e(! $level ? 'active' : ''); ?>">Semua</a>
                <?php $__currentLoopData = \App\Models\Facility::LEVELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($levels->contains($key)): ?>
                        <a href="<?php echo e(route('facilities.index', ['jenis' => $jenis, 'kampus' => $branch?->id, 'jenjang' => $key])); ?>" class="oa-chip <?php echo e($level === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($facilities->isEmpty()): ?>
        <div class="oa-empty"><i class="bi bi-building"></i>Belum ada data fasilitas untuk pilihan ini.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-6 col-lg-4 col-xl-3" data-reveal>
                    <a href="<?php echo e(route('facilities.show', $f->slug)); ?>" class="oa-card">
                        <div class="oa-card__img">
                            <img src="<?php echo e($f->image_url); ?>" alt="<?php echo e($f->name); ?>" loading="lazy">
                            <span class="oa-card__badge oa-badge"><?php echo e($f->level_label); ?></span>
                        </div>
                        <div class="oa-card__body">
                            <h3 class="oa-card__title"><?php echo e($f->name); ?></h3>
                            <span class="oa-card__meta">
                                <?php if($f->label): ?><span><i class="bi bi-door-open"></i><?php echo e($f->label); ?></span><?php endif; ?>
                                <?php if($f->capacity): ?><span><i class="bi bi-people"></i><?php echo e($f->capacity); ?></span><?php endif; ?>
                            </span>
                            <p class="oa-card__text"><?php echo e($f->description); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-full', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/facilities/index.blade.php ENDPATH**/ ?>