<?php
    $links = [
        'social_facebook'  => 'bi-facebook',
        'social_instagram' => 'bi-instagram',
        'social_youtube'   => 'bi-youtube',
        'social_tiktok'    => 'bi-tiktok',
    ];
?>
<span class="<?php echo e($class ?? 'oa-social'); ?>">
    <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(setting($key)): ?>
            <a href="<?php echo e(setting($key)); ?>" target="_blank" rel="noopener" aria-label="<?php echo e(ucfirst(str_replace('social_', '', $key))); ?>"><i class="bi <?php echo e($icon); ?>"></i></a>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if(setting('contact_whatsapp')): ?>
        <a href="<?php echo e(wa_link(setting('contact_whatsapp'), 'Halo '.setting('site_name').', saya ingin bertanya.')); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
    <?php endif; ?>
</span>
<?php /**PATH S:\oa-school\resources\views/web/partials/social.blade.php ENDPATH**/ ?>