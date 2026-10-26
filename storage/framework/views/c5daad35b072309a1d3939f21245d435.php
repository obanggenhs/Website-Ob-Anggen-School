
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'opt' => [], 'type' => 'halaman', 'in' => [], 'chips' => null, 'years' => [], 'result' => null, 'noun' => 'data',
    'hidden' => [], 'scope' => [], 'pageName' => 'page', 'anchor' => null, 'placeholder' => 'Cari...', 'chipsAlways' => false,
]));

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

foreach (array_filter(([
    'opt' => [], 'type' => 'halaman', 'in' => [], 'chips' => null, 'years' => [], 'result' => null, 'noun' => 'data',
    'hidden' => [], 'scope' => [], 'pageName' => 'page', 'anchor' => null, 'placeholder' => 'Cari...', 'chipsAlways' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $in = array_merge(['cari' => '', 'tahun' => '', 'urut' => '', 'tampil' => 'kartu'], $in);
    $years = collect($years);
    $sorts = \App\Support\PageOptions::sorts($type);
    $hash = $anchor ? '#'.$anchor : '';
    $link = fn (array $params) => request()->fullUrlWithQuery(array_merge($scope, $params, [$pageName => null])).$hash;

    // Satukan bentuk chips menjadi daftar kelompok
    $groups = [];
    if (is_array($chips) && $chips !== []) {
        $groups = array_key_exists('param', $chips) ? [$chips] : array_values(array_filter($chips));
    }
    $groups = array_map(fn ($g) => array_merge(['current' => '', 'items' => [], 'all' => null, 'always' => $chipsAlways, 'icon' => null, 'reset' => []], $g), $groups);
    $groups = array_values(array_filter($groups, fn ($g) => ($g['always'] || ! empty($opt['filter'])) && count($g['items']) > 0));

    $showYear = ! empty($opt['year']) && $years->isNotEmpty();
    $showSort = ! empty($opt['sort']);
    $showSearch = ! empty($opt['search']);
    $showView = ! empty($opt['view']);
    $showCount = ! empty($opt['count']) && $result !== null;
    $extra = trim((string) $slot) !== '';
    $hasForm = $showYear || $showSort || $showSearch || $extra;

    // Pilihan yang bisa dihapus lewat "Hapus pencarian & filter" (pilihan kampus dsb. tidak ikut dihapus)
    $resetParams = ['cari' => null, 'tahun' => null, 'urut' => null];
    $filtered = $in['cari'] !== '' || $in['tahun'] !== '';
    foreach ($groups as $g) {
        if (! $g['always']) {
            $resetParams[$g['param']] = null;
            $filtered = $filtered || (string) $g['current'] !== '';
        }
    }

    // Nilai yang ikut terkirim saat formulir dipakai (tanpa menggandakan isian yang sudah ada)
    $keep = array_merge($hidden, $scope);
    foreach ($groups as $g) {
        if ((string) $g['current'] !== '') {
            $keep[$g['param']] = $g['current'];
        }
    }
    if ($showView && $in['tampil'] === 'daftar') {
        $keep['tampil'] = 'daftar';
    }
    foreach (['cari' => $showSearch, 'tahun' => $showYear, 'urut' => $showSort] as $name => $shown) {
        if ($shown) {
            unset($keep[$name]);
        }
    }
?>

<?php if($groups || $hasForm || $showView || $showCount): ?>
    <div class="oa-filterbar oa-listbar" <?php if($anchor): ?> id="<?php echo e($anchor); ?>" <?php endif; ?>>
        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="oa-chips oa-listbar__chips">
                <?php if($g['all']): ?>
                    <a href="<?php echo e($link(array_merge(array_fill_keys($g['reset'], null), [$g['param'] => null]))); ?>" class="oa-chip <?php echo e((string) $g['current'] === '' ? 'active' : ''); ?>"><?php echo e($g['all']); ?></a>
                <?php endif; ?>
                <?php $__currentLoopData = $g['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($link(array_merge(array_fill_keys($g['reset'], null), [$g['param'] => $value]))); ?>" class="oa-chip <?php echo e((string) $g['current'] === (string) $value ? 'active' : ''); ?>"><?php if($g['icon']): ?><i class="bi <?php echo e($g['icon']); ?>"></i> <?php endif; ?><?php echo e($label); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($hasForm || $showView): ?>
            <div class="oa-listbar__row">
                <?php if($hasForm): ?>
                    <form method="GET" action="<?php echo e(url()->current()); ?><?php echo e($hash); ?>" class="oa-listbar__form" role="search">
                        <?php $__currentLoopData = $keep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($value !== null && $value !== ''): ?>
                                <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php echo e($slot); ?>


                        <?php if($showYear): ?>
                            <select name="tahun" class="form-select form-select-sm rounded-pill" data-autosubmit aria-label="Filter tahun">
                                <option value="">Semua tahun</option>
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($y); ?>" <?php if((string) $in['tahun'] === (string) $y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>

                        <?php if($showSort): ?>
                            <select name="urut" class="form-select form-select-sm rounded-pill" data-autosubmit aria-label="Urutkan">
                                <?php $__currentLoopData = $sorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php if($in['urut'] === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>

                        <?php if($showSearch): ?>
                            <div class="oa-search oa-search--btn">
                                <i class="bi bi-search"></i>
                                <input type="search" name="cari" value="<?php echo e($in['cari']); ?>" class="form-control" placeholder="<?php echo e($placeholder); ?>" aria-label="Cari">
                                <button type="submit" class="oa-search__go" aria-label="Cari">Cari</button>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>

                <?php if($showView): ?>
                    <div class="oa-viewtoggle" role="group" aria-label="Tampilan">
                        <a href="<?php echo e($link(['tampil' => null])); ?>" class="<?php echo e($in['tampil'] !== 'daftar' ? 'active' : ''); ?>" title="Tampilan kartu" aria-label="Tampilan kartu"><i class="bi bi-grid-3x3-gap"></i></a>
                        <a href="<?php echo e($link(['tampil' => 'daftar'])); ?>" class="<?php echo e($in['tampil'] === 'daftar' ? 'active' : ''); ?>" title="Tampilan daftar" aria-label="Tampilan daftar"><i class="bi bi-list-ul"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($showCount || $filtered): ?>
            <div class="oa-listbar__info">
                <?php if($showCount): ?>
                    <span><i class="bi bi-info-circle"></i> <?php echo e(\App\Support\PageOptions::countText($result, $noun)); ?></span>
                <?php endif; ?>
                <?php if($filtered): ?>
                    <a href="<?php echo e($link($resetParams)); ?>" class="oa-listbar__reset"><i class="bi bi-x-circle"></i> Hapus pencarian & filter</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH S:\oa-school\resources\views/components/list-toolbar.blade.php ENDPATH**/ ?>