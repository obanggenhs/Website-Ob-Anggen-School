<?php $__env->startSection('title', 'Kategori'); ?>
<?php $__env->startSection('page_title', 'Kategori'); ?>
<?php $__env->startSection('page_subtitle', 'Pengelompokan berita dan album galeri.'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-6">
                <div class="card dash-card h-100">
                    <div class="card-header"><h2>Kategori <?php echo e(\App\Models\Category::TYPES[$type]); ?></h2></div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('dashboard.categories.store')); ?>" class="input-group mb-3">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="type" value="<?php echo e($type); ?>">
                            <input type="text" name="name" class="form-control" placeholder="Nama kategori baru" required maxlength="60">
                            <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</button>
                        </form>
                        <ul class="dash-list">
                            <?php $__empty_1 = true; $__currentLoopData = $categories[$type] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <form method="POST" action="<?php echo e(route('dashboard.categories.update', $cat)); ?>" class="input-group input-group-sm flex-fill">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="text" name="name" value="<?php echo e($cat->name); ?>" class="form-control" required>
                                        <span class="input-group-text"><?php echo e($type === 'news' ? $cat->news_count.' berita' : $cat->albums_count.' album'); ?></span>
                                        <button class="btn btn-soft" title="Simpan nama"><i class="bi bi-check2"></i></button>
                                    </form>
                                    <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.categories.destroy', $cat), 'label' => 'kategori '.$cat->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="small text-muted-d">Belum ada kategori.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/categories/index.blade.php ENDPATH**/ ?>