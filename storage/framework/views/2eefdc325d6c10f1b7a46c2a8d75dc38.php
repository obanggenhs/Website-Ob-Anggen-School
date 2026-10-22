<?php $__env->startSection('title', $post->title); ?>
<?php $__env->startSection('description', $post->summary); ?>
<?php $__env->startSection('page_title', $post->title); ?>
<?php $__env->startSection('page_banner', $post->cover_url); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('news.index', $post->channel === 'pendaftaran' ? ['kanal' => 'pendaftaran'] : [])); ?>"><?php echo e($menu?->title ?? 'Berita'); ?></a></li>
    <li class="breadcrumb-item active">Detail</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('no_intro', true); ?>

<?php $__env->startSection('page'); ?>
    <article class="oa-panel">
        <div class="oa-card__meta mb-3">
            <span><i class="bi bi-calendar3"></i><?php echo e(tgl($post->published_at, 'l, d F Y')); ?></span>
            <?php if($post->category): ?><span><i class="bi bi-tag"></i><?php echo e($post->category->name); ?></span><?php endif; ?>
            <?php if($post->author): ?><span><i class="bi bi-person"></i><?php echo e($post->author->name); ?></span><?php endif; ?>
            <span><i class="bi bi-clock"></i><?php echo e($post->reading_time); ?> menit baca</span>
            <span><i class="bi bi-eye"></i><?php echo e(number_format($post->views, 0, ',', '.')); ?>x dibaca</span>
        </div>
        <?php if($post->cover): ?>
            <a href="<?php echo e($post->cover_url); ?>" class="glightbox"><img src="<?php echo e($post->cover_url); ?>" alt="<?php echo e($post->title); ?>" class="oa-cover mb-3"></a>
        <?php endif; ?>
        <div class="prose"><?php echo $post->body; ?></div>

        
        <?php if(\App\Support\PageOptions::for($menu, 'berita')['share']): ?>
            <hr class="my-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <strong class="small me-1">Bagikan:</strong>
                <a class="btn btn-sm btn-outline-primary" href="https://wa.me/?text=<?php echo e(rawurlencode($post->title.' '.url()->current())); ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                <a class="btn btn-sm btn-outline-primary" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(url()->current())); ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i> Facebook</a>
                <button type="button" class="btn btn-sm btn-outline-primary" data-copy="<?php echo e(url()->current()); ?>"><i class="bi bi-link-45deg"></i> Salin link</button>
            </div>
        <?php endif; ?>
    </article>

    <?php if($related->isNotEmpty()): ?>
        <div class="oa-sechead mt-4"><h2>Berita lainnya</h2></div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col"><?php echo $__env->make('web.news._card', ['post' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/news/show.blade.php ENDPATH**/ ?>