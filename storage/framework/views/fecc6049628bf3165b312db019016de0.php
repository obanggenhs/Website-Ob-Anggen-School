<?php $__env->startSection('title', 'Kalender Akademik'); ?>
<?php $__env->startSection('page_title', 'Kalender Akademik'); ?>
<?php $__env->startSection('page_subtitle', 'Agenda untuk SD, SMP, SMA, atau semua jenjang.'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.calendar.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah agenda</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="waktu" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="mendatang" <?php if(request('waktu', 'mendatang') === 'mendatang'): echo 'selected'; endif; ?>>Mendatang & berlangsung</option>
                    <option value="semua" <?php if(request('waktu') === 'semua'): echo 'selected'; endif; ?>>Semua agenda</option>
                </select>
                <select name="jenjang" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua jenjang</option>
                    <?php $__currentLoopData = \App\Models\CalendarEvent::LEVELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('jenjang') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    <?php $__currentLoopData = \App\Models\CalendarEvent::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('kategori') === $k): echo 'selected'; endif; ?>><?php echo e($v['label']); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php if($years->isNotEmpty()): ?>
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua tahun ajaran</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($y); ?>" <?php if(request('tahun') === $y): echo 'selected'; endif; ?>><?php echo e($y); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Tanggal</th><th>Agenda</th><th>Kategori</th><th>Jenjang</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="small text-nowrap"><strong><?php echo e($e->date_label); ?></strong><div class="text-muted-d"><?php echo e($e->relative_label); ?></div></td>
                            <td><strong><?php echo e($e->title); ?></strong><div class="small text-muted-d"><?php echo e($e->time_info); ?> <?php echo e($e->location ? '· '.$e->location : ''); ?></div></td>
                            <td><span class="badge" style="background: <?php echo e($e->category_color); ?>"><?php echo e($e->category_label); ?></span></td>
                            <td><?php echo e($e->level_label); ?></td>
                            <td><?php echo $__env->make('dashboard.partials.status', ['active' => $e->is_active], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            <td><div class="dash-actions">
                                <a href="<?php echo e(route('dashboard.calendar.edit', $e)); ?>" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.calendar.destroy', $e), 'label' => 'agenda ini'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6"><?php echo $__env->make('dashboard.partials.empty', ['icon' => 'bi-calendar3', 'text' => 'Tidak ada agenda.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($events->hasPages()): ?><div class="card-footer"><?php echo e($events->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/calendar/index.blade.php ENDPATH**/ ?>