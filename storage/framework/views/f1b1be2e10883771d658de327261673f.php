<div class="card dash-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="dash-card__title">Submenu di "<?php echo e($menu->title); ?>"</h2>
            <small class="text-muted-d">Grup dropdown tidak punya halaman sendiri. Kelola isi masing-masing submenu.</small>
        </div>
        <?php if(auth()->user()->isMaster()): ?>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#menuCreateModal" data-parent="<?php echo e($menu->id); ?>"><i class="bi bi-plus-lg"></i> Tambah submenu</button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if($menu->children->isEmpty()): ?>
            <?php echo $__env->make('dashboard.partials.empty', ['icon' => 'bi-menu-button-wide', 'text' => 'Belum ada submenu. Grup kosong tidak ditampilkan di navbar.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="row g-3">
                <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6">
                        <a href="<?php echo e(route('dashboard.studio.show', $child)); ?>" class="block-item text-decoration-none text-body mb-0 h-100 <?php echo e($child->is_active ? '' : 'is-hidden'); ?>">
                            <span class="block-item__icon"><i class="bi <?php echo e($child->type_icon); ?>"></i></span>
                            <span class="min-w-0 flex-fill">
                                <span class="block-item__title d-block"><?php echo e($child->title); ?></span>
                                <span class="block-item__preview d-block"><?php echo e($child->type_label); ?><?php echo e($child->param_label ? ' · '.$child->param_label : ''); ?></span>
                            </span>
                            <?php $__currentLoopData = $child->roleBadges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="role-chip role-chip--<?php echo e($role); ?>"><?php echo e($role === 'publikasi' ? 'PUB' : 'DAF'); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <i class="bi bi-chevron-right text-muted-d"></i>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_content-group.blade.php ENDPATH**/ ?>