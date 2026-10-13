<?php $__env->startSection('title', 'Guru & Staf'); ?>
<?php $__env->startSection('page_title', 'Guru & Staf'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.staff.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kelompok</option>
                    <?php $__currentLoopData = \App\Models\Staff::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('kategori') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="kampus" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kampus</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($b->id); ?>" <?php if(request('kampus') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="search" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control form-control-sm" placeholder="Cari nama...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>#</th><th colspan="2">Nama</th><th>Kelompok</th><th>Kampus</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-muted-d small"><?php echo e($p->position); ?></td>
                            <td style="width:52px"><img src="<?php echo e($p->photo_url); ?>" class="dash-thumb dash-thumb--round" alt=""></td>
                            <td><strong><?php echo e($p->name); ?></strong> <?php if($p->is_featured): ?><i class="bi bi-star-fill text-warning small" title="Ditampilkan sebagai pimpinan"></i><?php endif; ?><div class="small text-muted-d"><?php echo e($p->position_title); ?></div></td>
                            <td class="small"><?php echo e($p->category_label); ?></td>
                            <td class="small"><?php echo e($p->branch?->name ?? '-'); ?></td>
                            <td><?php echo $__env->make('dashboard.partials.status', ['active' => $p->is_active], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            <td><div class="dash-actions">
                                <a href="<?php echo e(route('dashboard.staff.edit', $p)); ?>" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.staff.destroy', $p), 'label' => $p->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7"><?php echo $__env->make('dashboard.partials.empty', ['icon' => 'bi-people', 'text' => 'Belum ada data guru & staf.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($staff->hasPages()): ?><div class="card-footer"><?php echo e($staff->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/staff/index.blade.php ENDPATH**/ ?>