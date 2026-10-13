
<?php
    $list = \App\Support\CardCollection::listing($b, request());
    $cards = $list['items'];
    $columns = (int) $b->cardSetting('columns');
    $cols = [2 => 'row-cols-1 row-cols-sm-2', 3 => 'row-cols-1 row-cols-sm-2 row-cols-lg-3', 4 => 'row-cols-2 row-cols-md-3 row-cols-lg-4', 6 => 'row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6'][$columns] ?? 'row-cols-2 row-cols-md-3 row-cols-lg-4';
    $menuSlug = $b->menu->slug;
    $isList = $list['input']['tampil'] === 'daftar';
?>

<?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
<?php if(strip_tags((string) $b->body) !== ''): ?><div class="prose mb-3"><?php echo $b->body; ?></div><?php endif; ?>

<?php if (isset($component)) { $__componentOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8bc177ea0842ad0f82ca4b6fef1cba41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.list-toolbar','data' => ['opt' => $list['options'],'type' => 'halaman','in' => $list['input'],'chips' => ['param' => 'kategori', 'current' => $list['category'], 'items' => $list['categories']->mapWithKeys(fn ($c) => [$c => $c])->all(), 'all' => 'Semua'],'years' => $list['years'],'result' => $cards,'noun' => 'data','scope' => $list['scope'],'pageName' => $list['pageName'],'anchor' => 'koleksi-'.$b->id,'placeholder' => 'Cari nama, angkatan, tahun...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['opt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($list['options']),'type' => 'halaman','in' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($list['input']),'chips' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['param' => 'kategori', 'current' => $list['category'], 'items' => $list['categories']->mapWithKeys(fn ($c) => [$c => $c])->all(), 'all' => 'Semua']),'years' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($list['years']),'result' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cards),'noun' => 'data','scope' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($list['scope']),'pageName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($list['pageName']),'anchor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('koleksi-'.$b->id),'placeholder' => 'Cari nama, angkatan, tahun...']); ?>
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

<?php if($cards->isEmpty()): ?>
    <div class="oa-empty">
        <i class="bi <?php echo e($list['filtered'] ? 'bi-search' : 'bi-grid-3x3-gap'); ?>"></i>
        <?php echo e($list['filtered'] ? 'Tidak ada data yang cocok dengan pencarian atau filter.' : 'Belum ada isi.'); ?>

    </div>
<?php else: ?>
    <div class="row g-3 oa-results <?php echo e($cols); ?> <?php echo e($isList ? 'is-list' : ''); ?>">
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col">
                <?php echo $__env->make('web.partials.collection-card', [
                    'card' => $card, 'url' => route('page.item', [$menuSlug, $card->slug]),
                    'shape' => $b->cardSetting('shape'), 'excerpt' => (bool) $b->cardSetting('excerpt'), 'button' => $b->cardSetting('button'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php if($cards->hasPages()): ?>
        <div class="mt-3 <?php echo e($list['options']['count'] ? 'oa-pager--counted' : ''); ?>"><?php echo e($cards->fragment('koleksi-'.$b->id)->links()); ?></div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH S:\oa-school\resources\views/web/partials/card-collection.blade.php ENDPATH**/ ?>