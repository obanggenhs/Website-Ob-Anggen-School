
<form method="POST" action="<?php echo e($url); ?>" class="d-inline" data-confirm="<?php echo e($confirm ?? 'Hapus '.($label ?? 'data ini').'? Tindakan ini tidak bisa dibatalkan.'); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit" class="btn btn-sm <?php echo e($class ?? 'btn-outline-danger btn-icon'); ?>" title="Hapus" data-bs-toggle="tooltip">
        <i class="bi bi-trash"></i><?php if(isset($text)): ?> <?php echo e($text); ?><?php endif; ?>
    </button>
</form>
<?php /**PATH S:\oa-school\resources\views/dashboard/partials/delete.blade.php ENDPATH**/ ?>