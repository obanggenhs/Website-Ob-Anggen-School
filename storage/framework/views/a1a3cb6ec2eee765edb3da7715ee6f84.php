
<?php
    $extra = $extra ?? false;
    $blockTypes = $menu->isPage() ? \App\Models\Block::TYPES : array_diff_key(\App\Models\Block::TYPES, ['koleksi' => true]);
?>
<div class="card dash-card mb-4" <?php if($extra): ?> id="isi-tambahan" <?php endif; ?>>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title"><?php echo e($blocksTitle ?? 'Isi halaman'); ?></h2>
            <small class="text-muted-d">
                <?php if($extra): ?>
                    Teks, foto, video, YouTube, atau PDF yang tampil bersama daftar <?php echo e(\Illuminate\Support\Str::lower($menu->type_label)); ?>. Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan.
                <?php else: ?>
                    Blok tampil berurutan dari atas ke bawah. Seret <i class="bi bi-grip-vertical"></i> untuk mengubah urutan.
                <?php endif; ?>
            </small>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <?php if($extra): ?>
                <form method="POST" action="<?php echo e(route('dashboard.studio.blocks-place', $menu)); ?>" class="d-flex align-items-center gap-2">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <label for="blocks_at" class="small text-muted-d text-nowrap">Letak</label>
                    <select name="blocks_at" id="blocks_at" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="atas" <?php if($menu->blocksAt() === 'atas'): echo 'selected'; endif; ?>>Di atas daftar</option>
                        <option value="bawah" <?php if($menu->blocksAt() === 'bawah'): echo 'selected'; endif; ?>>Di bawah daftar</option>
                    </select>
                </form>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-plus-lg"></i> Tambah blok</button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 block-type-menu">
                    <?php $__currentLoopData = $blockTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a class="dropdown-item d-flex gap-2 py-2" href="<?php echo e(route('dashboard.blocks.create', [$menu, 'jenis' => $key])); ?>">
                                <i class="bi <?php echo e($t['icon']); ?> text-primary fs-5"></i>
                                <span><strong class="d-block small"><?php echo e($t['label']); ?></strong><small class="text-muted-d"><?php echo e($t['desc']); ?></small></span>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if($menu->blocks->isEmpty()): ?>
            <?php echo $__env->make('dashboard.partials.empty', [
                'icon' => 'bi-bricks',
                'text' => $extra
                    ? 'Belum ada isi tambahan. Bila perlu, tambahkan blok seperti Dokumen / PDF, Video, atau Teks.'
                    : 'Halaman ini belum punya isi. Mulai dengan menambah blok Teks, Media + Teks, atau Dokumen / PDF.',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div id="blockList" data-reorder-url="<?php echo e(route('dashboard.blocks.reorder', $menu)); ?>">
                <?php $__currentLoopData = $menu->blocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="block-item <?php echo e($block->is_active ? '' : 'is-hidden'); ?>" data-id="<?php echo e($block->id); ?>" data-anchor="blok-<?php echo e($block->id); ?>">
                        <span class="tree-handle"><i class="bi bi-grip-vertical"></i></span>
                        <span class="block-item__icon"><i class="bi <?php echo e($block->type_icon); ?>"></i></span>
                        <div class="min-w-0 flex-fill">
                            <div class="block-item__title">
                                <?php echo e($block->title ?: $block->type_label); ?>

                                <span class="badge badge-soft ms-1"><?php echo e($block->type_label); ?></span>
                                <span class="badge text-bg-light border ms-1" title="Lebar & posisi blok"><i class="bi <?php echo e(\App\Models\Block::PLACEMENTS[$block->placementKey()]['icon']); ?> me-1"></i><?php echo e($block->layoutLabel()); ?></span>
                                <?php if($block->pairingKey() !== 'auto' && ! $loop->first): ?>
                                    <span class="badge text-bg-light border ms-1" title="<?php echo e(\App\Models\Block::PAIRINGS[$block->pairingKey()]['desc']); ?>"><i class="bi <?php echo e(\App\Models\Block::PAIRINGS[$block->pairingKey()]['icon']); ?> me-1"></i><?php echo e(\App\Models\Block::PAIRINGS[$block->pairingKey()]['label']); ?> blok sebelumnya</span>
                                <?php endif; ?>
                                <?php if($block->contentAlign() !== 'left'): ?>
                                    <span class="badge text-bg-light border ms-1" title="Perataan isi"><i class="bi <?php echo e(\App\Models\Block::ALIGNS[$block->contentAlign()]['icon']); ?>"></i></span>
                                <?php endif; ?>
                                <?php if (! ($block->is_active)): ?><span class="badge text-bg-secondary ms-1">Disembunyikan</span><?php endif; ?>
                            </div>
                            <div class="block-item__preview"><?php echo e($block->preview ?: '—'); ?></div>
                        </div>
                        <div class="dash-actions">
                            <?php if($block->type === 'koleksi'): ?>
                                <a href="<?php echo e(route('dashboard.cards.index', [$menu, $block])); ?>" class="btn btn-sm btn-primary" title="Tambah & ubah kartu di blok ini"><i class="bi bi-grid-3x3-gap"></i> Kelola kartu</a>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('dashboard.blocks.move', [$menu, $block, 'naik'])); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-light btn-icon" title="Naikkan" <?php if($loop->first): echo 'disabled'; endif; ?>><i class="bi bi-arrow-up"></i></button></form>
                            <form method="POST" action="<?php echo e(route('dashboard.blocks.move', [$menu, $block, 'turun'])); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-light btn-icon" title="Turunkan" <?php if($loop->last): echo 'disabled'; endif; ?>><i class="bi bi-arrow-down"></i></button></form>
                            <form method="POST" action="<?php echo e(route('dashboard.blocks.toggle', [$menu, $block])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-light btn-icon" title="<?php echo e($block->is_active ? 'Sembunyikan' : 'Tampilkan'); ?>"><i class="bi <?php echo e($block->is_active ? 'bi-eye-slash' : 'bi-eye'); ?>"></i></button></form>
                            <a href="<?php echo e(route('dashboard.blocks.edit', [$menu, $block])); ?>" class="btn btn-sm btn-soft btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.blocks.destroy', [$menu, $block]), 'label' => 'blok '.$block->type_label], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH S:\oa-school\resources\views/dashboard/studio/_content-blocks.blade.php ENDPATH**/ ?>