<?php $__env->startSection('title', $menu?->title ?? 'Guru & Staf'); ?>

<?php $__env->startSection('page'); ?>
    <?php if($leaders->isNotEmpty() && ! $category && ! $branchId && ! request('cari')): ?>
        <div class="oa-sechead"><div><h2>Pimpinan sekolah</h2></div></div>
        <div class="row g-3 mb-5">
            <?php $__currentLoopData = $leaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-3" data-reveal><?php echo $__env->make('web.staff._card', ['person' => $person], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <form class="oa-filterbar" method="GET">
        <div class="oa-chips">
            <a href="<?php echo e(route('staff.index', array_filter(['kampus' => $branchId]))); ?>" class="oa-chip <?php echo e(! $category ? 'active' : ''); ?>">Semua</a>
            <?php $__currentLoopData = \App\Models\Staff::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('staff.index', array_filter(['kategori' => $key, 'kampus' => $branchId]))); ?>" class="oa-chip <?php echo e($category === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="d-flex gap-2">
            <?php if($category): ?><input type="hidden" name="kategori" value="<?php echo e($category); ?>"><?php endif; ?>
            <select name="kampus" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                <option value="">Semua kampus</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($b->id); ?>" <?php if($branchId === $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="oa-search"><i class="bi bi-search"></i><input type="search" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Cari nama..."></div>
        </div>
    </form>

    <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $people): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="oa-sechead mt-2"><h2 class="h5"><?php echo e(\App\Models\Staff::CATEGORIES[$group] ?? $group); ?></h2></div>
        <div class="row g-3 mb-4">
            <?php $__currentLoopData = $people; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-4 col-lg-3" data-reveal><?php echo $__env->make('web.staff._card', ['person' => $person], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="oa-empty"><i class="bi bi-people"></i>Data guru & staf tidak ditemukan.</div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-full', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/staff/index.blade.php ENDPATH**/ ?>