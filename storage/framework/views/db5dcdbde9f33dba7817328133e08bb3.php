<?php $__env->startSection('title', $menu->title); ?>

<?php
    $isMaster = auth()->user()->isMaster();
    $hasPage = ! in_array($menu->type, ['dropdown', 'tautan'], true);
?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <?php if($isMaster): ?>
            <div class="col-xl-4 order-2 order-xl-1">
                <?php echo $__env->make('dashboard.studio._tree', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endif; ?>

        <div class="<?php echo e($isMaster ? 'col-xl-8 order-1 order-xl-2' : 'col-12'); ?>">
            <div class="card dash-card">
                <div class="card-body pb-3">
                    <div class="workspace-head">
                        <div class="d-flex gap-3 min-w-0">
                            <span class="workspace-icon"><i class="bi <?php echo e($menu->type_icon); ?>"></i></span>
                            <div class="min-w-0">
                                <div class="small text-muted-d">
                                    <?php echo e($menu->parent ? $menu->parent->title.' ›' : 'Menu utama ·'); ?> <?php echo e($menu->type_label); ?><?php echo e($menu->param_label ? ' · '.$menu->param_label : ''); ?>

                                </div>
                                <h1 class="h4 mb-1 text-truncate"><?php echo e($menu->title); ?></h1>
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    <?php echo $__env->make('dashboard.partials.status', ['active' => $menu->isVisible()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php $__currentLoopData = $menu->roleBadges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="role-chip role-chip--<?php echo e($role); ?>"><?php echo e($role === 'publikasi' ? 'Admin Publikasi' : 'Admin Pendaftaran'); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($menu->is_custom): ?>
                                        <a href="<?php echo e(route('dashboard.archive.index', ['menu' => $menu->id])); ?>" class="badge text-bg-light border text-decoration-none fw-semibold" title="Isi menu ini tercatat di database sekolah (tabel menu_contents)">
                                            <i class="bi bi-database-check text-success"></i> Menu buatan admin · tersimpan di database
                                        </a>
                                    <?php endif; ?>
                                    <?php if($menu->updated_at): ?>
                                        <small class="text-muted-d ms-1">Diubah <?php echo e($menu->updated_at->diffForHumans()); ?><?php echo e($menu->editor ? ' oleh '.$menu->editor->name : ''); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <?php if($menu->type !== 'dropdown'): ?>
                                <a href="<?php echo e($menu->link); ?>" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-box-arrow-up-right"></i> Lihat di website</a>
                            <?php endif; ?>
                            <?php if($isMaster): ?>
                                <form method="POST" action="<?php echo e(route('dashboard.studio.toggle', $menu)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-sm <?php echo e($menu->is_active ? 'btn-outline-secondary' : 'btn-success'); ?>">
                                        <i class="bi <?php echo e($menu->is_active ? 'bi-eye-slash' : 'bi-eye'); ?>"></i> <?php echo e($menu->is_active ? 'Sembunyikan' : 'Tampilkan'); ?>

                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($isMaster): ?>
                    <ul class="nav workspace-tabs">
                        <li class="nav-item"><a class="nav-link <?php echo e($tab === 'konten' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.studio.show', $menu)); ?>"><i class="bi bi-pencil-square me-1"></i> Isi Konten</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo e($tab === 'pengaturan' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.studio.show', [$menu, 'tab' => 'pengaturan'])); ?>"><i class="bi bi-gear me-1"></i> Pengaturan Menu</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo e($tab === 'akses' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.studio.show', [$menu, 'tab' => 'akses'])); ?>"><i class="bi bi-people me-1"></i> Akses Dashboard</a></li>
                    </ul>
                <?php else: ?>
                    <div class="px-3 pb-3">
                        <div class="alert alert-light border small mb-0"><i class="bi bi-info-circle me-1"></i> Menu ini ditugaskan Master Admin kepada Anda. Anda bisa mengubah isinya; struktur menu tetap diatur Master Admin.</div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <?php if($tab === 'pengaturan'): ?>
                    <?php echo $__env->make('dashboard.studio._settings', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php elseif($tab === 'akses'): ?>
                    <?php echo $__env->make('dashboard.studio._access', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php else: ?>
                    <?php switch($menu->type):
                        case ('beranda'): ?>
                            <?php echo $__env->make('dashboard.studio._content-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('dashboard.studio._content-home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('dashboard.studio._content-blocks', ['blocksTitle' => 'Blok tambahan di Beranda'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('dashboard.studio._content-options', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php break; ?>
                        <?php case ('halaman'): ?>
                            <?php echo $__env->make('dashboard.studio._content-blocks', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('dashboard.studio._content-options', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('dashboard.studio._content-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php break; ?>
                        <?php case ('dropdown'): ?>
                            <?php echo $__env->make('dashboard.studio._content-group', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php break; ?>
                        <?php case ('tautan'): ?>
                            <div class="card dash-card"><div class="card-body">
                                <h2 class="h6">Tautan luar</h2>
                                <p class="mb-2">Menu ini mengarah ke: <a href="<?php echo e($menu->url); ?>" target="_blank"><?php echo e($menu->url ?: '(belum diisi)'); ?></a></p>
                                <?php if($isMaster): ?><a href="<?php echo e(route('dashboard.studio.show', [$menu, 'tab' => 'pengaturan'])); ?>" class="btn btn-sm btn-soft">Ubah alamat tautan</a><?php endif; ?>
                            </div></div>
                            <?php break; ?>
                        <?php default: ?>
                            <?php echo $__env->make('dashboard.studio._content-module', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php if(\App\Support\PageOptions::supports($menu->type)): ?>
                                <?php echo $__env->make('dashboard.studio._content-options', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                            <?php echo $__env->make('dashboard.studio._content-blocks', ['blocksTitle' => 'Isi tambahan halaman', 'extra' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php echo $__env->make('dashboard.studio._content-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endswitch; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
    <?php if($isMaster): ?>
        <?php echo $__env->make('dashboard.studio._create-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/studio/show.blade.php ENDPATH**/ ?>