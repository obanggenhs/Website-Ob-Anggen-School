<?php $__env->startSection('title', $menu?->title ?? 'Kelulusan'); ?>
<?php $__env->startSection('page_title', ($menu?->title ?? 'Kelulusan').($year ? ' '.$year : '')); ?>

<?php $__env->startSection('page'); ?>
    <?php
        // Tombol angkatan (bawaan sama seperti sebelumnya) + "Semua angkatan"
        $yearChips = $years->mapWithKeys(fn ($y) => [$y => 'Angkatan '.$y])->all();
        if ($years->count() > 1) {
            $yearChips['semua'] = 'Semua angkatan';
        }
    ?>

    
    <?php if (isset($component)) { $__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.list-toolbar','data' => ['opt' => $opt,'type' => 'kelulusan','in' => array_merge($in, ['tahun' => '']),'result' => $graduates,'noun' => 'lulusan','placeholder' => 'Cari nama, kampus...','chips' => $opt['year'] ? ['param' => 'tahun', 'current' => $allYears ? 'semua' : (string) $year, 'items' => $yearChips, 'always' => true] : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opt),'type' => 'kelulusan','in' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(array_merge($in, ['tahun' => ''])),'result' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($graduates),'noun' => 'lulusan','placeholder' => 'Cari nama, kampus...','chips' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opt['year'] ? ['param' => 'tahun', 'current' => $allYears ? 'semua' : (string) $year, 'items' => $yearChips, 'always' => true] : null)]); ?>
        <?php if($opt['filter']): ?>
            <select name="jenjang" class="form-select form-select-sm rounded-pill" data-autosubmit aria-label="Jenjang">
                <option value="">Semua jenjang</option>
                <?php $__currentLoopData = \App\Models\Graduate::LEVELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php if($level === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        <?php elseif($level): ?>
            <input type="hidden" name="jenjang" value="<?php echo e($level); ?>">
        <?php endif; ?>
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

    <?php if($graduates->isEmpty()): ?>
        <div class="oa-empty">
            <i class="bi bi-mortarboard"></i>
            <?php if($in['cari'] !== ''): ?>
                Tidak ada lulusan yang cocok dengan "<?php echo e($in['cari']); ?>"<?php echo e($year ? ' di angkatan '.$year : ''); ?>.
                <?php if($year && $years->count() > 1): ?>
                    <div class="mt-2"><a href="<?php echo e(request()->fullUrlWithQuery(['tahun' => 'semua', 'page' => null])); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Cari di semua angkatan</a></div>
                <?php endif; ?>
            <?php else: ?>
                Belum ada data lulusan.
            <?php endif; ?>
        </div>
    <?php else: ?>
        
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 oa-results <?php echo e($in['tampil'] === 'daftar' ? 'is-list' : ''); ?>">
            <?php $__currentLoopData = $graduates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col">
                    <a href="<?php echo e(route('graduates.show', $g->slug)); ?>" class="oa-card oa-card--mini oa-person">
                        <div class="oa-card__img oa-card__img--portrait"><img src="<?php echo e($g->photo_url); ?>" alt="<?php echo e($g->name); ?>" loading="lazy"></div>
                        <div class="oa-card__body">
                            <span class="oa-badge"><?php echo e($g->level_label); ?> <?php echo e($g->class_name); ?></span>
                            <h3 class="oa-card__title"><?php echo e($g->name); ?></h3>
                            <?php if($g->continue_to): ?><span class="oa-card__meta justify-content-center"><span class="text-truncate" style="max-width: 100%"><i class="bi bi-signpost-2"></i><?php echo e($g->continue_to); ?></span></span><?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-4 <?php echo e($opt['count'] ? 'oa-pager--counted' : ''); ?>"><?php echo e($graduates->links()); ?></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-full', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/graduates/index.blade.php ENDPATH**/ ?>