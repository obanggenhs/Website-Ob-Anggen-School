<?php
    // Grup dropdown tanpa submenu tidak ditampilkan
    $visibleMenus = $navMenus->reject(fn ($m) => $m->isGroup() && $m->children->isEmpty())->values();
    $menusNormal = $visibleMenus->reject(fn ($m) => $m->is_highlight)->values();
    $menusHighlight = $visibleMenus->filter(fn ($m) => $m->is_highlight)->values();
    $phone = setting('contact_phone');
    $email = setting('contact_email');
?>

<header class="oa-header" id="oaHeader">
    <div class="oa-topbar d-none d-lg-block">
        <div class="container oa-container d-flex justify-content-between align-items-center gap-3">
            <div class="oa-topbar__info">
                <?php if($phone): ?>
                    <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="oa-topbar__item"><i class="bi bi-telephone"></i> <?php echo e($phone); ?></a>
                <?php endif; ?>
                <?php if($email): ?>
                    <a href="mailto:<?php echo e($email); ?>" class="oa-topbar__item"><i class="bi bi-envelope"></i> <?php echo e($email); ?></a>
                <?php endif; ?>
                <span class="oa-topbar__item"><i class="bi bi-geo-alt"></i> <?php echo e(\Illuminate\Support\Str::limit(setting('contact_address'), 48)); ?></span>
            </div>
            <?php echo $__env->make('web.partials.social', ['class' => 'oa-social'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <nav class="navbar navbar-expand-xl oa-navbar" aria-label="Navigasi utama">
        <div class="container oa-container oa-navbar__inner">
            
            <a class="navbar-brand oa-brand" href="<?php echo e(route('home')); ?>" title="<?php echo e(setting('site_name')); ?>">
                <span class="oa-brand__logos">
                    <?php $__currentLoopData = ['logo_sd' => 'SD', 'logo_smp' => 'SMP', 'logo_sma' => 'SMA']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $alt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(media_url(setting($key))); ?>" alt="Logo <?php echo e($alt); ?>" width="30" height="30">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </span>
                <span class="oa-brand__text">
                    <strong><?php echo e(setting('site_name')); ?></strong>
                    <small><?php echo e(setting('site_tagline')); ?></small>
                </span>
            </a>

            <button class="navbar-toggler oa-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#oaNav" aria-controls="oaNav" aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>

            <div class="offcanvas offcanvas-end oa-offcanvas" tabindex="-1" id="oaNav" aria-labelledby="oaNavLabel">
                <div class="offcanvas-header">
                    <a href="<?php echo e(route('home')); ?>" class="oa-offcanvas__brand" id="oaNavLabel">
                        <img src="<?php echo e(media_url(setting('logo_main'))); ?>" alt="" width="34" height="34">
                        <strong><?php echo e(setting('site_name')); ?></strong>
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
                </div>

                <div class="offcanvas-body oa-navbar__body">
                    
                    <ul class="navbar-nav oa-nav-main" data-nav-main>
                        <?php $__currentLoopData = $menusNormal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('web.partials.nav-item', ['menu' => $item, 'index' => $i], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <li class="nav-item dropdown oa-nav-more" data-nav-more hidden>
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-grid-3x3-gap"></i><span class="oa-nav-label">Lainnya</span>
                            </a>
                            <ul class="dropdown-menu oa-nav-more__menu">
                                <?php $__currentLoopData = $menusNormal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($item->isGroup()): ?>
                                        <li data-more="<?php echo e($i); ?>" hidden><h6 class="dropdown-header"><i class="bi <?php echo e($item->icon ?: $item->type_icon); ?>"></i> <?php echo e($item->title); ?></h6></li>
                                        <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li data-more="<?php echo e($i); ?>" hidden>
                                                <a class="dropdown-item oa-nav-more__child <?php echo e($child->isCurrent() ? 'active' : ''); ?>" href="<?php echo e($child->link); ?>" target="<?php echo e($child->target); ?>">
                                                    <i class="bi <?php echo e($child->type_icon); ?>"></i> <?php echo e($child->title); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <li data-more="<?php echo e($i); ?>" hidden>
                                            <a class="dropdown-item <?php echo e($item->isCurrent() ? 'active' : ''); ?>" href="<?php echo e($item->link); ?>" target="<?php echo e($item->target); ?>">
                                                <i class="bi <?php echo e($item->icon ?: $item->type_icon); ?>"></i> <?php echo e($item->title); ?>

                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                    </ul>

                    
                    <?php if($menusHighlight->isNotEmpty()): ?>
                        <ul class="navbar-nav oa-nav-actions">
                            <?php $__currentLoopData = $menusHighlight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('web.partials.nav-item', ['menu' => $item, 'cta' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>

                    <div class="oa-offcanvas__contact d-xl-none">
                        <?php if($phone): ?><a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $phone)); ?>"><i class="bi bi-telephone me-2"></i><?php echo e($phone); ?></a><?php endif; ?>
                        <?php if($email): ?><a href="mailto:<?php echo e($email); ?>"><i class="bi bi-envelope me-2"></i><?php echo e($email); ?></a><?php endif; ?>
                        <?php echo $__env->make('web.partials.social', ['class' => 'oa-social'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header><?php /**PATH S:\oa-school\resources\views/web/partials/navbar.blade.php ENDPATH**/ ?>