
<?php
    $groups = $tree->where('type', 'dropdown');
    $paramMap = collect($types)->mapWithKeys(fn ($t, $key) => [$key => \App\Support\Modules::paramOptions($key)]);
    $rootOnly = collect($types)->filter(fn ($t) => ! empty($t['root_only']))->keys()->values();
    $usedSingle = \App\Models\Menu::whereIn('type', collect($types)->filter(fn ($t) => ! empty($t['single']))->keys())->pluck('type')->all();
?>
<div class="modal fade" id="menuCreateModal" tabindex="-1" aria-labelledby="menuCreateLabel" aria-hidden="true" <?php if($errors->any() && old('_form') === 'create-menu'): ?> data-auto-open <?php endif; ?>>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form method="POST" action="<?php echo e(route('dashboard.studio.store')); ?>" class="modal-content" data-type-param data-params='<?php echo json_encode($paramMap, 15, 512) ?>' data-root-only='<?php echo json_encode($rootOnly, 15, 512) ?>'>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_form" value="create-menu">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="menuCreateLabel"><i class="bi bi-plus-square me-1"></i> Tambah menu</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label" for="new_title">1. Nama menu <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="new_title" value="<?php echo e(old('title')); ?>" class="form-control" placeholder="mis. Prestasi Siswa" required maxlength="80">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="new_parent">2. Letakkan di</label>
                        <select name="parent_id" id="new_parent" class="form-select">
                            <option value="">Menu utama (tampil langsung di navbar)</option>
                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($g->id); ?>" <?php if(old('parent_id') == $g->id): echo 'selected'; endif; ?>>Submenu di dalam "<?php echo e($g->title); ?>"</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text">Submenu hanya bisa ditaruh di menu bertipe Grup Dropdown.</div>
                    </div>
                </div>

                <label class="form-label">3. Jenis isi menu <span class="text-danger">*</span></label>
                <div class="type-grid mb-4">
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $taken = in_array($key, $usedSingle, true); ?>
                        <div class="type-option">
                            <input type="radio" name="type" id="type_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if(old('type', 'halaman') === $key): echo 'checked'; endif; ?> <?php if($taken): echo 'disabled'; endif; ?>>
                            <label for="type_<?php echo e($key); ?>">
                                <i class="bi <?php echo e($t['icon']); ?>"></i>
                                <span><strong><?php echo e($t['label']); ?></strong><small><?php echo e($taken ? 'Sudah ada (hanya boleh satu)' : $t['desc']); ?></small></span>
                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 d-none" data-param-wrap>
                        <label class="form-label" for="new_param">Tampilkan</label>
                        <select name="param" id="new_param" class="form-select" data-value="<?php echo e(old('param')); ?>"></select>
                    </div>
                    <div class="col-md-6 d-none" data-url-wrap>
                        <label class="form-label" for="new_url">Alamat tautan</label>
                        <input type="text" name="url" id="new_url" value="<?php echo e(old('url')); ?>" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">4. Ikut dikelola oleh <span class="fw-normal text-muted-d">(bisa diubah nanti di tab Akses Dashboard)</span></label>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="access_publikasi" id="new_pub" value="1" <?php if(old('access_publikasi')): echo 'checked'; endif; ?>>
                                <label class="form-check-label" for="new_pub"><span class="role-chip role-chip--publikasi">PUB</span> Admin Publikasi</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="access_pendaftaran" id="new_reg" value="1" <?php if(old('access_pendaftaran')): echo 'checked'; endif; ?>>
                                <label class="form-check-label" for="new_reg"><span class="role-chip role-chip--pendaftaran">DAF</span> Admin Pendaftaran</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Buat menu</button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_create-modal.blade.php ENDPATH**/ ?>