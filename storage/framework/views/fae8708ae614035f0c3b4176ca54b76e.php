<?php $__env->startSection('title', $menu?->title ?? 'Keunikan'); ?>

<?php $__env->startSection('page'); ?>
    
    <?php if (isset($component)) { $__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.list-toolbar','data' => ['opt' => $opt,'type' => 'keunikan','in' => $in,'result' => $items,'noun' => 'keunikan','placeholder' => 'Cari keunikan...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opt),'type' => 'keunikan','in' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($in),'result' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($items),'noun' => 'keunikan','placeholder' => 'Cari keunikan...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41)): ?>
<?php $attributes = $__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41; ?>
<?php unset($__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41)): ?>
<?php $component = $__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41; ?>
<?php unset($__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41); ?>
<?php endif; ?>

    <?php if($items->isEmpty()): ?>
        <div class="oa-empty"><i class="bi bi-stars"></i><?php echo e($in['cari'] !== '' ? 'Tidak ada keunikan yang cocok dengan pencarian.' : 'Belum ada data keunikan.'); ?></div>
    <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-sm-6 col-lg-3" data-reveal>
                    <a href="<?php echo e(route('uniqueness.show', $item->slug)); ?>" class="oa-card">
                        <div class="oa-card__img">
                            <img src="<?php echo e($item->image_url); ?>" alt="<?php echo e($item->title); ?>" loading="lazy">
                            <?php if($item->video_url): ?><span class="oa-card__play"><i class="bi bi-play-circle-fill"></i></span><?php endif; ?>
                        </div>
                        <div class="oa-card__body">
                            <h3 class="oa-card__title"><i class="bi <?php echo e($item->icon ?: 'bi-stars'); ?> text-primary me-1"></i> <?php echo e($item->title); ?></h3>
                            <p class="oa-card__text"><?php echo e($item->excerpt); ?></p>
                            <span class="oa-card__foot oa-link-more">Selengkapnya <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('after'); ?>
    <?php if($testimonials->isNotEmpty()): ?>
        <section class="oa-section oa-section--surface">
            <div class="container oa-container">
                <div class="oa-sechead oa-sechead--center"><h2>Ulasan alumni & orang tua</h2></div>
                <div class="row g-3">
                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4" data-reveal>
                            <div class="oa-testi">
                                <p class="oa-testi__quote"><?php echo e($t->content); ?></p>
                                <div class="oa-testi__person">
                                    <img src="<?php echo e($t->photo_url); ?>" alt="<?php echo e($t->name); ?>" loading="lazy">
                                    <span><strong><?php echo e($t->name); ?></strong><small><?php echo e($t->role); ?></small></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/uniqueness/index.blade.php ENDPATH**/ ?>