
<div class="<?php echo e($b->columnClass()); ?> oa-block-col oa-place--<?php echo e($b->placementKey()); ?>">
    <div class="<?php echo e($b->layoutClass()); ?> h-100" id="blok-<?php echo e($b->id); ?>">
        <?php switch($b->type):
            case ('teks'): ?>
                <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                <div class="prose"><?php echo $b->body; ?></div>
                <?php break; ?>

            <?php case ('gambar_teks'): ?>
                
                <div class="row g-3 align-items-center <?php echo e($b->image_position === 'left' ? 'flex-md-row-reverse' : ''); ?>">
                    <div class="col-md-7">
                        <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                        <div class="prose"><?php echo $b->body; ?></div>
                    </div>
                    <div class="col-md-5 oa-block__media <?php echo e(in_array($b->mediaKind(), ['video', 'youtube', 'pdf'], true) ? 'oa-block__media--free' : ''); ?>">
                        <?php switch($b->mediaKind()):
                            case ('video'): ?>
                                <?php echo $__env->make('web.partials.video', ['url' => $b->video_file ? media_url($b->video_file) : null, 'title' => $b->title ?: 'Video', 'class' => 'oa-video--side'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php break; ?>
                            <?php case ('youtube'): ?>
                                <?php echo $__env->make('web.partials.video', ['url' => $b->video_url, 'title' => $b->title ?: 'Video', 'class' => 'oa-video--side'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php break; ?>
                            <?php case ('pdf'): ?>
                                <?php if($b->document): ?>
                                    <?php echo $__env->make('web.partials.document', [
                                        'path' => $b->document, 'name' => $b->document_name, 'size' => $b->document_size,
                                        'title' => \App\Support\Media::displayName($b->document_name, $b->document, $b->title ?: 'Dokumen PDF'), 'mode' => 'card', 'layout' => 'stack',
                                        'download' => (bool) $b->docSetting('download'),
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php default: ?>
                                <a href="<?php echo e(media_url($b->image)); ?>" class="glightbox"><img src="<?php echo e(media_url($b->image)); ?>" alt="<?php echo e($b->title); ?>" loading="lazy"></a>
                        <?php endswitch; ?>
                    </div>
                </div>
                <?php break; ?>

            <?php case ('video'): ?>
                <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                <?php echo $__env->make('web.partials.video', ['url' => $b->videoLink(), 'title' => $b->title ?: 'Video', 'class' => 'oa-video--block'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if($b->caption): ?><p class="oa-caption"><?php echo e($b->caption); ?></p><?php endif; ?>
                <?php break; ?>

            <?php case ('dokumen'): ?>
                <?php
                    // PDF tampil langsung: judul blok & keterangan di atas pembaca.
                    // Kartu dokumen & file Word/Excel/PowerPoint: judul & keterangan ada di dalam kartunya.
                    $docInline = \App\Support\Media::isPdf($b->document) && $b->docSetting('display') === 'reader';
                    $docDesc = \Illuminate\Support\Str::limit(trim(strip_tags((string) $b->body)), 260);
                ?>
                <?php if($docInline || ! $b->document): ?>
                    <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                    <?php if(strip_tags((string) $b->body) !== ''): ?><div class="prose mb-3"><?php echo $b->body; ?></div><?php endif; ?>
                <?php endif; ?>
                <?php if($b->document): ?>
                    <?php echo $__env->make('web.partials.document', [
                        'path' => $b->document, 'name' => $b->document_name, 'size' => $b->document_size,
                        'title' => match (true) {
                            $docInline && $b->title => $b->document_name ?: \App\Support\Media::displayName(null, $b->document),
                            ! $b->title             => \App\Support\Media::displayName($b->document_name, $b->document),
                            default                 => $b->title,
                        },
                        'desc' => $docInline ? null : ($docDesc ?: null), 'mode' => $b->docSetting('display'),
                        'cover' => $b->image ? media_url($b->image) : null, 'download' => (bool) $b->docSetting('download'),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php break; ?>

            <?php case ('daftar'): ?>
                <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                <ol class="oa-numbered">
                    <?php $__currentLoopData = lines($b->items); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($line); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
                <?php break; ?>

            <?php case ('kartu'): ?>
                <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                <?php
                    // Kolom kartu mengikuti lebar blok: di blok sempit (setengah atau kurang) kartu tersusun satu kolom
                    $cardCol = match ($b->displayWidth()) {
                        12      => 'col-md-6 col-xl-'.(count($b->cardItems()) % 3 === 0 ? 4 : 6),
                        8       => 'col-md-6',
                        3       => 'col-12',
                        default => 'col-md-6 col-lg-12',
                    };
                ?>
                <div class="row g-2 g-md-3">
                    <?php $__currentLoopData = $b->cardItems(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="<?php echo e($cardCol); ?>">
                            <div class="oa-feature">
                                <i class="bi <?php echo e($card['icon']); ?>"></i>
                                <div>
                                    <h3><?php echo e($card['title']); ?></h3>
                                    <?php if($card['text']): ?><p><?php echo e($card['text']); ?></p><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php break; ?>

            <?php case ('galeri'): ?>
                <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                <div class="oa-gallery">
                    <?php $__currentLoopData = $b->images ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(\App\Models\Block::isVideoPath($img)): ?>
                            
                            <a href="<?php echo e(media_url($img)); ?>" class="oa-gallery__video" data-video-modal title="Putar video">
                                <video src="<?php echo e(media_url($img)); ?>#t=0.5" muted playsinline preload="metadata"></video>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(media_url($img)); ?>" class="glightbox" data-gallery="blok-<?php echo e($b->id); ?>"><img src="<?php echo e(media_url($img)); ?>" alt="" loading="lazy"></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php break; ?>

            <?php case ('kutipan'): ?>
                <?php if($b->title): ?><h2 class="oa-block__title"><?php echo e($b->title); ?></h2><?php endif; ?>
                <blockquote class="oa-quote"><?php echo e(strip_tags($b->body)); ?></blockquote>
                <?php if($b->caption): ?><p class="oa-caption mt-2">— <?php echo e($b->caption); ?></p><?php endif; ?>
                <?php break; ?>

            <?php case ('koleksi'): ?>
                <?php echo $__env->make('web.partials.card-collection', ['b' => $b], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php break; ?>

            <?php case ('tombol'): ?>
                <div class="oa-cta <?php echo e($b->style === 'plain' ? 'oa-panel' : ''); ?>">
                    <div class="min-w-0">
                        <?php if($b->title): ?><h2 class="oa-block__title mb-1"><?php echo e($b->title); ?></h2><?php endif; ?>
                        <div class="prose"><?php echo $b->body; ?></div>
                    </div>
                    <a href="<?php echo e($b->button_url); ?>" class="btn <?php echo e($b->style === 'accent' ? 'btn-accent' : 'btn-primary'); ?> flex-none"><?php echo e($b->button_label); ?> <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <?php break; ?>
        <?php endswitch; ?>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/web/partials/block.blade.php ENDPATH**/ ?>