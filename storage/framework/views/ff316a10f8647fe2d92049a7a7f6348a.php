<div class="tree-row <?php echo e(isset($menu) && $menu?->id === $item->id ? 'is-current' : ''); ?> <?php echo e($item->is_active ? '' : 'is-hidden'); ?>">
    <span class="tree-handle" title="Seret untuk memindah"><i class="bi bi-grip-vertical"></i></span>
    <a href="<?php echo e(route('dashboard.studio.show', $item)); ?>" class="tree-link" title="<?php echo e($item->type_label); ?>">
        <i class="bi <?php echo e($item->type_icon); ?>"></i>
        <span><?php echo e($item->title); ?></span>
    </a>
    <?php if($item->is_highlight): ?><i class="bi bi-star-fill text-warning small"></i><?php endif; ?>
    <?php if (! ($item->is_active)): ?><i class="bi bi-eye-slash text-muted-d small"></i><?php endif; ?>
    <?php $__currentLoopData = $item->roleBadges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="role-chip role-chip--<?php echo e($role); ?>"><?php echo e($role === 'publikasi' ? 'PUB' : 'DAF'); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->isPage()): ?>
        <span class="tree-type"><?php echo e($item->blocks_count ?? $item->blocks()->count()); ?> blok</span>
    <?php endif; ?>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_tree-row.blade.php ENDPATH**/ ?>