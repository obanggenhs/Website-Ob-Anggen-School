<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php if (! empty(trim($__env->yieldContent('title')))): ?><?php echo $__env->yieldContent('title'); ?> | <?php endif; ?><?php echo e(setting('site_name')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', setting('meta_description')); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', setting('site_name')); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('description', setting('meta_description')); ?>">
    <link rel="icon" href="<?php echo e(media_url(setting('favicon'))); ?>">
    <script>
        // Tandai bahwa konten akan dianimasikan; cadangan 2,5 detik bila skrip gagal dimuat
        document.documentElement.classList.add('oa-anim-pending');
        setTimeout(function () { document.documentElement.classList.remove('oa-anim-pending'); }, 2500);
    </script>

    <?php
        $fontHeading = setting('font_heading');
        $fontBody = setting('font_body');
        $localFonts = ['Lexend' => 'lexend/lexend.css', 'Nunito Sans' => 'nunito-sans/nunito-sans.css'];
    ?>
    <?php $__currentLoopData = array_unique([$fontHeading, $fontBody]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($localFonts[$font])): ?>
            <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/fonts/'.$localFonts[$font])); ?>">
        <?php else: ?>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=<?php echo e(urlencode($font)); ?>:wght@400;600;700&display=swap">
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/glightbox/glightbox.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <style>
        :root {
            --oa-primary: <?php echo e(setting('color_primary')); ?>;
            --oa-primary-dark: <?php echo e(setting('color_primary_dark')); ?>;
            --oa-accent: <?php echo e(setting('color_accent')); ?>;
            --oa-secondary: <?php echo e(setting('color_secondary')); ?>;
            --oa-bg: <?php echo e(setting('color_bg')); ?>;
            --oa-footer: <?php echo e(setting('color_footer')); ?>;
            --oa-font-heading: '<?php echo e($fontHeading); ?>', system-ui, sans-serif;
            --oa-font-body: '<?php echo e($fontBody); ?>', system-ui, sans-serif;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="oa-body <?php echo $__env->yieldContent('body_class'); ?>">

    <?php echo $__env->make('web.partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main id="main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('web.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <a href="#" class="oa-backtop" id="oaBackTop" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></a>

    <?php if(session('success') || session('error')): ?>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast align-items-center border-0 text-bg-<?php echo e(session('success') ? 'success' : 'danger'); ?>" role="alert" data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body"><?php echo e(session('success') ?? session('error')); ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?php echo e(asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/glightbox/glightbox.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH S:\oa-school\resources\views/layouts/web.blade.php ENDPATH**/ ?>