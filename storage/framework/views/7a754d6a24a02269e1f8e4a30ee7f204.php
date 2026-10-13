
<?php
    $shape = $shape ?? 'landscape';
    $imgClass = ['square' => 'oa-card__img--square', 'portrait' => 'oa-card__img--portrait'][$shape] ?? '';
?>
<a href="<?php echo e($url); ?>" class="oa-card oa-collection-card <?php echo e($shape === 'none' ? 'oa-card--noimg' : ''); ?>">
    <?php if($shape !== 'none'): ?>
        <div class="oa-card__img <?php echo e($imgClass); ?>">
            <img src="<?php echo e($card->image_url); ?>" alt="<?php echo e($card->title); ?>" loading="lazy">
            <?php if($card->category): ?><span class="oa-card__badge oa-badge"><?php echo e($card->category); ?></span><?php endif; ?>
            <?php if($card->document || $card->video_url || $card->video_file): ?>
                <span class="oa-card__mediatags">
                    <?php if($card->document): ?><span title="Ada dokumen"><i class="bi <?php echo e(\App\Support\Media::docType($card->document)['icon']); ?>"></i></span><?php endif; ?>
                    <?php if($card->video_url || $card->video_file): ?><span title="Ada video"><i class="bi bi-play-fill"></i></span><?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="oa-card__body">
        <?php if($shape === 'none' && $card->category): ?><span class="oa-badge align-self-start"><?php echo e($card->category); ?></span><?php endif; ?>
        <h3 class="oa-card__title"><?php echo e($card->title); ?></h3>
        <?php if($card->subtitle || $card->year): ?>
            <span class="oa-card__meta">
                <?php if($card->subtitle): ?><span><i class="bi bi-person-badge"></i><?php echo e($card->subtitle); ?></span><?php endif; ?>
                <?php if($card->year): ?><span><i class="bi bi-calendar3"></i><?php echo e($card->year); ?></span><?php endif; ?>
            </span>
        <?php endif; ?>
        <?php if(($excerpt ?? true) && $card->summary): ?>
            <p class="oa-card__text"><?php echo e($card->summary); ?></p>
        <?php endif; ?>
        <span class="oa-card__foot oa-link-more"><?php echo e($button ?? 'Lihat detail'); ?> <i class="bi bi-arrow-right"></i></span>
    </div>
</a>
<?php /**PATH S:\oa-school\resources\views/web/partials/collection-card.blade.php ENDPATH**/ ?>