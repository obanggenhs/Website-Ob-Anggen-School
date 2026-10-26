<aside class="dash-sidebar" id="dashSidebar">
    <a href="<?php echo e(route('dashboard.home')); ?>" class="dash-brand">
        <img src="<?php echo e(media_url(setting('logo_main'))); ?>" alt="">
        <span><strong><?php echo e(setting('site_name')); ?></strong><small>Dashboard pengelola</small></span>
    </a>

    <div class="dash-role">
        <i class="bi <?php echo e($theme['icon']); ?>"></i>
        <span><strong><?php echo e($theme['label']); ?></strong><small><?php echo e($theme['panel']); ?></small></span>
    </div>

    <nav class="dash-nav" aria-label="Menu dashboard">
        <?php $__currentLoopData = $sidebarGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($group['label']): ?>
                <div class="dash-nav__label"><?php echo e($group['label']); ?></div>
            <?php endif; ?>
            <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($item['url']); ?>" class="dash-nav__link <?php echo e($item['active'] ? 'active' : ''); ?>">
                    <i class="bi <?php echo e($item['icon']); ?>"></i>
                    <span class="min-w-0">
                        <?php echo e($item['label']); ?>

                        <?php if(! empty($item['hint'])): ?><span class="dash-nav__hint"><?php echo e($item['hint']); ?></span><?php endif; ?>
                    </span>
                    <?php if($item['badge']): ?><span class="badge rounded-pill text-bg-warning"><?php echo e($item['badge']); ?></span><?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <div class="dash-sidebar__foot">Masuk sebagai <?php echo e(auth()->user()->name); ?></div>
</aside>
<?php /**PATH S:\oa-school\resources\views/dashboard/partials/sidebar.blade.php ENDPATH**/ ?>