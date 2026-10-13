
<?php if(! empty($media) && $media['type'] !== 'image'): ?>
    <div class="oa-headmedia oa-headmedia--<?php echo e($media['type']); ?>" aria-hidden="true" data-headmedia>
        <?php switch($media['type']):
            case ('video'): ?>
                <video src="<?php echo e($media['src']); ?>" <?php if(! empty($media['poster'])): ?> poster="<?php echo e($media['poster']); ?>" <?php endif; ?>
                       autoplay muted loop playsinline preload="auto" disablepictureinpicture tabindex="-1"></video>
                <?php break; ?>

            <?php case ('youtube'): ?>
                <iframe src="<?php echo e($media['src']); ?>" title="Video latar" tabindex="-1" loading="lazy"
                        allow="autoplay; encrypted-media; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                <?php break; ?>

            <?php case ('slider'): ?>
                <?php $__currentLoopData = $media['slides']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="oa-headmedia__slide <?php echo e($i === 0 ? 'is-active' : ''); ?>" style="background-image: url('<?php echo e($slide); ?>')"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($media['slides']) > 1): ?>
                    <div class="oa-headmedia__dots">
                        <?php $__currentLoopData = $media['slides']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="<?php echo e($i === 0 ? 'is-active' : ''); ?>"></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
                <?php break; ?>
        <?php endswitch; ?>
    </div>
<?php endif; ?><?php /**PATH S:\oa-school\resources\views/web/partials/header-media.blade.php ENDPATH**/ ?>