<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['back', 'label' => 'Simpan']));

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

foreach (array_filter((['back', 'label' => 'Simpan']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="d-flex flex-wrap gap-2 justify-content-end border-top pt-3 mt-4">
    <a href="<?php echo e($back); ?>" class="btn btn-light">Batal</a>
    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1"></i> <?php echo e($label); ?></button>
</div>
<?php /**PATH S:\oa-school\resources\views/components/form/buttons.blade.php ENDPATH**/ ?>