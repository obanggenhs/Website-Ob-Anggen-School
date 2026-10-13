<?php
    $menu = $menu ?? null;
    $override = trim(View::getSection('page_banner') ?? '');

    // Halaman detail (mis. berita, album) memakai fotonya sendiri; halaman lain memakai latar header menu
    $media = $override !== '' ? ['type' => 'image', 'src' => $override, 'poster' => $override] : $menu?->headerMedia();
    $banner = $media['poster'] ?? media_url(setting('page_header_image'));
    $subtitle = trim(View::getSection('page_subtitle') ?? '') ?: $menu?->subtitle;

    // Lapisan warna di atas latar (tipis / sedang / pekat), diatur di Studio per halaman atau untuk semua halaman
    $overlay = $menu?->headerOverlay() ?? \App\Models\Menu::defaultOverlay();
?>
<header class="oa-pagehead oa-overlay--<?php echo e($overlay); ?> <?php echo e($media && $media['type'] !== 'image' ? 'has-media' : ''); ?>" style="--oa-pagehead-img: url('<?php echo e($banner); ?>')">
    <?php echo $__env->make('web.partials.header-media', ['media' => $media], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container oa-container position-relative" style="z-index:3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb oa-breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><i class="bi bi-house-door"></i> Beranda</a></li>
                <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                <?php else: ?>
                    <?php if($menu?->parent): ?>
                        <li class="breadcrumb-item"><?php echo e($menu->parent->title); ?></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $__env->yieldContent('page_title', $menu?->title); ?></li>
                <?php endif; ?>
            </ol>
        </nav>
        <h1 class="oa-pagehead__title"><?php echo $__env->yieldContent('page_title', $menu?->title); ?></h1>
        <?php if($subtitle): ?>
            <p class="oa-pagehead__sub"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
        <?php if($menu && ! $menu->isVisible() && auth()->check()): ?>
            <span class="oa-preview-flag"><i class="bi bi-eye-slash"></i> Pratinjau admin: menu ini sedang disembunyikan</span>
        <?php endif; ?>
    </div>
    <?php echo $__env->make('web.partials.ridge', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</header><?php /**PATH S:\oa-school\resources\views/web/partials/page-header.blade.php ENDPATH**/ ?>