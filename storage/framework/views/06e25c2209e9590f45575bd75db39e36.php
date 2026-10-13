<?php $__env->startSection('title', $menu?->title ?? 'Cek Status Pendaftaran'); ?>
<?php $__env->startSection('page_title', $menu?->title ?? 'Cek Status Pendaftaran'); ?>

<?php $__env->startSection('page'); ?>
    <div class="oa-panel mb-4">
        <form method="GET" action="<?php echo e(route('registration.status')); ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="nomor">Nomor pendaftaran</label>
                <input type="text" name="nomor" id="nomor" value="<?php echo e(request('nomor')); ?>" class="form-control text-uppercase <?php $__errorArgs = ['nomor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="OA-<?php echo e(date('Y')); ?>-0001" required>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="email">Email / No. HP</label>
                <input type="text" name="email" id="email" value="<?php echo e(request('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-primary"><i class="bi bi-search"></i> Cek status</button>
            </div>
        </form>
    </div>

    <?php if($searched && ! $registration): ?>
        <div class="oa-result oa-result--danger"><i class="bi bi-x-circle"></i>
            <div><strong>Data tidak ditemukan.</strong><br>Periksa kembali nomor pendaftaran dan email / nomor HP yang Anda gunakan saat mendaftar.</div>
        </div>
    <?php elseif($registration): ?>
        <?php
            $step = $registration->pipelineStep();
            $steps = ['Formulir terkirim', 'Verifikasi berkas', 'Tes / wawancara', 'Pengumuman'];
            $info = $registration->status_info;
        ?>
        <div class="oa-panel">
            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div>
                    <small class="text-muted-oa"><?php echo e($registration->type->name); ?></small>
                    <h2 class="h5 mb-0"><?php echo e($registration->full_name); ?></h2>
                    <small class="text-muted-oa">No. <?php echo e($registration->number); ?> · daftar <?php echo e(tgl($registration->created_at, 'd F Y')); ?></small>
                </div>
                <span class="badge text-bg-<?php echo e($info['color']); ?> align-self-start fs-6"><i class="bi <?php echo e($info['icon']); ?>"></i> <?php echo e($info['label']); ?></span>
            </div>

            <div class="oa-stepper">
                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="oa-stepper__item <?php echo e($i < $step ? 'is-done' : ($i === $step ? 'is-current' : '')); ?>">
                        <div class="oa-stepper__dot"><?php if($i < $step): ?><i class="bi bi-check-lg"></i><?php else: ?><?php echo e($i + 1); ?><?php endif; ?></div>
                        <?php echo e($label); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="oa-result <?php echo e($registration->status === 'diterima' ? 'oa-result--success' : ($registration->status === 'ditolak' ? 'oa-result--danger' : 'oa-result--info')); ?> mb-4">
                <i class="bi <?php echo e($info['icon']); ?>"></i>
                <div>
                    <strong><?php echo e($info['label']); ?></strong><br><?php echo e($info['public']); ?>

                    <?php if($registration->status === 'diterima' && $registration->type->accepted_message): ?>
                        <p class="mb-0 mt-2"><?php echo e($registration->type->accepted_message); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($registration->schedule_at && ! $registration->isFinal()): ?>
                <div class="oa-panel mb-4" style="background: color-mix(in srgb, var(--oa-accent) 12%, #fff); box-shadow:none">
                    <h3 class="h6"><i class="bi bi-calendar-event me-1"></i> Jadwal tes / wawancara</h3>
                    <p class="mb-0"><strong><?php echo e(tgl($registration->schedule_at, 'l, d F Y')); ?></strong> pukul <?php echo e($registration->schedule_at->format('H.i')); ?> WIT<br><?php echo e($registration->schedule_place); ?></p>
                </div>
            <?php endif; ?>

            <?php if($registration->public_note): ?>
                <h3 class="h6">Catatan panitia</h3>
                <p class="mb-4"><?php echo e($registration->public_note); ?></p>
            <?php endif; ?>

            <h3 class="h6 mb-3">Riwayat proses</h3>
            <ul class="oa-timeline">
                <?php $__currentLoopData = $registration->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <strong class="d-block small"><?php echo e($log->title); ?></strong>
                        <?php if($log->note): ?><span class="small"><?php echo e($log->note); ?></span><br><?php endif; ?>
                        <small><?php echo e(tgl($log->created_at, 'd M Y, H.i')); ?></small>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="oa-empty"><i class="bi bi-search"></i>Masukkan nomor pendaftaran dan email untuk melihat status, jadwal, dan hasil seleksi.</div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/registration/status.blade.php ENDPATH**/ ?>