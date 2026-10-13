<?php
    $key = 'f.'.$field->name;
    $input = 'f['.$field->name.']';
    $id = 'f_'.$field->name;
    $value = old($key);
?>
<div class="col-md-<?php echo e($field->width); ?>">
    <label for="<?php echo e($id); ?>" class="form-label"><?php echo e($field->label); ?> <?php if($field->is_required): ?><span class="text-danger">*</span><?php endif; ?></label>

    <?php switch($field->type):
        case ('textarea'): ?>
            <textarea name="<?php echo e($input); ?>" id="<?php echo e($id); ?>" rows="3" class="form-control <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e($field->placeholder); ?>" <?php if($field->is_required): echo 'required'; endif; ?>><?php echo e($value); ?></textarea>
            <?php break; ?>
        <?php case ('select'): ?>
            <select name="<?php echo e($input); ?>" id="<?php echo e($id); ?>" class="form-select <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php if($field->is_required): echo 'required'; endif; ?>>
                <option value="">— Pilih —</option>
                <?php $__currentLoopData = $field->optionList(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($opt); ?>" <?php if($value === $opt): echo 'selected'; endif; ?>><?php echo e($opt); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php break; ?>
        <?php case ('radio'): ?>
            <div class="d-flex flex-wrap gap-3 pt-1">
                <?php $__currentLoopData = $field->optionList(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check">
                        <input class="form-check-input <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="radio" name="<?php echo e($input); ?>" id="<?php echo e($id); ?>_<?php echo e($i); ?>" value="<?php echo e($opt); ?>" <?php if($value === $opt): echo 'checked'; endif; ?> <?php if($field->is_required): echo 'required'; endif; ?>>
                        <label class="form-check-label" for="<?php echo e($id); ?>_<?php echo e($i); ?>"><?php echo e($opt); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php break; ?>
        <?php case ('file'): ?>
            <input type="file" name="<?php echo e($input); ?>" id="<?php echo e($id); ?>" class="form-control <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.jpg,.jpeg,.png,.webp" data-filename="<?php echo e($id); ?>_name" <?php if($field->is_required): echo 'required'; endif; ?>>
            <div class="form-text" id="<?php echo e($id); ?>_name"></div>
            <?php break; ?>
        <?php default: ?>
            <input type="<?php echo e($field->type); ?>" name="<?php echo e($input); ?>" id="<?php echo e($id); ?>" value="<?php echo e($value); ?>" class="form-control <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e($field->placeholder); ?>" <?php if($field->is_required): echo 'required'; endif; ?>>
    <?php endswitch; ?>

    <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php if($field->help_text): ?><div class="form-text"><?php echo e($field->help_text); ?></div><?php endif; ?>
</div>
<?php /**PATH S:\oa-school\resources\views/web/registration/_field.blade.php ENDPATH**/ ?>