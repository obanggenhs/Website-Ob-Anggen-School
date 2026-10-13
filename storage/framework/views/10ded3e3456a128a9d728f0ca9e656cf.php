
<div class="card dash-card mb-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title">Isi otomatis dari modul <?php echo e($summary['module'] ?? $menu->type_label); ?></h2>
            <small class="text-muted-d">Halaman ini menampilkan data modul secara otomatis<?php echo e($menu->param_label ? ' (filter: '.$menu->param_label.')' : ''); ?>.</small>
        </div>
        <?php if($summary && $summary['can_manage'] && $summary['manage_url']): ?>
            <a href="<?php echo e($summary['manage_url']); ?>" class="btn btn-primary btn-sm"><i class="bi bi-database-gear"></i> Kelola data <?php echo e(\Illuminate\Support\Str::lower($summary['module'])); ?></a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if($summary): ?>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="dash-stat flex-fill" style="box-shadow:none;border:1px solid var(--dash-line)">
                    <i class="bi <?php echo e($menu->type_icon); ?>"></i>
                    <span><strong><?php echo e($summary['count']); ?></strong><small><?php echo e($summary['noun']); ?> tersimpan</small></span>
                </div>
            </div>
            <ul class="dash-list">
                <?php $__empty_1 = true; $__currentLoopData = $summary['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>
                        <span class="min-w-0 flex-fill">
                            <?php if($summary['can_manage']): ?>
                                <a href="<?php echo e($row['url']); ?>" class="dash-list__title d-block text-truncate"><?php echo e($row['title']); ?></a>
                            <?php else: ?>
                                <span class="dash-list__title d-block text-truncate"><?php echo e($row['title']); ?></span>
                            <?php endif; ?>
                            <span class="dash-list__meta"><?php echo e($row['meta']); ?></span>
                        </span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="small text-muted-d">Belum ada data.</li>
                <?php endif; ?>
            </ul>
            <?php if (! ($summary['can_manage'])): ?>
                <div class="alert alert-light border small mt-3 mb-0">Data modul ini dikelola admin lain. Anda dapat mengubah header & teks pembuka halaman di bawah.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_content-module.blade.php ENDPATH**/ ?>