<?php $__env->startSection('title', 'Pengaturan Website'); ?>
<?php $__env->startSection('page_title', 'Pengaturan Website'); ?>
<?php $__env->startSection('page_subtitle', 'Identitas, kontak, warna, halaman login, dan pengaturan umum. Isi Beranda diatur dari Studio › Beranda.'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card dash-card">
                <div class="list-group list-group-flush rounded-3">
                    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('dashboard.settings.edit', $key)); ?>" class="list-group-item list-group-item-action d-flex gap-2 align-items-center <?php echo e($tab === $key ? 'active' : ''); ?>" <?php if($tab === $key): ?> style="background: var(--dash-primary); border-color: var(--dash-primary)" <?php endif; ?>>
                            <i class="bi <?php echo e($t['icon']); ?>"></i> <?php echo e($t['label']); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <?php if(! empty($tabs[$tab]['view'])): ?>
                
                <?php echo $__env->make($tabs[$tab]['view'], ['tabInfo' => $tabs[$tab]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('dashboard.settings.update', $tab)); ?>" enctype="multipart/form-data" class="card dash-card">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="card-header"><h2><i class="bi <?php echo e($tabs[$tab]['icon']); ?> me-1"></i> <?php echo e($tabs[$tab]['label']); ?></h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php $__currentLoopData = $tabs[$tab]['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $col = 'col-md-'.($f['col'] ?? 12); $val = setting($key); ?>
                                <?php switch($f['type']):
                                    case ('image'): ?>
                                        <?php if (isset($component)) { $__componentOriginal0adeffd803f781016aacf4a89b2e1449 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0adeffd803f781016aacf4a89b2e1449 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image','data' => ['name' => $key,'label' => $f['label'],'value' => $val,'col' => isset($f['col']) ? $col : 'col-md-6','logo' => str_starts_with($key, 'logo') || $key === 'favicon','help' => 'PNG / JPG / WEBP.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($f['col']) ? $col : 'col-md-6'),'logo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(str_starts_with($key, 'logo') || $key === 'favicon'),'help' => 'PNG / JPG / WEBP.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0adeffd803f781016aacf4a89b2e1449)): ?>
<?php $attributes = $__attributesOriginal0adeffd803f781016aacf4a89b2e1449; ?>
<?php unset($__attributesOriginal0adeffd803f781016aacf4a89b2e1449); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0adeffd803f781016aacf4a89b2e1449)): ?>
<?php $component = $__componentOriginal0adeffd803f781016aacf4a89b2e1449; ?>
<?php unset($__componentOriginal0adeffd803f781016aacf4a89b2e1449); ?>
<?php endif; ?>
                                        <?php break; ?>
                                    <?php case ('color'): ?>
                                        <div class="<?php echo e($col); ?>">
                                            <label class="form-label" for="c_<?php echo e($key); ?>"><?php echo e($f['label']); ?></label>
                                            <div class="input-group">
                                                <input type="color" class="form-control form-control-color" value="<?php echo e($val); ?>" data-color-sync="c_<?php echo e($key); ?>" title="Pilih warna">
                                                <input type="text" name="<?php echo e($key); ?>" id="c_<?php echo e($key); ?>" value="<?php echo e(old($key, $val)); ?>" class="form-control" maxlength="7">
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php case ('textarea'): ?>
                                        <?php if (isset($component)) { $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.textarea','data' => ['name' => $key,'label' => $f['label'],'value' => $val,'col' => $col]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($col)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab)): ?>
<?php $attributes = $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab; ?>
<?php unset($__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd97a59301ba78d56b3ed60dd41409ab)): ?>
<?php $component = $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab; ?>
<?php unset($__componentOriginalcd97a59301ba78d56b3ed60dd41409ab); ?>
<?php endif; ?>
                                        <?php break; ?>
                                    <?php case ('toggle'): ?>
                                        <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => $key,'label' => $f['label'],'checked' => (bool) $val,'col' => $col]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) $val),'col' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($col)]); ?>
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
                                        <?php break; ?>
                                    <?php case ('select'): ?>
                                        <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => $key,'label' => $f['label'],'options' => $f['options'],'value' => $val,'col' => $col]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['options']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($col)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $attributes = $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $component = $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => $key,'label' => $f['label'],'type' => in_array($f['type'], ['email', 'url', 'number']) ? $f['type'] : 'text','value' => $val,'col' => $col]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(in_array($f['type'], ['email', 'url', 'number']) ? $f['type'] : 'text'),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($col)]); ?>
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
                                <?php endswitch; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan pengaturan</button></div>
                </form>
                <?php if(! empty($tabs[$tab]['after'])): ?>
                    <?php echo $__env->make($tabs[$tab]['after'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/settings/edit.blade.php ENDPATH**/ ?>