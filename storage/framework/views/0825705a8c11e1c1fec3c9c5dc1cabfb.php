<?php $__env->startSection('title', 'Data Pendaftar'); ?>
<?php $__env->startSection('page_title', 'Data Pendaftar'); ?>
<?php $__env->startSection('page_subtitle', 'Proses setiap pendaftar: verifikasi berkas → jadwal tes/wawancara → keputusan.'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.whatsapp.edit')); ?>" class="btn btn-light"><i class="bi bi-whatsapp text-success"></i> Koneksi WhatsApp</a>
    <a href="<?php echo e(route('dashboard.applicants.export', request()->query())); ?>" class="btn btn-light"><i class="bi bi-filetype-csv"></i> Unduh Excel (CSV)</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $total = $counts->sum(); ?>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="<?php echo e(route('dashboard.applicants.index', request()->except(['status', 'page']))); ?>" class="dash-chip <?php echo e(! request('status') ? 'active' : ''); ?>">Semua <span class="opacity-75"><?php echo e($total); ?></span></a>
        <?php $__currentLoopData = \App\Models\Registration::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('dashboard.applicants.index', array_merge(request()->except('page'), ['status' => $key]))); ?>" class="dash-chip <?php echo e(request('status') === $key ? 'active' : ''); ?>">
                <i class="bi <?php echo e($s['icon']); ?>"></i> <?php echo e($s['label']); ?> <span class="opacity-75"><?php echo e($counts[$key] ?? 0); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <?php if(request('status')): ?><input type="hidden" name="status" value="<?php echo e(request('status')); ?>"><?php endif; ?>
                <select name="jalur" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua jalur</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>" <?php if(request('jalur') == $t->id): echo 'selected'; endif; ?>><?php echo e($t->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="search" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control form-control-sm" placeholder="Nama, nomor, atau email...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i> Cari</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Nomor</th><th>Nama pendaftar</th><th>Jalur</th><th>Tanggal</th><th>Jadwal</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold small text-nowrap"><?php echo e($r->number); ?></td>
                            <td><a href="<?php echo e(route('dashboard.applicants.show', $r)); ?>" class="fw-bold text-body"><?php echo e($r->full_name); ?></a><div class="small text-muted-d"><?php echo e($r->phone); ?> · <?php echo e($r->email); ?></div></td>
                            <td class="small"><?php echo e($r->type->name); ?></td>
                            <td class="small text-nowrap"><?php echo e(tgl($r->created_at, 'd M Y')); ?><div class="text-muted-d"><?php echo e($r->created_at->diffForHumans()); ?></div></td>
                            <td class="small text-nowrap"><?php echo e($r->schedule_at ? tgl($r->schedule_at, 'd M, H.i') : '-'); ?></td>
                            <td><span class="badge text-bg-<?php echo e($r->status_info['color']); ?>"><?php echo e($r->status_label); ?></span></td>
                            <td><div class="dash-actions">
                                <a href="<?php echo e(wa_link($r->phone, 'Halo '.$r->full_name.', kami dari panitia pendaftaran '.setting('site_name').' terkait pendaftaran '.$r->number.'.')); ?>" target="_blank" class="btn btn-sm btn-light btn-icon" title="WhatsApp"><i class="bi bi-whatsapp text-success"></i></a>
                                <a href="<?php echo e(route('dashboard.applicants.show', $r)); ?>" class="btn btn-sm btn-primary"><i class="bi bi-arrow-right-circle"></i> Proses</a>
                            </div></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7"><?php echo $__env->make('dashboard.partials.empty', ['icon' => 'bi-person-lines-fill', 'text' => 'Tidak ada pendaftar untuk filter ini.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($registrations->hasPages()): ?><div class="card-footer"><?php echo e($registrations->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/applicants/index.blade.php ENDPATH**/ ?>