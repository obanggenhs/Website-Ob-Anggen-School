
<?php
    $isCta = ! empty($cta);
    $long = mb_strlen($menu->title) > 22;
?>

<?php if($menu->isGroup()): ?>
    <?php if($menu->children->isNotEmpty()): ?>
        <li class="nav-item dropdown <?php echo e($isCta ? 'oa-nav-cta' : ''); ?>" <?php if(isset($index)): ?> data-nav-index="<?php echo e($index); ?>" <?php endif; ?>>
            <a class="nav-link dropdown-toggle <?php echo e($menu->isCurrent() ? 'active' : ''); ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" <?php if($long): ?> title="<?php echo e($menu->title); ?>" <?php endif; ?>>
                <?php if($menu->icon): ?><i class="bi <?php echo e($menu->icon); ?>"></i><?php endif; ?><span class="oa-nav-label"><?php echo e($menu->title); ?></span>
            </a>
            <ul class="dropdown-menu <?php echo e($isCta ? 'dropdown-menu-end' : ''); ?>">
                <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a class="dropdown-item <?php echo e($child->isCurrent() ? 'active' : ''); ?>" href="<?php echo e($child->link); ?>" target="<?php echo e($child->target); ?>">
                            <i class="bi <?php echo e($child->type_icon); ?>"></i> <?php echo e($child->title); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </li>
    <?php endif; ?>
<?php else: ?>
    <li class="nav-item <?php echo e($isCta ? 'oa-nav-cta' : ''); ?>" <?php if(isset($index)): ?> data-nav-index="<?php echo e($index); ?>" <?php endif; ?>>
        <a class="nav-link <?php echo e($menu->isCurrent() ? 'active' : ''); ?>" href="<?php echo e($menu->link); ?>" target="<?php echo e($menu->target); ?>" <?php if($long): ?> title="<?php echo e($menu->title); ?>" <?php endif; ?>>
            <?php if($menu->icon): ?><i class="bi <?php echo e($menu->icon); ?>"></i><?php endif; ?><span class="oa-nav-label"><?php echo e($menu->title); ?></span>
        </a>
    </li>
<?php endif; ?><?php /**PATH S:\oa-school\resources\views/web/partials/nav-item.blade.php ENDPATH**/ ?>