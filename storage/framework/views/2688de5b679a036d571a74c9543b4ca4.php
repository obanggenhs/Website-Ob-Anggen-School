<?php $__env->startSection('title', 'Akun Admin'); ?>
<?php $__env->startSection('page_title', 'Akun Admin'); ?>
<?php $__env->startSection('page_subtitle', 'Buat dan atur akun untuk dashboard Publikasi dan dashboard Pendaftaran.'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.users.create', ['role' => 'publikasi'])); ?>" class="btn btn-outline-primary"><i class="bi bi-broadcast"></i> Akun Publikasi</a>
    <a href="<?php echo e(route('dashboard.users.create', ['role' => 'pendaftaran'])); ?>" class="btn btn-primary"><i class="bi bi-clipboard-check"></i> Akun Pendaftaran</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $access; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $theme = \App\Support\DashboardMenu::theme($role); ?>
            <div class="col-lg-6">
                <div class="card dash-card h-100" style="border-top: 4px solid <?php echo e($theme['color']); ?>">
                    <div class="card-body">
                        <h2 class="h6 mb-2"><i class="bi <?php echo e($theme['icon']); ?>" style="color: <?php echo e($theme['color']); ?>"></i> Yang dikelola <?php echo e($theme['label']); ?></h2>
                        <div class="small mb-2"><strong>Modul bawaan:</strong> <?php echo e($a['core']->join(', ')); ?></div>
                        <div class="small">
                            <strong>Menu yang ditugaskan Master (<?php echo e($a['menus']->count()); ?>):</strong>
                            <?php $__empty_1 = true; $__currentLoopData = $a['menus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('dashboard.studio.show', [$m, 'tab' => 'akses'])); ?>" class="dash-chip mt-1"><i class="bi <?php echo e($m->type_icon); ?>"></i> <?php echo e($m->title); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted-d">belum ada</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="card dash-card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('dashboard.users.index')); ?>" class="dash-chip <?php echo e(! request('role') ? 'active' : ''); ?>">Semua</a>
                <?php $__currentLoopData = \App\Models\User::ROLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('dashboard.users.index', ['role' => $k])); ?>" class="dash-chip <?php echo e(request('role') === $k ? 'active' : ''); ?>"><?php echo e($v); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th>Nama</th><th>Role</th><th>Terakhir masuk</th><th>Dibuat oleh</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="dash-avatar" style="background: <?php echo e(\App\Support\DashboardMenu::theme($u->role)['color']); ?>"><?php echo e($u->initials); ?></span>
                                    <span><strong><?php echo e($u->name); ?></strong> <?php if($u->is(auth()->user())): ?><span class="badge text-bg-light border">Anda</span><?php endif; ?><div class="small text-muted-d"><?php echo e($u->email); ?></div></span>
                                </div>
                            </td>
                            <td><span class="role-chip role-chip--<?php echo e($u->role); ?>"><?php echo e($u->role_label); ?></span></td>
                            <td class="small"><?php echo e($u->last_login_at ? $u->last_login_at->diffForHumans() : 'Belum pernah'); ?></td>
                            <td class="small"><?php echo e($u->creator?->name ?? '-'); ?></td>
                            <td>
                                <?php if($u->is_active): ?><span class="badge text-bg-success">Aktif</span><?php else: ?><span class="badge text-bg-secondary">Nonaktif</span><?php endif; ?>
                            </td>
                            <td><div class="dash-actions">
                                <?php if (! ($u->is(auth()->user()))): ?>
                                    <form method="POST" action="<?php echo e(route('dashboard.users.toggle', $u)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm btn-light" title="<?php echo e($u->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>"><i class="bi <?php echo e($u->is_active ? 'bi-person-dash' : 'bi-person-check'); ?>"></i></button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo e(route('dashboard.users.edit', $u)); ?>" class="btn btn-sm btn-soft btn-icon"><i class="bi bi-pencil"></i></a>
                                <?php if (! ($u->is(auth()->user()))): ?>
                                    <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.users.destroy', $u), 'label' => 'akun '.$u->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endif; ?>
                            </div></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/users/index.blade.php ENDPATH**/ ?>