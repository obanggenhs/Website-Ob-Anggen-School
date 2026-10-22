<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex gap-2 align-items-start" role="alert">
        <i class="bi bi-check-circle-fill"></i><div><?php echo e(session('success')); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-start" role="alert">
        <i class="bi bi-exclamation-octagon-fill"></i><div><?php echo e(session('error')); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Periksa kembali isian berikut:</strong>
        <ul class="mb-0 mt-1 small"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>
<?php /**PATH S:\oa-school\resources\views/dashboard/partials/alerts.blade.php ENDPATH**/ ?>