
<?php
    $mailOn = (bool) setting('reg_send_mail');
    $waOn = \App\Support\WhatsAppSender::enabled();
?>
<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="send_email" value="0">
        <input class="form-check-input" type="checkbox" name="send_email" id="send_email<?php echo e($n); ?>" value="1" <?php if($mailOn): echo 'checked'; endif; ?> <?php if(! $mailOn): echo 'disabled'; endif; ?>>
        <label class="form-check-label small" for="send_email<?php echo e($n); ?>">
            <?php echo e($mailText); ?> <strong><?php echo e($r->email); ?></strong>
            <?php if (! ($mailOn)): ?><span class="text-danger">(dimatikan di Pengaturan)</span><?php endif; ?>
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="send_wa" value="0">
        <input class="form-check-input" type="checkbox" name="send_wa" id="send_wa<?php echo e($n); ?>" value="1" <?php if($waOn): echo 'checked'; endif; ?> <?php if(! $waOn): echo 'disabled'; endif; ?>>
        <label class="form-check-label small" for="send_wa<?php echo e($n); ?>">
            <?php echo e($waText); ?> <strong><?php echo e($r->phone); ?></strong>
            <?php if (! ($waOn)): ?><span class="text-danger">(dimatikan di Pengaturan)</span><?php endif; ?>
        </label>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/applicants/_channels.blade.php ENDPATH**/ ?>