<?php $user = auth()->user(); ?>
<header class="dash-topbar">
    <button type="button" class="btn btn-light btn-icon d-lg-none" data-sidebar-toggle aria-label="Buka menu"><i class="bi bi-list fs-5"></i></button>

    <div class="dash-topbar__title d-none d-md-block">
        <?php echo e($theme['panel']); ?> · <strong><?php echo $__env->yieldContent('title', 'Dashboard'); ?></strong>
    </div>

    <div class="ms-auto d-flex align-items-center gap-2">
        <a href="<?php echo e(route('home')); ?>" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-box-arrow-up-right"></i> <span class="d-none d-sm-inline">Lihat website</span></a>

        <?php if($user->canManage('pendaftaran')): ?>
            <?php $fresh = \App\Models\Registration::where('status', 'baru')->count(); ?>
            <a href="<?php echo e(route('dashboard.applicants.index', ['status' => 'baru'])); ?>" class="btn btn-light btn-icon position-relative" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Pendaftar baru">
                <i class="bi bi-bell"></i>
                <?php if($fresh): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem"><?php echo e($fresh); ?></span><?php endif; ?>
            </a>
        <?php endif; ?>

        <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center gap-2 py-1 px-2" data-bs-toggle="dropdown" aria-expanded="false">
                <?php if($user->avatar_url): ?>
                    <img src="<?php echo e($user->avatar_url); ?>" alt="" class="dash-avatar">
                <?php else: ?>
                    <span class="dash-avatar"><?php echo e($user->initials); ?></span>
                <?php endif; ?>
                <span class="text-start d-none d-sm-block lh-sm">
                    <strong class="d-block small"><?php echo e($user->name); ?></strong>
                    <small class="text-muted-d" style="font-size:.7rem"><?php echo e($user->role_label); ?></small>
                </span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item" href="<?php echo e(route('dashboard.profile.edit')); ?>"><i class="bi bi-person me-2"></i>Profil & kata sandi</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
<?php /**PATH S:\oa-school\resources\views/dashboard/partials/topbar.blade.php ENDPATH**/ ?>