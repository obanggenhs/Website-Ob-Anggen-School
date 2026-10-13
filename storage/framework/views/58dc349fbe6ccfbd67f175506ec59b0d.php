<footer class="oa-footer">
    <div class="container oa-container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4">
                <a href="<?php echo e(route('home')); ?>" class="oa-footer__brand">
                    <img src="<?php echo e(media_url(setting('logo_main'))); ?>" alt="">
                    <strong><?php echo e(setting('site_name')); ?></strong>
                </a>
                <p><?php echo e(setting('footer_about')); ?></p>
                <?php echo $__env->make('web.partials.social', ['class' => 'oa-social-btns'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <?php $__currentLoopData = $navMenus->filter(fn ($m) => $m->isGroup() && $m->children->isNotEmpty())->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-lg-2">
                    <h3><?php echo e($group->title); ?></h3>
                    <ul class="oa-footer__links">
                        <?php $__currentLoopData = $group->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e($child->link); ?>" target="<?php echo e($child->target); ?>"><?php echo e($child->title); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="col-6 col-lg-2">
                <h3>Kontak</h3>
                <ul class="oa-footer__links">
                    <?php if(setting('contact_phone')): ?><li><a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', setting('contact_phone'))); ?>"><?php echo e(setting('contact_phone')); ?></a></li><?php endif; ?>
                    <?php if(setting('contact_email')): ?><li><a href="mailto:<?php echo e(setting('contact_email')); ?>"><?php echo e(setting('contact_email')); ?></a></li><?php endif; ?>
                    <li><?php echo e(setting('contact_address')); ?></li>
                </ul>
            </div>
        </div>

        <div class="oa-footer__bottom">
            <span>&copy; <?php echo e(date('Y')); ?> <?php echo e(setting('site_name')); ?>. Hak cipta dilindungi.</span>
            <a href="<?php echo e(route('login')); ?>"><i class="bi bi-lock"></i> Masuk admin</a>
        </div>
    </div>
</footer>
<?php /**PATH S:\oa-school\resources\views/web/partials/footer.blade.php ENDPATH**/ ?>