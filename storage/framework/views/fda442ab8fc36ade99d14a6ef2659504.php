<?php $__env->startSection('title', 'Koneksi WhatsApp'); ?>
<?php $__env->startSection('page_title', 'Koneksi WhatsApp'); ?>
<?php $__env->startSection('page_subtitle', 'Hubungkan gateway WhatsApp agar status & pesan admin terkirim otomatis ke nomor pendaftar.'); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.applicants.index')); ?>">Data Pendaftar</a></li>
    <li class="breadcrumb-item active">Koneksi WhatsApp</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-xl-7">
            <form method="POST" action="<?php echo e(route('dashboard.whatsapp.update')); ?>" class="card dash-card">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-whatsapp text-success me-1"></i> Hubungkan gateway</h2>
                    <?php if($connected): ?>
                        <span class="badge text-bg-success"><i class="bi bi-check-circle"></i> Terhubung</span>
                    <?php else: ?>
                        <span class="badge text-bg-warning"><i class="bi bi-exclamation-triangle"></i> Belum terhubung</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="wa_api_url">Alamat API</label>
                        <input type="url" name="wa_api_url" id="wa_api_url" class="form-control <?php $__errorArgs = ['wa_api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('wa_api_url', setting('wa_api_url'))); ?>" placeholder="https://api.fonnte.com/send" required>
                        <div class="form-text">Untuk Fonnte: <code>https://api.fonnte.com/send</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="wa_token">Token</label>
                        <div class="input-group">
                            <input type="password" name="wa_token" id="wa_token" class="form-control <?php $__errorArgs = ['wa_token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="" autocomplete="off"
                                   placeholder="<?php echo e(filled(setting('wa_token')) ? 'Token sudah tersimpan — kosongkan jika tidak diganti' : 'Tempel token dari akun gateway'); ?>">
                            <button type="button" class="btn btn-light" data-wa-reveal title="Tampilkan / sembunyikan"><i class="bi bi-eye"></i></button>
                        </div>
                        <div class="form-text">Token ada di akun gateway (Fonnte › menu Device › Token).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="reg_notify_wa">No. WhatsApp panitia <span class="text-muted-d fw-normal">(menerima kabar pendaftar baru)</span></label>
                        <input type="text" name="reg_notify_wa" id="reg_notify_wa" class="form-control" value="<?php echo e(old('reg_notify_wa', setting('reg_notify_wa'))); ?>" placeholder="0812xxxxxxx">
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="reg_send_wa" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" name="reg_send_wa" id="reg_send_wa" value="1" <?php if(old('reg_send_wa', setting('reg_send_wa'))): echo 'checked'; endif; ?>>
                        <label class="form-check-label" for="reg_send_wa">Kirim WhatsApp otomatis (ke pendaftar saat status berubah, dan ke panitia saat ada pendaftar baru)</label>
                    </div>
                </div>
                <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-plug me-1"></i> Simpan &amp; hubungkan</button></div>
            </form>

            <form method="POST" action="<?php echo e(route('dashboard.whatsapp.test')); ?>" class="card dash-card mt-4">
                <?php echo csrf_field(); ?>
                <div class="card-header"><h2><i class="bi bi-lightning-charge me-1"></i> Kirim pesan tes</h2></div>
                <div class="card-body">
                    <p class="small text-muted-d">Simpan dulu isian di atas, lalu kirim pesan percobaan untuk memastikan sudah terhubung. Bila gagal, alasannya tampil di layar.</p>
                    <label class="form-label" for="to">Nomor tujuan</label>
                    <div class="input-group">
                        <input type="text" name="to" id="to" class="form-control" value="<?php echo e(old('to', setting('reg_notify_wa'))); ?>" placeholder="0812xxxxxxx" required>
                        <button class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim tes</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xl-5">
            <div class="card dash-card">
                <div class="card-header"><h2>Cara menghubungkan (Fonnte)</h2></div>
                <div class="card-body small">
                    <ol class="ps-3 mb-3">
                        <li class="mb-2">Daftar di <strong>fonnte.com</strong>, lalu tambahkan <em>Device</em> dengan nomor WhatsApp sekolah.</li>
                        <li class="mb-2">Scan kode QR memakai WhatsApp di HP sekolah sampai statusnya <strong>Connected</strong>.</li>
                        <li class="mb-2">Salin <strong>Token</strong> device, tempel di kolom Token di samping.</li>
                        <li class="mb-2">Klik <strong>Simpan &amp; hubungkan</strong>, lalu <strong>Kirim tes</strong>.</li>
                    </ol>
                    <p class="text-muted-d mb-0">Setelah terhubung, setiap kali admin menekan <strong>Simpan status</strong> atau <strong>Simpan jadwal</strong>, pesan terkirim otomatis ke nomor WhatsApp yang didaftarkan pendaftar. Hasilnya tercatat di halaman detail pendaftar. Pengaturan ini sama dengan <strong>Pengaturan Website › Email &amp; WhatsApp</strong>.</p>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.querySelector('[data-wa-reveal]')?.addEventListener('click', function () {
                var input = document.getElementById('wa_token');
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/whatsapp/edit.blade.php ENDPATH**/ ?>