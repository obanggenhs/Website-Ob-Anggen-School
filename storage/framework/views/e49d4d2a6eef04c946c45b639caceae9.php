<div class="dash-empty">
    <i class="bi <?php echo e($icon ?? 'bi-inbox'); ?>"></i>
    <div><?php echo e($text ?? 'Belum ada data.'); ?></div>
    <?php if(isset($action)): ?><a href="<?php echo e($action); ?>" class="btn btn-sm btn-primary mt-3"><i class="bi bi-plus-lg"></i> <?php echo e($actionLabel ?? 'Tambah'); ?></a><?php endif; ?>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/partials/empty.blade.php ENDPATH**/ ?>