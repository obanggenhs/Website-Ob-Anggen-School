
<div class="card dash-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Pesan ke pendaftar</h2>
        <div class="d-flex gap-1">
            <form method="POST" action="<?php echo e(route('dashboard.applicants.resend', [$r, 'email'])); ?>"><?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-light" title="Kirim ulang status terbaru ke <?php echo e($r->email); ?>"><i class="bi bi-envelope-arrow-up"></i> Kirim ulang email</button>
            </form>
            <form method="POST" action="<?php echo e(route('dashboard.applicants.resend', [$r, 'whatsapp'])); ?>"><?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-light" title="Kirim ulang status terbaru ke <?php echo e($r->phone); ?>"><i class="bi bi-whatsapp text-success"></i> Kirim ulang WA</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <?php if (! (\App\Support\WhatsAppSender::enabled() && \App\Support\WhatsAppSender::configured())): ?>
            <div class="alert alert-warning small py-2 d-flex gap-2 align-items-start">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>WhatsApp belum terhubung, jadi pesan ke pendaftar hanya lewat email. <a href="<?php echo e(route('dashboard.whatsapp.edit')); ?>" class="alert-link">Hubungkan WhatsApp</a></div>
            </div>
        <?php endif; ?>
        <?php $__empty_1 = true; $__currentLoopData = $r->notifications->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="d-flex gap-2 align-items-start <?php echo e($loop->last ? '' : 'mb-2 pb-2 border-bottom'); ?>">
                <i class="bi <?php echo e($n->channel === 'email' ? 'bi-envelope' : 'bi-whatsapp'); ?> mt-1"></i>
                <div class="min-w-0 small">
                    <strong><?php echo e($n->channel_label); ?></strong> ke <?php echo e($n->target); ?> <span class="text-muted-d">· <?php echo e($n->event_label); ?></span>
                    <span class="badge text-bg-<?php echo e($n->isSent() ? 'success' : 'danger'); ?>"><?php echo e($n->isSent() ? 'Terkirim' : 'Gagal'); ?></span>
                    <?php if($n->error): ?><div class="text-danger"><?php echo e($n->error); ?></div><?php endif; ?>
                    <div class="text-muted-d"><?php echo e(tgl($n->created_at, 'd M Y, H.i')); ?> · <?php echo e($n->user?->name ?? 'sistem'); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="small text-muted-d mb-0">Belum ada pesan otomatis. Setiap kali status atau jadwal disimpan, kabar dikirim ke email dan WhatsApp pendaftar, lalu hasilnya tercatat di sini.</p>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/applicants/_notifications.blade.php ENDPATH**/ ?>