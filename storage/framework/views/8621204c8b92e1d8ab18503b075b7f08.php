
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <meta name="upload-url" content="<?php echo e(route('dashboard.upload.chunk')); ?>">
    <meta name="upload-chunk-size" content="<?php echo e(\App\Support\ChunkUpload::chunkSize()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | <?php echo e($theme['label']); ?> · <?php echo e(setting('site_name')); ?></title>
    <link rel="icon" href="<?php echo e(media_url(setting('favicon'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/fonts/lexend/lexend.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/fonts/nunito-sans/nunito-sans.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/summernote/summernote-bs5.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dashboard.css')); ?>">
    <style>:root { --dash-primary: <?php echo e($theme['color']); ?>; }</style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="dash-body theme-<?php echo e(auth()->user()->role); ?>">

    <?php echo $__env->make('dashboard.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="dash-overlay" data-sidebar-toggle></div>

    <div class="dash-main">
        <?php echo $__env->make('dashboard.partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="dash-content">
            <?php if (! empty(trim($__env->yieldContent('page_title')))): ?>
                <div class="dash-pagehead">
                    <div>
                        <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
                            <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-1"><?php echo $__env->yieldContent('breadcrumb'); ?></ol></nav>
                        <?php endif; ?>
                        <h1><?php echo $__env->yieldContent('page_title'); ?></h1>
                        <?php if (! empty(trim($__env->yieldContent('page_subtitle')))): ?><p><?php echo $__env->yieldContent('page_subtitle'); ?></p><?php endif; ?>
                    </div>
                    <div class="d-flex flex-wrap gap-2"><?php echo $__env->yieldContent('actions'); ?></div>
                </div>
            <?php endif; ?>

            <?php echo $__env->make('dashboard.partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="dash-footer">
            <span>&copy; <?php echo e(date('Y')); ?> <?php echo e(setting('site_name')); ?></span>
            <span><?php echo e($theme['label']); ?> · <?php echo e($theme['panel']); ?></span>
        </footer>
    </div>

    
    <?php echo $__env->yieldPushContent('modals'); ?>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="dashFlash"></div>

    <script src="<?php echo e(asset('assets/vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/summernote/summernote-bs5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/summernote/summernote-id-ID.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/sortablejs/Sortable.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dashboard.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH S:\oa-school\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>