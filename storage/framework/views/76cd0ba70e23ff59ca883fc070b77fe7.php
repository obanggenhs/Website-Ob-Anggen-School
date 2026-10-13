<?php $__env->startSection('body'); ?>
    <p>Halo <strong><?php echo e($registration->full_name); ?></strong>,</p>
    <p>Status pendaftaran <strong><?php echo e($registration->number); ?></strong> (<?php echo e($registration->type->name); ?>) sekarang:</p>
    <p style="font-size:18px;font-weight:bold;color:#7A2B25"><?php echo e($registration->status_label); ?></p>
    <p><?php echo e($registration->status_info['public']); ?></p>

    <?php if($note): ?>
        <p style="background:#faf6f3;border-left:3px solid #E39B24;padding:10px 14px"><?php echo e($note); ?></p>
    <?php endif; ?>

    <?php if($registration->status === 'diterima' && $registration->type->accepted_message): ?>
        <p><?php echo e($registration->type->accepted_message); ?></p>
    <?php endif; ?>

    <p><a href="<?php echo e(route('registration.status', ['nomor' => $registration->number, 'email' => $registration->email])); ?>" style="color:#7A2B25">Lihat detail di halaman Cek Status</a></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/emails/registration-status.blade.php ENDPATH**/ ?>