
<?php
    $groups = [
        'Hero (bagian paling atas)' => ['hero_title', 'hero_subtitle', 'hero_button', 'hero_button_url'],
        'Angka statistik' => ['stat_campus', 'stat_students', 'stat_teachers'],
        'Sambutan' => ['welcome_title', 'welcome_text', 'welcome_link', 'welcome_image'],
        'Video profil' => ['video_title', 'video_text', 'video_url', 'video_bg'],
        'Bagian yang ditampilkan' => ['home_show_quick', 'home_show_branches', 'home_show_agenda', 'home_show_news', 'home_show_video', 'home_show_testimonials', 'home_blocks_position'],
    ];
?>
<form method="POST" action="<?php echo e(route('dashboard.studio.home', $menu)); ?>" enctype="multipart/form-data" class="card dash-card mb-4">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="card-header d-flex justify-content-between align-items-center">
        <div><h2 class="dash-card__title">Isi Beranda</h2><small class="text-muted-d">Klik setiap bagian untuk membuka.</small></div>
        <button class="btn btn-primary btn-sm"><i class="bi bi-check2-circle me-1"></i> Simpan Beranda</button>
    </div>
    <div class="accordion accordion-flush" id="homeAccordion">
        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $keys): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button <?php echo e($loop->first ? '' : 'collapsed'); ?> fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#home<?php echo e($loop->index); ?>"><?php echo e($label); ?></button>
                </h3>
                <div id="home<?php echo e($loop->index); ?>" class="accordion-collapse collapse <?php echo e($loop->first ? 'show' : ''); ?>" data-bs-parent="#homeAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <?php $__currentLoopData = $keys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $f = $homeFields[$key]; $val = setting($key); ?>
                                <?php switch($f['type']):
                                    case ('image'): ?>
                                        <?php if (isset($component)) { $__componentOriginal0adeffd803f781016aacf4a89b2e1449 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0adeffd803f781016aacf4a89b2e1449 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image','data' => ['name' => $key,'label' => $f['label'],'value' => $val,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => 'col-md-6']); ?>
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
                                    <?php case ('editor'): ?>
                                        <?php if (isset($component)) { $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.editor','data' => ['name' => $key,'label' => $f['label'],'value' => $val,'height' => 160]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'height' => 160]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1)): ?>
<?php $attributes = $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1; ?>
<?php unset($__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1)): ?>
<?php $component = $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1; ?>
<?php unset($__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1); ?>
<?php endif; ?>
                                        <?php break; ?>
                                    <?php case ('textarea'): ?>
                                        <?php if (isset($component)) { $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.textarea','data' => ['name' => $key,'label' => $f['label'],'value' => $val]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => $key,'label' => $f['label'],'checked' => (bool) $val,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) $val),'col' => 'col-md-6']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => $key,'label' => $f['label'],'options' => $f['options'],'value' => $val,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['options']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => 'col-md-6']); ?>
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
                                    <?php case ('number'): ?>
                                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => $key,'label' => $f['label'],'type' => 'number','value' => $val,'col' => 'col-md-4','min' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'type' => 'number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => 'col-md-4','min' => '0']); ?>
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
                                        <?php break; ?>
                                    <?php default: ?>
                                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => $key,'label' => $f['label'],'type' => $f['type'] === 'url' ? 'url' : 'text','value' => $val,'col' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['label']),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($f['type'] === 'url' ? 'url' : 'text'),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($val),'col' => 'col-md-6']); ?>
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
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan Beranda</button></div>
</form><?php /**PATH S:\oa-school\resources\views/dashboard/studio/_content-home.blade.php ENDPATH**/ ?>