<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Nama isian <span class="text-danger">*</span></label>
        <input type="text" name="label" value="<?php echo e($f->label); ?>" class="form-control" required maxlength="120" placeholder="mis. Nama ayah">
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis</label>
        <select name="type" class="form-select">
            <?php $__currentLoopData = \App\Models\RegistrationField::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if($f->type === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Bagian</label>
        <select name="section" class="form-select">
            <?php $__currentLoopData = \App\Models\RegistrationField::SECTIONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if($f->section === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-12 d-none" data-options-wrap>
        <label class="form-label">Pilihan jawaban (satu baris satu pilihan)</label>
        <textarea name="options" rows="3" class="form-control"><?php echo e($f->options); ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Contoh isian</label>
        <input type="text" name="placeholder" value="<?php echo e($f->placeholder); ?>" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Lebar</label>
        <select name="width" class="form-select">
            <?php $__currentLoopData = ['12' => 'Penuh', '6' => 'Setengah', '4' => 'Sepertiga']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if((string) $f->width === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Petunjuk singkat</label>
        <input type="text" name="help_text" value="<?php echo e($f->help_text); ?>" class="form-control">
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_required" value="1" id="req<?php echo e($f->id ?? 'new'); ?>" <?php if($f->is_required): echo 'checked'; endif; ?>>
            <label class="form-check-label" for="req<?php echo e($f->id ?? 'new'); ?>">Wajib diisi</label>
        </div>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/registration-types/_field-inputs.blade.php ENDPATH**/ ?>