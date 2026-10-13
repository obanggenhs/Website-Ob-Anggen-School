
<?php
    $paramMap = collect($types)->mapWithKeys(fn ($t, $key) => [$key => \App\Support\Modules::paramOptions($key)]);
    $iconSuggestions = ['bi-house-door', 'bi-info-circle', 'bi-book', 'bi-mortarboard', 'bi-newspaper', 'bi-images', 'bi-calendar3', 'bi-people', 'bi-building', 'bi-pencil-square', 'bi-trophy', 'bi-heart', 'bi-telephone', 'bi-globe'];
?>
<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?php echo e(route('dashboard.studio.update', $menu)); ?>" class="card dash-card" data-type-param data-params='<?php echo json_encode($paramMap, 15, 512) ?>' data-root-only='[]'>
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="card-header"><h2 class="dash-card__title">Pengaturan menu</h2></div>
            <div class="card-body">
                <div class="row g-3">
                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'title','label' => 'Nama menu','value' => $menu->title,'required' => true,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Nama menu','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->title),'required' => true,'col' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'slug','label' => 'Alamat (slug)','value' => $menu->slug,'required' => true,'col' => 'col-md-6','help' => ''.e($menu->type === 'halaman' ? url('/halaman').'/'.$menu->slug : 'Dipakai sebagai pengenal unik menu.').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'slug','label' => 'Alamat (slug)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->slug),'required' => true,'col' => 'col-md-6','help' => ''.e($menu->type === 'halaman' ? url('/halaman').'/'.$menu->slug : 'Dipakai sebagai pengenal unik menu.').'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>

                    <div class="col-md-6">
                        <label class="form-label" for="f_type">Jenis isi</label>
                        <select name="type" id="f_type" class="form-select" <?php if($menu->type === 'beranda'): echo 'disabled'; endif; ?>>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php if(old('type', $menu->type) === $key): echo 'selected'; endif; ?>><?php echo e($t['label']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($menu->type === 'beranda'): ?><input type="hidden" name="type" value="beranda"><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="f_parent_id">Letak</label>
                        <select name="parent_id" id="f_parent_id" class="form-select" <?php if($menu->isGroup() || $menu->type === 'beranda'): echo 'disabled'; endif; ?>>
                            <option value="">Menu utama</option>
                            <?php $__currentLoopData = $roots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $root): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($root->id); ?>" <?php if((int) old('parent_id', $menu->parent_id) === $root->id): echo 'selected'; endif; ?>>Submenu di "<?php echo e($root->title); ?>"</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-none" data-param-wrap>
                        <label class="form-label" for="f_param">Tampilkan</label>
                        <select name="param" id="f_param" class="form-select" data-value="<?php echo e(old('param', $menu->param)); ?>"></select>
                    </div>
                    <div class="col-md-6 d-none" data-url-wrap>
                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'url','label' => 'Alamat tautan','value' => $menu->url,'col' => '','placeholder' => 'https://...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'url','label' => 'Alamat tautan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->url),'col' => '','placeholder' => 'https://...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="f_icon">Ikon (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i id="iconPreview"></i></span>
                            <input type="text" name="icon" id="f_icon" value="<?php echo e(old('icon', $menu->icon)); ?>" class="form-control" placeholder="bi-star" data-icon-input="iconPreview">
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            <?php $__currentLoopData = $iconSuggestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="btn btn-sm btn-light btn-icon" data-icon-pick="f_icon" data-icon="<?php echo e($icon); ?>" title="<?php echo e($icon); ?>"><i class="bi <?php echo e($icon); ?>"></i></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="form-text">Kosongkan untuk memakai ikon bawaan jenis isi. Daftar lengkap: icons.getbootstrap.com</div>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>
                    <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'is_active','label' => 'Tampilkan menu ini di website','checked' => $menu->is_active,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_active','label' => 'Tampilkan menu ini di website','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->is_active),'col' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $attributes = $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $component = $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'show_sidebar','label' => 'Tampilkan sidebar (berita & kontak)','checked' => $menu->show_sidebar,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'show_sidebar','label' => 'Tampilkan sidebar (berita & kontak)','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->show_sidebar),'col' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $attributes = $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $component = $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
                    <?php if(! $menu->parent_id): ?>
                        <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'is_highlight','label' => 'Jadikan tombol menonjol di ujung navbar','checked' => $menu->is_highlight,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_highlight','label' => 'Jadikan tombol menonjol di ujung navbar','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->is_highlight),'col' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $attributes = $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $component = $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
                    <?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'open_new_tab','label' => 'Buka di tab baru','checked' => $menu->open_new_tab,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'open_new_tab','label' => 'Buka di tab baru','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->open_new_tab),'col' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $attributes = $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $component = $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
                </div>
            </div>
            <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan pengaturan</button></div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card dash-card mb-4">
            <div class="card-header"><h2 class="dash-card__title">Urutan</h2></div>
            <div class="card-body d-flex gap-2">
                <form method="POST" action="<?php echo e(route('dashboard.studio.move', [$menu, 'naik'])); ?>" class="flex-fill"><?php echo csrf_field(); ?><button class="btn btn-light w-100"><i class="bi bi-arrow-up"></i> Naikkan</button></form>
                <form method="POST" action="<?php echo e(route('dashboard.studio.move', [$menu, 'turun'])); ?>" class="flex-fill"><?php echo csrf_field(); ?><button class="btn btn-light w-100"><i class="bi bi-arrow-down"></i> Turunkan</button></form>
            </div>
        </div>

        <?php if($menu->type !== 'beranda'): ?>
            <div class="card dash-card border border-danger-subtle">
                <div class="card-header"><h2 class="dash-card__title text-danger">Hapus menu</h2></div>
                <div class="card-body">
                    <p class="small text-muted-d">
                        Menghapus menu juga menghapus isi halamannya<?php echo e($menu->children->isNotEmpty() ? ' dan '.$menu->children->count().' submenu di dalamnya' : ''); ?>.
                        Data modul (berita, galeri, dll.) tetap aman.
                    </p>
                    <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.studio.destroy', $menu), 'class' => 'btn-danger w-100', 'text' => 'Hapus "'.$menu->title.'"', 'confirm' => 'Hapus menu "'.$menu->title.'" beserta isinya?'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_settings.blade.php ENDPATH**/ ?>