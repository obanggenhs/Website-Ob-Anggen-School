<?php $__env->startSection('title', 'Galeri'); ?>
<?php $__env->startSection('page_title', 'Galeri'); ?>
<?php $__env->startSection('page_subtitle', 'Satu album berisi banyak foto kegiatan (dan video YouTube bila ada).'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.gallery.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Album baru</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <form class="dash-filter mb-3" method="GET">
        <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Semua kategori</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($cat->id); ?>" <?php if(request('kategori') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input type="search" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control form-control-sm" placeholder="Cari album...">
        <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
    </form>

    <?php if($albums->isEmpty()): ?>
        <div class="card dash-card"><?php echo $__env->make('dashboard.partials.empty', ['icon' => 'bi-images', 'text' => 'Belum ada album.', 'action' => route('dashboard.gallery.create'), 'actionLabel' => 'Buat album'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div class="card dash-card h-100 overflow-hidden <?php echo e($album->is_active ? '' : 'opacity-75'); ?>">
                        <a href="<?php echo e(route('dashboard.gallery.edit', $album)); ?>"><img src="<?php echo e($album->cover_url); ?>" alt="" class="w-100" style="aspect-ratio:16/10;object-fit:cover"></a>
                        <div class="card-body">
                            <h3 class="h6 mb-1 text-truncate"><?php echo e($album->title); ?></h3>
                            <div class="small text-muted-d mb-2"><?php echo e($album->photos_count); ?> foto · <?php echo e($album->event_date ? tgl($album->event_date, 'd M Y') : '-'); ?></div>
                            <div class="d-flex gap-1 flex-wrap">
                                <?php if($album->category): ?><span class="badge badge-soft"><?php echo e($album->category->name); ?></span><?php endif; ?>
                                <?php if($album->video_url): ?><span class="badge text-bg-dark"><i class="bi bi-play-fill"></i> Video</span><?php endif; ?>
                                <?php if (! ($album->is_active)): ?><span class="badge text-bg-secondary">Disembunyikan</span><?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="<?php echo e(route('gallery.show', $album->slug)); ?>" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> Lihat</a>
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('dashboard.gallery.edit', $album)); ?>" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Kelola</a>
                                <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.gallery.destroy', $album), 'label' => 'album "'.$album->title.'" beserta semua fotonya'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-3"><?php echo e($albums->links()); ?></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/gallery/index.blade.php ENDPATH**/ ?>