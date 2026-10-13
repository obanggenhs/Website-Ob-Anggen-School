<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'checked' => false, 'help' => null, 'col' => 'col-12']));

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

foreach (array_filter((['name', 'label', 'checked' => false, 'help' => null, 'col' => 'col-12']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php $id = 'f_'.$name; ?>
<div class="<?php echo e($col); ?>">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" value="1" <?php if(old($name, $checked)): echo 'checked'; endif; ?>>
        <label class="form-check-label" for="<?php echo e($id); ?>"><?php echo e($label); ?></label>
    </div>
    <?php if($help): ?><div class="form-text ms-5 mt-0"><?php echo e($help); ?></div><?php endif; ?>
</div>
<?php /**PATH S:\oa-school\resources\views/components/form/toggle.blade.php ENDPATH**/ ?>