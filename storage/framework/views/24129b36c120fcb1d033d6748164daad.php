
<?php
    $extraMenu = isset($menu) && $menu instanceof \App\Models\Menu ? $menu : null;
    $extraBlocks = collect();

    if ($extraMenu && ! $extraMenu->isPage() && $extraMenu->supportsBlocks()
        && ! View::hasSection('no_intro') && $extraMenu->blocksAt() === $place) {
        $extraBlocks = $extraMenu->blocks()->where('is_active', true)->where('type', '!=', 'koleksi')->get();
        $extraBlocks->each->setRelation('menu', $extraMenu);
    }
?>

<?php if($extraBlocks->isNotEmpty()): ?>
    <div class="row oa-blocks oa-blocks--extra oa-blocks--<?php echo e($place); ?>">
        <?php echo $__env->make('web.partials.blocks', ['list' => $extraBlocks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php endif; ?>
<?php /**PATH S:\oa-school\resources\views/web/partials/extra-blocks.blade.php ENDPATH**/ ?>