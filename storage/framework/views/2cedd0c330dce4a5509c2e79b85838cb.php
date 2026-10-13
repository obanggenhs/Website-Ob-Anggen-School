<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('web.partials.page-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $withSidebar = setting('layout_sidebar') && ($menu->show_sidebar ?? true) && ! View::hasSection('no_sidebar');
    ?>

    <section class="oa-section oa-section--tight">
        <div class="container oa-container">
            <div class="row g-4 g-xl-5">
                <div class="<?php echo e($withSidebar ? 'col-lg-8' : 'col-12'); ?>">
                    <?php echo $__env->make('web.partials.intro', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('web.partials.extra-blocks', ['place' => 'atas'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->yieldContent('page'); ?>
                    <?php echo $__env->make('web.partials.extra-blocks', ['place' => 'bawah'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <?php if($withSidebar): ?>
                    <aside class="col-lg-4">
                        <?php echo $__env->make('web.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </aside>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php echo $__env->yieldContent('after'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/layouts/web-page.blade.php ENDPATH**/ ?>