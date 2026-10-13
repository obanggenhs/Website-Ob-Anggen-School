<?php $__env->startSection('title', 'Beranda Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="dash-hero">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center position-relative" style="z-index:1">
            <div>
                <h1>Selamat datang, <?php echo e(auth()->user()->name); ?></h1>
                <p>Atur struktur menu, isi setiap halaman, dan tentukan bagian mana yang dikelola Admin Publikasi atau Admin Pendaftaran.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('dashboard.studio.index')); ?>" class="btn btn-light"><i class="bi bi-diagram-3"></i> Buka Studio</a>
                <a href="<?php echo e(route('dashboard.users.create')); ?>" class="btn btn-outline-light"><i class="bi bi-person-plus"></i> Buat akun admin</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-xl-3">
                <a href="<?php echo e($s['url']); ?>" class="dash-stat"><i class="bi <?php echo e($s['icon']); ?>"></i><span><strong><?php echo e($s['value']); ?></strong><small><?php echo e($s['label']); ?></small></span></a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($hidden || $drafts): ?>
        <div class="alert alert-light border d-flex flex-wrap gap-3 small">
            <i class="bi bi-info-circle text-primary"></i>
            <?php if($hidden): ?><span><strong><?php echo e($hidden); ?></strong> menu sedang disembunyikan dari website.</span><?php endif; ?>
            <?php if($drafts): ?><span><strong><?php echo e($drafts); ?></strong> berita masih berstatus draf.</span><?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card dash-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Peta website</h2>
                    <a href="<?php echo e(route('dashboard.studio.index')); ?>" class="small">Kelola struktur <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php $__currentLoopData = $tree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $root): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100 <?php echo e($root->is_active ? '' : 'opacity-50'); ?>">
                                    <a href="<?php echo e(route('dashboard.studio.show', $root)); ?>" class="d-flex align-items-center gap-2 fw-bold text-decoration-none text-body">
                                        <i class="bi <?php echo e($root->type_icon); ?> text-primary"></i> <?php echo e($root->title); ?>

                                        <?php if($root->is_highlight): ?><i class="bi bi-star-fill text-warning small" title="Tombol menonjol"></i><?php endif; ?>
                                        <span class="ms-auto"><?php $__currentLoopData = $root->roleBadges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="role-chip role-chip--<?php echo e($r); ?>"><?php echo e($r === 'publikasi' ? 'PUB' : 'DAF'); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span>
                                    </a>
                                    <?php if($root->children->isNotEmpty()): ?>
                                        <div class="d-flex flex-wrap gap-1 mt-2">
                                            <?php $__currentLoopData = $root->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('dashboard.studio.show', $child)); ?>" class="dash-chip <?php echo e($child->is_active ? '' : 'opacity-50'); ?>"><i class="bi <?php echo e($child->type_icon); ?>"></i> <?php echo e($child->title); ?></a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="small text-muted-d mt-1"><?php echo e($root->type_label); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="tree-legend mt-3">
                        <span><span class="role-chip role-chip--publikasi">PUB</span> juga dikelola Admin Publikasi</span>
                        <span><span class="role-chip role-chip--pendaftaran">DAF</span> juga dikelola Admin Pendaftaran</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Akun admin</h2>
                    <a href="<?php echo e(route('dashboard.users.index')); ?>" class="small">Kelola <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <span class="dash-avatar" style="background: <?php echo e(\App\Support\DashboardMenu::theme($admin->role)['color']); ?>"><?php echo e($admin->initials); ?></span>
                                <span class="min-w-0 flex-fill">
                                    <span class="dash-list__title d-block"><?php echo e($admin->name); ?></span>
                                    <span class="dash-list__meta"><?php echo e($admin->role_label); ?> · <?php echo e($admin->last_login_at ? 'aktif '.$admin->last_login_at->diffForHumans() : 'belum pernah masuk'); ?></span>
                                </span>
                                <?php if (! ($admin->is_active)): ?><span class="badge text-bg-secondary">Nonaktif</span><?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted-d small">Belum ada akun Admin Publikasi / Pendaftaran.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Pendaftar terbaru</h2>
                    <a href="<?php echo e(route('dashboard.applicants.index')); ?>" class="small">Semua <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        <?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <span class="min-w-0 flex-fill">
                                    <a href="<?php echo e(route('dashboard.applicants.show', $a)); ?>" class="dash-list__title d-block text-truncate"><?php echo e($a->full_name); ?></a>
                                    <span class="dash-list__meta"><?php echo e($a->number); ?> · <?php echo e($a->type->name); ?></span>
                                </span>
                                <span class="badge text-bg-<?php echo e($a->status_info['color']); ?>"><?php echo e($a->status_label); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="text-muted-d small">Belum ada pendaftar.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Aktivitas terakhir semua admin</h2>
                    <a href="<?php echo e(route('dashboard.activity.index')); ?>" class="small">Log lengkap <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td style="width:170px" class="text-muted-d small"><?php echo e($log->created_at->diffForHumans()); ?></td>
                                    <td><strong><?php echo e($log->user?->name ?? 'Sistem'); ?></strong> <?php if($log->user): ?><span class="role-chip role-chip--<?php echo e($log->user->role); ?>"><?php echo e($log->user->role_label); ?></span><?php endif; ?></td>
                                    <td><?php echo e($log->action); ?> <span class="text-muted-d"><?php echo e($log->subject); ?></span></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td class="text-center text-muted-d py-4">Belum ada aktivitas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/home/master.blade.php ENDPATH**/ ?>