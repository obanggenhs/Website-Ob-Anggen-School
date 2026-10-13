<?php
    $type = $block->type;
    $info = \App\Models\Block::TYPES[$type];
    $editing = $block->exists;
    // Pilihan media yang sedang aktif (Media + Teks: foto/video/youtube/pdf, Video: youtube/upload)
    $media = old('media_type', $type === 'video' ? $block->videoSource() : $block->mediaKind());
    $docDownload = $editing ? (bool) $block->docSetting('download') : true;
?>

<?php $__env->startSection('title', ($editing ? 'Ubah' : 'Tambah').' blok '.$info['label']); ?>
<?php $__env->startSection('page_title', ($editing ? 'Ubah' : 'Tambah').' blok: '.$info['label']); ?>
<?php $__env->startSection('page_subtitle', 'Halaman "'.$menu->title.'" · '.$info['desc']); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.studio.show', $menu)); ?>"><?php echo e($menu->title); ?></a></li>
    <li class="breadcrumb-item active">Blok</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.studio.show', $menu)); ?>" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali ke halaman</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e($editing ? route('dashboard.blocks.update', [$menu, $block]) : route('dashboard.blocks.store', $menu)); ?>" enctype="multipart/form-data"
          data-media-form>
        <?php echo csrf_field(); ?>
        <?php if($editing): ?> <?php echo method_field('PUT'); ?> <?php else: ?> <input type="hidden" name="type" value="<?php echo e($type); ?>"> <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card dash-card">
                    <div class="card-body">
                        <div class="row g-3">
                            <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'title','label' => $type === 'tombol' ? 'Judul ajakan' : 'Judul blok (opsional)','value' => $block->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type === 'tombol' ? 'Judul ajakan' : 'Judul blok (opsional)'),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->title)]); ?>
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

                            <?php if(in_array($type, ['teks', 'gambar_teks', 'tombol'], true)): ?>
                                <?php if (isset($component)) { $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.editor','data' => ['name' => 'body','label' => 'Isi','value' => $block->body,'required' => $type === 'teks','height' => $type === 'tombol' ? 120 : 260]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'body','label' => 'Isi','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->body),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type === 'teks'),'height' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type === 'tombol' ? 120 : 260)]); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'kutipan'): ?>
                                <?php if (isset($component)) { $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.textarea','data' => ['name' => 'body','label' => 'Kalimat kutipan','value' => strip_tags((string) $block->body),'rows' => '4','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'body','label' => 'Kalimat kutipan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(strip_tags((string) $block->body)),'rows' => '4','required' => true]); ?>
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
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'caption','label' => 'Sumber (opsional)','value' => $block->caption,'placeholder' => 'mis. Amsal 22:6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'caption','label' => 'Sumber (opsional)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->caption),'placeholder' => 'mis. Amsal 22:6']); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'gambar_teks'): ?>
                                
                                <div class="col-12">
                                    <label class="form-label">Media di samping teks</label>
                                    <div class="media-switch media-switch--wrap mb-3" role="radiogroup" aria-label="Jenis media">
                                        <?php $__currentLoopData = \App\Models\Block::MEDIA_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input type="radio" class="btn-check" name="media_type" id="mt_<?php echo e($key); ?>" value="<?php echo e($key); ?>" data-media-type <?php if($media === $key): echo 'checked'; endif; ?>>
                                            <label class="media-switch__item" for="mt_<?php echo e($key); ?>"><i class="bi <?php echo e($m['icon']); ?>"></i> <?php echo e($m['label']); ?></label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <div class="media-panel" data-media-panel="image">
                                        <div class="row g-3">
                                            <?php if (isset($component)) { $__componentOriginal0adeffd803f781016aacf4a89b2e1449 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0adeffd803f781016aacf4a89b2e1449 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image','data' => ['name' => 'image','label' => 'Foto','value' => $block->image,'remove' => 'remove_image']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','label' => 'Foto','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->image),'remove' => 'remove_image']); ?>
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
                                        </div>
                                    </div>
                                    <div class="media-panel" data-media-panel="video">
                                        <?php echo $__env->make('dashboard.studio._media-video', ['path' => $block->video_file], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                    <div class="media-panel" data-media-panel="youtube">
                                        <?php echo $__env->make('dashboard.studio._media-youtube', ['url' => $block->video_url], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                    <div class="media-panel" data-media-panel="pdf">
                                        <?php echo $__env->make('dashboard.studio._media-document', [
                                            'path' => $block->document, 'name' => $block->document_name, 'size' => $block->document_size,
                                            'pdfOnly' => true, 'removable' => false, 'label' => 'Unggah PDF',
                                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        <div class="mt-3">
                                            <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'doc_download','label' => 'Izinkan pengunjung mengunduh PDF','checked' => $docDownload]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'doc_download','label' => 'Izinkan pengunjung mengunduh PDF','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($docDownload)]); ?>
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
                                        <div class="form-text">Di samping teks tampil kartu dokumen dengan sampul halaman pertama. Pengunjung menekan <strong>Baca</strong> untuk membuka PDF di website.</div>
                                    </div>
                                </div>
                                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => 'image_position','label' => 'Posisi media','options' => ['right' => 'Di kanan teks', 'left' => 'Di kiri teks'],'value' => $block->image_position,'col' => 'col-md-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image_position','label' => 'Posisi media','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['right' => 'Di kanan teks', 'left' => 'Di kiri teks']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->image_position),'col' => 'col-md-5']); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'video'): ?>
                                
                                <div class="col-12">
                                    <label class="form-label">Sumber video</label>
                                    <div class="media-switch media-switch--2 mb-3" role="radiogroup" aria-label="Sumber video">
                                        <?php $__currentLoopData = \App\Models\Block::VIDEO_SOURCES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input type="radio" class="btn-check" name="media_type" id="mt_<?php echo e($key); ?>" value="<?php echo e($key); ?>" data-media-type <?php if($media === $key): echo 'checked'; endif; ?>>
                                            <label class="media-switch__item" for="mt_<?php echo e($key); ?>"><i class="bi <?php echo e($m['icon']); ?>"></i> <?php echo e($m['label']); ?></label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="media-panel" data-media-panel="youtube">
                                        <?php echo $__env->make('dashboard.studio._media-youtube', ['url' => $block->video_url], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                    <div class="media-panel" data-media-panel="upload">
                                        <?php echo $__env->make('dashboard.studio._media-video', ['path' => $block->video_file], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'caption','label' => 'Keterangan di bawah video','value' => $block->caption]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'caption','label' => 'Keterangan di bawah video','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->caption)]); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'dokumen'): ?>
                                <?php if (isset($component)) { $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.editor','data' => ['name' => 'body','label' => 'Keterangan (opsional)','value' => $block->body,'height' => 140]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'body','label' => 'Keterangan (opsional)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->body),'height' => 140]); ?>
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
                                <div class="col-12">
                                    <?php echo $__env->make('dashboard.studio._media-document', [
                                        'path' => $block->document, 'name' => $block->document_name, 'size' => $block->document_size,
                                        'pdfOnly' => false, 'removable' => false, 'label' => 'Unggah dokumen',
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            <?php endif; ?>

                            <?php if($type === 'daftar'): ?>
                                <?php if (isset($component)) { $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.textarea','data' => ['name' => 'items','label' => 'Daftar poin (satu baris = satu poin)','value' => $block->items,'rows' => '8','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'items','label' => 'Daftar poin (satu baris = satu poin)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->items),'rows' => '8','required' => true]); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'kartu'): ?>
                                <?php if (isset($component)) { $__componentOriginalcd97a59301ba78d56b3ed60dd41409ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd97a59301ba78d56b3ed60dd41409ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.textarea','data' => ['name' => 'items','label' => 'Kartu (satu baris = satu kartu)','value' => $block->items,'rows' => '8','required' => true,'help' => 'Format: Judul | Keterangan | ikon. Contoh: Iman | Bertumbuh setiap hari | bi-heart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'items','label' => 'Kartu (satu baris = satu kartu)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->items),'rows' => '8','required' => true,'help' => 'Format: Judul | Keterangan | ikon. Contoh: Iman | Bertumbuh setiap hari | bi-heart']); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'galeri'): ?>
                                <div class="col-12">
                                    <label class="form-label" for="f_images">Tambah foto atau video (boleh pilih banyak sekaligus)</label>
                                    <input type="file" name="images[]" id="f_images" class="form-control <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" multiple
                                           data-count-label="imagesCount">
                                    <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text" id="imagesCount"></div>
                                    <div class="form-text">Foto JPG/PNG/WEBP dan video MP4/WEBM/MOV, tanpa batas jumlah dan ukuran. Di website, foto dan video bisa diperbesar dan video diputar langsung.</div>
                                </div>
                                <?php if(! empty($block->images)): ?>
                                    <div class="col-12">
                                        <label class="form-label">Isi galeri saat ini (<?php echo e($block->galleryCount()); ?>) <span class="fw-normal text-muted-d">— centang untuk menghapus</span></label>
                                        <div class="photo-grid">
                                            <?php $__currentLoopData = $block->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="photo-grid__item">
                                                    <?php if(\App\Models\Block::isVideoPath($img)): ?>
                                                        <video src="<?php echo e(media_url($img)); ?>#t=0.5" muted playsinline preload="metadata"></video>
                                                        <span class="photo-grid__badge badge text-bg-dark"><i class="bi bi-play-fill"></i> Video</span>
                                                    <?php else: ?>
                                                        <img src="<?php echo e(media_url($img)); ?>" alt="">
                                                    <?php endif; ?>
                                                    <span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_images[]" value="<?php echo e($i); ?>"></span>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if($type === 'tombol'): ?>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'button_label','label' => 'Teks tombol','value' => $block->button_label,'required' => true,'col' => 'col-md-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'button_label','label' => 'Teks tombol','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->button_label),'required' => true,'col' => 'col-md-5']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'button_url','label' => 'Link tombol','value' => $block->button_url,'required' => true,'col' => 'col-md-7','placeholder' => '/pendaftaran atau https://...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'button_url','label' => 'Link tombol','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->button_url),'required' => true,'col' => 'col-md-7','placeholder' => '/pendaftaran atau https://...']); ?>
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
                            <?php endif; ?>

                            <?php if($type === 'koleksi'): ?>
                                <?php if (isset($component)) { $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.editor','data' => ['name' => 'body','label' => 'Teks pembuka di atas kartu (opsional)','value' => $block->body,'height' => 140]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'body','label' => 'Teks pembuka di atas kartu (opsional)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->body),'height' => 140]); ?>
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
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($type === 'dokumen'): ?>
                    <div class="card dash-card mt-4">
                        <div class="card-header">
                            <h2 class="dash-card__title"><i class="bi bi-file-earmark-pdf me-1"></i> Tampilan dokumen di website</h2>
                            <small class="text-muted-d">Berlaku untuk PDF. File Word, Excel, dan PowerPoint selalu tampil sebagai kartu unduh.</small>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => 'doc_display','label' => 'Cara menampilkan PDF','options' => \App\Models\Block::DOC_DISPLAYS,'value' => $block->docSetting('display'),'col' => 'col-md-7','help' => 'Langsung tampil: pembaca PDF terbuka di halaman. Kartu dokumen: lebih ringkas, PDF dibuka saat pengunjung menekan Baca.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'doc_display','label' => 'Cara menampilkan PDF','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\Block::DOC_DISPLAYS),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->docSetting('display')),'col' => 'col-md-7','help' => 'Langsung tampil: pembaca PDF terbuka di halaman. Kartu dokumen: lebih ringkas, PDF dibuka saat pengunjung menekan Baca.']); ?>
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
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="w-100"><?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'doc_download','label' => 'Izinkan pengunjung mengunduh','checked' => $docDownload]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'doc_download','label' => 'Izinkan pengunjung mengunduh','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($docDownload)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $attributes = $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7)): ?>
<?php $component = $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7; ?>
<?php unset($__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7); ?>
<?php endif; ?></div>
                                </div>
                                <?php if (isset($component)) { $__componentOriginal0adeffd803f781016aacf4a89b2e1449 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0adeffd803f781016aacf4a89b2e1449 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.image','data' => ['name' => 'image','label' => 'Sampul (opsional)','value' => $block->image,'remove' => 'remove_image','col' => 'col-md-7','help' => 'Tampil di kartu dokumen. Bila kosong, halaman pertama PDF dipakai sebagai sampul.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','label' => 'Sampul (opsional)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->image),'remove' => 'remove_image','col' => 'col-md-7','help' => 'Tampil di kartu dokumen. Bila kosong, halaman pertama PDF dipakai sebagai sampul.']); ?>
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
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($type === 'koleksi'): ?>
                    <div class="card dash-card mt-4">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h2 class="dash-card__title"><i class="bi bi-grid-3x3-gap me-1"></i> Tampilan kartu</h2>
                                <small class="text-muted-d">Setiap kartu bisa diklik pengunjung untuk membuka halaman detailnya.</small>
                            </div>
                            <?php if($editing): ?>
                                <a href="<?php echo e(route('dashboard.cards.index', [$menu, $block])); ?>" class="btn btn-sm btn-soft"><i class="bi bi-collection"></i> Kelola kartu (<?php echo e($block->cards()->count()); ?>)</a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => 'card_columns','label' => 'Kartu per baris (layar lebar)','options' => ['2' => '2 kartu', '3' => '3 kartu', '4' => '4 kartu', '6' => '6 kartu'],'value' => (string) $block->cardSetting('columns'),'col' => 'col-md-6','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'card_columns','label' => 'Kartu per baris (layar lebar)','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['2' => '2 kartu', '3' => '3 kartu', '4' => '4 kartu', '6' => '6 kartu']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((string) $block->cardSetting('columns')),'col' => 'col-md-6','required' => true]); ?>
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
                                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['name' => 'card_shape','label' => 'Bentuk gambar kartu','options' => \App\Models\Block::CARD_SHAPES,'value' => $block->cardSetting('shape'),'col' => 'col-md-6','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'card_shape','label' => 'Bentuk gambar kartu','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\Block::CARD_SHAPES),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->cardSetting('shape')),'col' => 'col-md-6','required' => true]); ?>
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
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'card_button','label' => 'Teks tautan di kartu','value' => $block->cardSetting('button'),'col' => 'col-md-6','placeholder' => 'Lihat detail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'card_button','label' => 'Teks tautan di kartu','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->cardSetting('button')),'col' => 'col-md-6','placeholder' => 'Lihat detail']); ?>
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
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="w-100">
                                        <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'card_excerpt','label' => 'Tampilkan ringkasan di kartu','checked' => (bool) $block->cardSetting('excerpt')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'card_excerpt','label' => 'Tampilkan ringkasan di kartu','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) $block->cardSetting('excerpt'))]); ?>
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
                            </div>
                            <div class="alert alert-light border small mt-3 mb-0 d-flex gap-2">
                                <i class="bi bi-info-circle text-primary"></i>
                                <div>
                                    <?php if($editing): ?>
                                        Isi kartu (nama, foto, kategori, tahun, isi detail) diubah lewat tombol <strong>Kelola kartu</strong>.
                                    <?php else: ?>
                                        Setelah blok ini disimpan, Anda langsung diarahkan untuk menambah kartu pertama.
                                    <?php endif; ?>
                                    Pencarian, filter, dan urutan untuk pengunjung diatur di bagian <strong>Fitur bantuan pengunjung</strong> pada halaman ini.
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <?php
                    // Pilihan tampilan blok yang sedang aktif (ikut nilai lama bila formulir gagal disimpan)
                    $previous = $previous ?? null;   // blok sebelumnya yang tampil di website
                    $lpWidth = (string) old('width', $block->widthKey());
                    $lpPlace = (string) old('placement', $block->placement ?: 'left');
                    $lpPair = (string) old('pairing', $block->pairingKey());
                    $lpStyle = (string) old('style', $block->style ?: 'plain');
                    $lpAlign = (string) old('align', $block->contentAlign());
                    $lpTitle = (string) old('title_align', $block->title_align ?: 'ikut');
                    $styleIcons = ['plain' => 'bi-square', 'card' => 'bi-card-heading', 'accent' => 'bi-square-fill'];
                ?>
                <div class="card dash-card mb-4" data-block-layout>
                    <div class="card-header">
                        <h2 class="dash-card__title"><i class="bi bi-layout-text-window-reverse me-1"></i> Tampilan blok</h2>
                        <small class="text-muted-d">Pratinjau di bawah mengikuti pilihan Anda.</small>
                    </div>
                    <div class="card-body">
                        
                        <div class="layout-preview" aria-hidden="true">
                            <div class="layout-preview__page">
                                <div class="layout-preview__line" data-lp-line="1">
                                    <?php if($previous): ?>
                                        <div class="layout-preview__cell" data-lp-prev-cell data-w="<?php echo e($previous->widthKey()); ?>" data-place="<?php echo e($previous->placementKey()); ?>">
                                            <div class="layout-preview__block layout-preview__block--prev">
                                                <span class="layout-preview__title"><?php echo e(\Illuminate\Support\Str::limit($previous->title ?: $previous->type_label, 30)); ?></span>
                                                <span class="layout-preview__text"><i></i><i></i><i></i></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="layout-preview__cell" data-lp-cell>
                                        <div class="layout-preview__block" data-lp-block>
                                            <span class="layout-preview__title" data-lp-title>Judul blok</span>
                                            <span class="layout-preview__text"><i></i><i></i><i></i><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="layout-preview__line" data-lp-line="2" hidden></div>
                            </div>
                            <?php if($previous): ?>
                                <div class="layout-preview__legend"><span><i class="is-prev"></i> Blok sebelumnya</span><span><i></i> Blok ini</span></div>
                            <?php endif; ?>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="f_width" class="form-label">Lebar</label>
                                <select name="width" id="f_width" class="form-select <?php $__errorArgs = ['width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__currentLoopData = \App\Models\Block::WIDTHS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php if($lpWidth === (string) $key): echo 'selected'; endif; ?>><?php echo e($w['label']); ?><?php echo e($key === 12 ? ' (selebar halaman)' : ' ('.$w['short'].' halaman)'); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Dua blok setengah tampil berdampingan. Di layar HP, blok selalu tampil penuh.</div>
                            </div>

                            <div class="col-12 <?php echo e($previous ? '' : 'is-off'); ?>" data-lp-pairing>
                                <label class="form-label">Susunan dengan blok sebelumnya</label>
                                <div class="seg-choice seg-choice--4" role="radiogroup" aria-label="Susunan dengan blok sebelumnya">
                                    <?php $__currentLoopData = \App\Models\Block::PAIRINGS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="pairing" id="pr_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($lpPair === $key): echo 'checked'; endif; ?>>
                                        <label class="seg-choice__item" for="pr_<?php echo e($key); ?>" title="<?php echo e($o['desc']); ?>"><i class="bi <?php echo e($o['icon']); ?>"></i><span><?php echo e($o['label']); ?></span></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php $__errorArgs = ['pairing'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php if($previous): ?>
                                    <div class="form-text pair-prev">
                                        <i class="bi bi-link-45deg"></i> Blok sebelumnya: <strong><?php echo e(\Illuminate\Support\Str::limit($previous->title ?: $previous->type_label, 40)); ?></strong>
                                        <span class="text-muted-d">(<?php echo e($previous->type_label); ?> · <?php echo e($previous->layoutLabel()); ?>)</span>
                                    </div>
                                    <div class="form-text" data-pair-hint="auto">Mengikuti lebar &amp; posisi. Blok Kiri yang lebarnya muat otomatis tampil berdampingan.</div>
                                    <div class="form-text" data-pair-hint="right" hidden><strong>Bersebelahan:</strong> blok ini di kanan blok sebelumnya. Bila total lebar keduanya lebih dari Penuh, keduanya dibuat sama lebar.</div>
                                    <div class="form-text" data-pair-hint="left" hidden><strong>Bersebelahan:</strong> blok ini di depan (kiri) blok sebelumnya. Bila total lebar keduanya lebih dari Penuh, keduanya dibuat sama lebar.</div>
                                    <div class="form-text" data-pair-hint="below" hidden><strong>Atas-bawah:</strong> blok ini selalu mulai di baris baru, di bawah blok sebelumnya.</div>
                                <?php else: ?>
                                    <div class="form-text"><i class="bi bi-info-circle"></i> Ini blok pertama yang tampil di halaman, jadi belum ada blok sebelumnya. Pilihan ini berlaku mulai blok kedua.</div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12" data-lp-placement>
                                <label class="form-label">Posisi blok</label>
                                <div class="seg-choice" role="radiogroup" aria-label="Posisi blok">
                                    <?php $__currentLoopData = \App\Models\Block::PLACEMENTS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="placement" id="pl_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($lpPlace === $key): echo 'checked'; endif; ?>>
                                        <label class="seg-choice__item" for="pl_<?php echo e($key); ?>"><i class="bi <?php echo e($o['icon']); ?>"></i><span><?php echo e($o['label']); ?></span></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="form-text seg-note seg-note--on">Kiri: blok-blok Kiri yang lebarnya muat tampil berdampingan. Tengah &amp; Kanan: blok tampil di barisnya sendiri.</div>
                                <div class="form-text seg-note seg-note--off"><i class="bi bi-info-circle"></i> Pilih lebar selain <strong>Penuh</strong> agar posisi blok berlaku.</div>
                                <div class="form-text seg-note seg-note--pair"><i class="bi bi-info-circle"></i> Saat bersebelahan, letak kedua blok mengikuti posisi blok sebelumnya.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Gaya</label>
                                <div class="seg-choice" role="radiogroup" aria-label="Gaya blok">
                                    <?php $__currentLoopData = \App\Models\Block::STYLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="style" id="st_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($lpStyle === $key): echo 'checked'; endif; ?>>
                                        <label class="seg-choice__item" for="st_<?php echo e($key); ?>" title="<?php echo e($label); ?>"><i class="bi <?php echo e($styleIcons[$key]); ?>"></i><span><?php echo e(\Illuminate\Support\Str::before($label, ' (')); ?></span></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php $__errorArgs = ['style'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Perataan judul</label>
                                <div class="seg-choice seg-choice--4" role="radiogroup" aria-label="Perataan judul">
                                    <?php $__currentLoopData = \App\Models\Block::TITLE_ALIGNS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="title_align" id="ta_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($lpTitle === $key): echo 'checked'; endif; ?>>
                                        <label class="seg-choice__item" for="ta_<?php echo e($key); ?>"><i class="bi <?php echo e($o['icon']); ?>"></i><span><?php echo e($o['label']); ?></span></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Perataan isi / paragraf</label>
                                <div class="seg-choice seg-choice--4" role="radiogroup" aria-label="Perataan isi">
                                    <?php $__currentLoopData = \App\Models\Block::ALIGNS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="align" id="al_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($lpAlign === $key): echo 'checked'; endif; ?>>
                                        <label class="seg-choice__item" for="al_<?php echo e($key); ?>" title="<?php echo e($o['label']); ?>"><i class="bi <?php echo e($o['icon']); ?>"></i><span><?php echo e($key === 'justify' ? 'Kiri-kanan' : ucfirst(\Illuminate\Support\Str::after($o['label'], 'Rata '))); ?></span></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php $__errorArgs = ['align'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Berlaku untuk seluruh isi blok. Perataan satu paragraf saja bisa diatur dari tombol perataan di editor.</div>
                            </div>

                            <?php if (isset($component)) { $__componentOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcda656c328d9f3b1b35a13f7eac62cf7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.toggle','data' => ['name' => 'is_active','label' => 'Tampilkan blok ini','checked' => $block->is_active]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_active','label' => 'Tampilkan blok ini','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($block->is_active)]); ?>
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
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> <?php echo e($editing ? 'Simpan perubahan' : ($type === 'koleksi' ? 'Simpan & tambah kartu' : 'Tambahkan blok')); ?></button>
                    <a href="<?php echo e(route('dashboard.studio.show', $menu)); ?>" class="btn btn-light">Batal</a>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/studio/block-form.blade.php ENDPATH**/ ?>