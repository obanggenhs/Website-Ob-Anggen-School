<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label' => null, 'type' => 'text', 'value' => null, 'required' => false, 'help' => null, 'col' => 'col-12', 'prefix' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'label' => null, 'type' => 'text', 'value' => null, 'required' => false, 'help' => null, 'col' => 'col-12', 'prefix' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f_'.str_replace('.', '_', $key);
?>
<div class="<?php echo e($col); ?>">
    <?php if($label): ?><label for="<?php echo e($id); ?>" class="form-label"><?php echo e($label); ?> <?php if($required): ?><span class="text-danger">*</span><?php endif; ?></label><?php endif; ?>
    <?php if($prefix): ?><div class="input-group"><span class="input-group-text small"><?php echo e($prefix); ?></span><?php endif; ?>
    <input type="<?php echo e($type); ?>" name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" value="<?php echo e($type === 'password' ? '' : old($key, $value)); ?>"
           <?php echo e($attributes->merge(['class' => 'form-control'.($errors->has($key) ? ' is-invalid' : '')])); ?> <?php if($required): echo 'required'; endif; ?>>
    <?php if($prefix): ?></div><?php endif; ?>
    <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php if($help): ?><div class="form-text"><?php echo e($help); ?></div><?php endif; ?>
</div>
<?php /**PATH S:\oa-school\resources\views/components/form/input.blade.php ENDPATH**/ ?>