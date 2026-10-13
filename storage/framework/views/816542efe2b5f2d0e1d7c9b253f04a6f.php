
<?php if(! empty($url)): ?>
    <div class="oa-video <?php echo e($class ?? ''); ?>" data-hover-video>
        <span class="oa-video__hint"><i class="bi bi-cursor-fill"></i> Arahkan kursor untuk memutar</span>
        <div class="ratio ratio-16x9">
            <?php if(is_video_file($url)): ?>
                <video src="<?php echo e($url); ?>" muted loop playsinline controls preload="metadata"></video>
            <?php else: ?>
                <iframe src="<?php echo e(youtube_embed($url)); ?>" title="<?php echo e($title ?? 'Video'); ?>" loading="lazy"
                        allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?><?php /**PATH S:\oa-school\resources\views/web/partials/video.blade.php ENDPATH**/ ?>