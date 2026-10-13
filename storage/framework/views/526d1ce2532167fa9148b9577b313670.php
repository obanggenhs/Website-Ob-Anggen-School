<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('web.partials.page-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="oa-section oa-section--tight">
        <div class="container oa-container">
            <?php echo $__env->make('web.partials.intro', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('web.partials.extra-blocks', ['place' => 'atas'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->yieldContent('page'); ?>
            <?php echo $__env->make('web.partials.extra-blocks', ['place' => 'bawah'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </section>

    <?php echo $__env->yieldContent('after'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/layouts/web-full.blade.php ENDPATH**/ ?>