
<div class="card dash-card mt-4">
    <div class="card-header"><h2><i class="bi bi-lightning-charge me-1"></i> Kirim tes</h2></div>
    <div class="card-body">
        <p class="small text-muted-d">Simpan pengaturan di atas lebih dulu, lalu kirim pesan percobaan. Bila gagal, alasannya tampil di layar.</p>
        <div class="row g-3">
            <div class="col-md-6">
                <form method="POST" action="<?php echo e(route('dashboard.settings.test', 'email')); ?>">
                    <?php echo csrf_field(); ?>
                    <label class="form-label" for="test_mail">Tes email ke</label>
                    <div class="input-group">
                        <input type="email" name="to" id="test_mail" class="form-control" value="<?php echo e(setting('reg_notify_email')); ?>" required>
                        <button class="btn btn-primary"><i class="bi bi-envelope-check"></i> Kirim</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST" action="<?php echo e(route('dashboard.settings.test', 'whatsapp')); ?>">
                    <?php echo csrf_field(); ?>
                    <label class="form-label" for="test_wa">Tes WhatsApp ke</label>
                    <div class="input-group">
                        <input type="text" name="to" id="test_wa" class="form-control" value="<?php echo e(setting('reg_notify_wa')); ?>" placeholder="0812xxxxxxx" required>
                        <button class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/settings/_test-send.blade.php ENDPATH**/ ?>