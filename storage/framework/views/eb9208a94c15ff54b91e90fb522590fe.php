<?php if($active): ?>
    <span class="badge text-bg-success-subtle text-success-emphasis"><i class="bi bi-eye"></i> <?php echo e($on ?? 'Tampil'); ?></span>
<?php else: ?>
    <span class="badge text-bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-eye-slash"></i> <?php echo e($off ?? 'Disembunyikan'); ?></span>
<?php endif; ?>
<?php /**PATH S:\oa-school\resources\views/dashboard/partials/status.blade.php ENDPATH**/ ?>