<div class="oa-sidebar">
    <?php if(\App\Models\Menu::moduleEnabled('berita') && $sidebarNews->isNotEmpty()): ?>
        <div class="oa-widget">
            <h2 class="oa-widget__title">Berita terbaru</h2>
            <?php $__currentLoopData = $sidebarNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="oa-mini-post">
                    <img src="<?php echo e($item->cover_url); ?>" alt="" loading="lazy">
                    <span class="min-w-0">
                        <strong><?php echo e($item->title); ?></strong>
                        <small><i class="bi bi-calendar3"></i> <?php echo e(tgl($item->published_at, 'd M Y')); ?></small>
                    </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('news.index')); ?>" class="oa-link-more mt-2">Semua berita <i class="bi bi-arrow-right"></i></a>
        </div>
    <?php endif; ?>

    <div class="oa-widget">
        <h2 class="oa-widget__title">Hubungi kami</h2>
        <ul class="oa-contact-list">
            <li>
                <a href="<?php echo e(setting('maps_url') ?: 'https://maps.google.com/?q='.urlencode(setting('contact_address'))); ?>" target="_blank" rel="noopener">
                    <i class="bi bi-geo-alt"></i><span><small>Alamat</small><?php echo e(setting('contact_address')); ?></span>
                </a>
            </li>
            <?php if(setting('contact_phone')): ?>
                <li><a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', setting('contact_phone'))); ?>"><i class="bi bi-telephone"></i><span><small>Telepon</small><?php echo e(setting('contact_phone')); ?></span></a></li>
            <?php endif; ?>
            <?php if(setting('contact_whatsapp')): ?>
                <li><a href="<?php echo e(wa_link(setting('contact_whatsapp'), 'Halo '.setting('site_name'))); ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i><span><small>WhatsApp</small><?php echo e(setting('contact_whatsapp')); ?></span></a></li>
            <?php endif; ?>
            <?php if(setting('contact_email')): ?>
                <li><a href="mailto:<?php echo e(setting('contact_email')); ?>"><i class="bi bi-envelope"></i><span><small>Email</small><?php echo e(setting('contact_email')); ?></span></a></li>
            <?php endif; ?>
        </ul>
        <div class="mt-3"><?php echo $__env->make('web.partials.social', ['class' => 'oa-social-btns'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    </div>

    <?php echo $__env->yieldPushContent('sidebar'); ?>
</div>
<?php /**PATH S:\oa-school\resources\views/web/partials/sidebar.blade.php ENDPATH**/ ?>