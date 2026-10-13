
<?php $__currentLoopData = \App\Models\Block::arrange($list); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($row['group']): ?>
        <div class="col-12 oa-block-row">
            <div class="row oa-blocks justify-content-<?php echo e($row['justify']); ?>">
                <?php $__currentLoopData = $row['blocks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('web.partials.block', ['b' => $b], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('web.partials.block', ['b' => $row['blocks'][0]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH S:\oa-school\resources\views/web/partials/blocks.blade.php ENDPATH**/ ?>