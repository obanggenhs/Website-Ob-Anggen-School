
<div class="card dash-card studio-tree">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title">Struktur website</h2>
            <small class="text-muted-d">Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#menuCreateModal" data-parent="">
            <i class="bi bi-plus-lg"></i> Menu utama
        </button>
    </div>
    <div class="card-body">
        <ul class="tree-list" id="studioTree" data-reorder-url="<?php echo e(route('dashboard.studio.reorder')); ?>">
            <?php $__currentLoopData = $tree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $root): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="tree-item" data-id="<?php echo e($root->id); ?>">
                    <?php echo $__env->make('dashboard.studio._tree-row', ['item' => $root], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php if($root->isGroup()): ?>
                        <ul class="tree-children">
                            <?php $__currentLoopData = $root->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="tree-item" data-id="<?php echo e($child->id); ?>">
                                    <?php echo $__env->make('dashboard.studio._tree-row', ['item' => $child], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <a href="#" class="tree-add ms-4 mb-1" data-bs-toggle="modal" data-bs-target="#menuCreateModal" data-parent="<?php echo e($root->id); ?>">
                            <i class="bi bi-plus-circle"></i> Submenu di <?php echo e($root->title); ?>

                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <div class="card-footer">
        <div class="tree-legend">
            <span><span class="role-chip role-chip--publikasi">PUB</span> Admin Publikasi</span>
            <span><span class="role-chip role-chip--pendaftaran">DAF</span> Admin Pendaftaran</span>
            <span><i class="bi bi-eye-slash"></i> disembunyikan</span>
            <span><i class="bi bi-star-fill text-warning"></i> tombol menonjol</span>
        </div>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_tree.blade.php ENDPATH**/ ?>