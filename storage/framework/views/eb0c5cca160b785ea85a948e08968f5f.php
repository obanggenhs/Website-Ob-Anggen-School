<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label' => 'Gambar', 'value' => null, 'help' => 'JPG, PNG, atau WEBP. Tanpa batas ukuran.', 'col' => 'col-12', 'remove' => null, 'logo' => false]));

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

foreach (array_filter((['name', 'label' => 'Gambar', 'value' => null, 'help' => 'JPG, PNG, atau WEBP. Tanpa batas ukuran.', 'col' => 'col-12', 'remove' => null, 'logo' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php $id = 'f_'.$name; ?>
<div class="<?php echo e($col); ?>">
    <label for="<?php echo e($id); ?>" class="form-label"><?php echo e($label); ?></label>
    <img src="<?php echo e($value ? media_url($value) : ''); ?>" id="<?php echo e($id); ?>_preview" alt="" class="img-preview <?php echo e($logo ? 'img-preview--logo' : ''); ?> mb-2 <?php echo e($value ? '' : 'd-none'); ?>">
    <input type="file" name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" accept="image/*" data-preview="<?php echo e($id); ?>_preview" class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php if($remove && $value): ?>
        <div class="form-check mt-1">
            <input class="form-check-input" type="checkbox" name="<?php echo e($remove); ?>" value="1" id="<?php echo e($id); ?>_remove">
            <label class="form-check-label small" for="<?php echo e($id); ?>_remove">Hapus gambar ini</label>
        </div>
    <?php endif; ?>
    <?php if($help): ?><div class="form-text"><?php echo e($help); ?></div><?php endif; ?>
</div>
<?php /**PATH S:\oa-school\resources\views/components/form/image.blade.php ENDPATH**/ ?>