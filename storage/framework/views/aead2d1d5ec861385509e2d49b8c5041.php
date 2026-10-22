<a href="<?php echo e(route('news.show', $post->slug)); ?>" class="oa-card">
    <div class="oa-card__img">
        <img src="<?php echo e($post->cover_url); ?>" alt="<?php echo e($post->title); ?>" loading="lazy">
        <?php if($post->is_featured): ?><span class="oa-card__badge oa-badge oa-badge--accent"><i class="bi bi-star-fill"></i> Penting</span><?php endif; ?>
    </div>
    <div class="oa-card__body">
        <span class="oa-card__meta">
            <span><i class="bi bi-calendar3"></i><?php echo e(tgl($post->published_at, 'd M Y')); ?></span>
            <?php if($post->category): ?><span><i class="bi bi-tag"></i><?php echo e($post->category->name); ?></span><?php endif; ?>
        </span>
        <h3 class="oa-card__title"><?php echo e($post->title); ?></h3>
        <p class="oa-card__text"><?php echo e($post->summary); ?></p>
        <span class="oa-card__foot oa-link-more">Baca berita <i class="bi bi-arrow-right"></i></span>
    </div>
</a>
<?php /**PATH S:\oa-school\resources\views/web/news/_card.blade.php ENDPATH**/ ?>