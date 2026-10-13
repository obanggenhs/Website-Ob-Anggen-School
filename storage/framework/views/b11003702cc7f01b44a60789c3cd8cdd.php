
<?php
    $optType = $menu->type === 'beranda' ? 'halaman' : $menu->type;
    $current = $menu->pageOptions();
    $collections = $menu->isPage() ? $menu->blocks->where('type', 'koleksi')->count() : null;
?>

<form method="POST" action="<?php echo e(route('dashboard.studio.options', $menu)); ?>" class="card dash-card mb-4" id="fitur-halaman">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title"><i class="bi bi-ui-checks-grid me-1"></i> Fitur bantuan pengunjung</h2>
            <small class="text-muted-d">
                <?php if($menu->isPage()): ?>
                    Berlaku untuk blok <strong>Koleksi Kartu</strong> di halaman ini. Pilih fitur yang memudahkan pengunjung mencari isi.
                <?php else: ?>
                    Berlaku untuk daftar <?php echo e(\Illuminate\Support\Str::lower($menu->type_label)); ?> di halaman ini.
                <?php endif; ?>
            </small>
        </div>
        <span class="badge badge-soft"><?php echo e(collect($current)->except('per_page')->filter()->count()); ?> fitur aktif</span>
    </div>

    <div class="card-body">
        <?php if($collections === 0): ?>
            <div class="alert alert-light border small d-flex gap-2 align-items-start">
                <i class="bi bi-lightbulb text-warning"></i>
                <div>Halaman ini belum punya blok <strong>Koleksi Kartu</strong>. Pilihan di bawah akan dipakai setelah blok tersebut ditambahkan lewat tombol <em>Tambah blok › Koleksi Kartu</em>.</div>
            </div>
        <?php endif; ?>

        <div class="option-grid">
            <?php $__currentLoopData = \App\Support\PageOptions::SUPPORT[$optType]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($key === 'per_page') continue; ?>
                <label class="option-tile" for="opt_<?php echo e($key); ?>">
                    <span class="option-tile__icon"><i class="bi <?php echo e(\App\Support\PageOptions::OPTIONS[$key]['icon']); ?>"></i></span>
                    <span class="option-tile__text">
                        <strong><?php echo e(\App\Support\PageOptions::label($optType, $key)); ?></strong>
                        <small><?php echo e(\App\Support\PageOptions::OPTIONS[$key]['desc']); ?></small>
                    </span>
                    <span class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" role="switch" name="opt_<?php echo e($key); ?>" id="opt_<?php echo e($key); ?>" value="1" <?php if($current[$key]): echo 'checked'; endif; ?>>
                    </span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if(in_array('per_page', \App\Support\PageOptions::SUPPORT[$optType], true)): ?>
            <div class="row g-2 align-items-center mt-3">
                <div class="col-sm-auto"><label class="form-label mb-0" for="per_page"><i class="bi <?php echo e(\App\Support\PageOptions::OPTIONS['per_page']['icon']); ?> me-1"></i> <?php echo e(\App\Support\PageOptions::OPTIONS['per_page']['label']); ?></label></div>
                <div class="col-sm-auto">
                    <select name="per_page" id="per_page" class="form-select form-select-sm option-perpage">
                        <?php $__currentLoopData = \App\Support\PageOptions::PER_PAGE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($n); ?>" <?php if($current['per_page'] === $n): echo 'selected'; endif; ?>><?php echo e($n); ?> per halaman</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-sm"><small class="text-muted-d"><?php echo e(\App\Support\PageOptions::OPTIONS['per_page']['desc']); ?></small></div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
        <?php if($menu->type !== 'dropdown'): ?>
            <a href="<?php echo e($menu->link); ?>" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat hasilnya di website</a>
        <?php endif; ?>
        <button class="btn btn-primary btn-sm"><i class="bi bi-check2-circle me-1"></i> Simpan fitur halaman</button>
    </div>
</form>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_content-options.blade.php ENDPATH**/ ?>