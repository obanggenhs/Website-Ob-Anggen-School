<?php $__env->startSection('title', $menu?->title ?? 'Berita'); ?>
<?php $__env->startSection('page_title', $category?->name ?? ($menu?->title ?? 'Berita')); ?>

<?php $__env->startSection('page'); ?>
    
    <?php if (isset($component)) { $__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.list-toolbar','data' => ['opt' => $opt,'type' => 'berita','in' => $in,'years' => $years,'result' => $news,'noun' => 'berita','chips' => ['param' => 'kategori', 'current' => $category?->slug ?? '', 'all' => 'Semua',
                 'items' => $categories->where('news_count', '>', 0)->pluck('name', 'slug')->all()],'hidden' => ['kanal' => $channel],'placeholder' => 'Cari berita...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($opt),'type' => 'berita','in' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($in),'years' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($years),'result' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($news),'noun' => 'berita','chips' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['param' => 'kategori', 'current' => $category?->slug ?? '', 'all' => 'Semua',
                 'items' => $categories->where('news_count', '>', 0)->pluck('name', 'slug')->all()]),'hidden' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['kanal' => $channel]),'placeholder' => 'Cari berita...']); ?>
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

    <?php if($news->isEmpty()): ?>
        <div class="oa-empty"><i class="bi bi-newspaper"></i>Belum ada berita<?php echo e($in['cari'] !== '' || $in['tahun'] !== '' ? ' yang cocok dengan pencarian' : ''); ?>.</div>
    <?php else: ?>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 oa-results <?php echo e($in['tampil'] === 'daftar' ? 'is-list' : ''); ?>">
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col"><?php echo $__env->make('web.news._card', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-4 <?php echo e($opt['count'] ? 'oa-pager--counted' : ''); ?>"><?php echo e($news->links()); ?></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-full', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/news/index.blade.php ENDPATH**/ ?>