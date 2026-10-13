<?php $__env->startSection('title', $menu->title); ?>

<?php $__env->startSection('page'); ?>
    <?php if($blocks->isEmpty()): ?>
        <div class="oa-empty"><i class="bi bi-file-earmark-text"></i>Isi halaman ini sedang disiapkan.</div>
    <?php else: ?>
        <div class="row oa-blocks">
            <?php echo $__env->make('web.partials.blocks', ['list' => $blocks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/page.blade.php ENDPATH**/ ?>