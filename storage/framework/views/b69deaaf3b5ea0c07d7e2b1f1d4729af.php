<?php $__env->startSection('title', 'Beranda'); ?>

<?php
    $quickColors = ['#7A2B25', '#2F5D50', '#B86B12', '#3b6fd8', '#6a5acd', '#d64545', '#0f766e', '#8a4fb8', '#2f9e6a', '#475569', '#c2410c', '#1F4E79'];
    $blocksTop = setting('home_blocks_position') === 'top';
    $showVideo = setting('home_show_video') && setting('video_url');

        // Tombol hero "Daftar sekarang" ikut status menu/jalur Pendaftaran (dihitung di HomeController)
    $showHeroButton = setting('hero_button') && ($registrationOpen ?? true);

    // Latar hero diatur di Studio > Beranda > "Latar belakang hero Beranda" (foto / video / YouTube / slide)
    $heroMedia = $home?->headerMedia() ?? ['type' => 'image', 'src' => media_url(setting('hero_image')), 'poster' => media_url(setting('hero_image'))];

    // Lapisan warna di atas latar hero (tipis / sedang / pekat), diatur di Studio › Beranda
    $heroOverlay = $home?->headerOverlay() ?? \App\Models\Menu::defaultOverlay();
?>

<?php $__env->startSection('content'); ?>
    
    <section class="oa-hero oa-overlay--<?php echo e($heroOverlay); ?> <?php echo e($heroMedia['type'] !== 'image' ? 'has-media' : ''); ?>" style="--oa-hero-img: url('<?php echo e($heroMedia['poster'] ?? media_url(setting('hero_image'))); ?>')">
        <?php echo $__env->make('web.partials.header-media', ['media' => $heroMedia], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="container oa-container">
            <span class="oa-hero__eyebrow"><i class="bi bi-geo-alt-fill"></i> <?php echo e(setting('site_tagline')); ?></span>
            <h1 class="oa-hero__title"><?php echo e(setting('hero_title')); ?></h1>
            <p class="oa-hero__text"><?php echo e(setting('hero_subtitle')); ?></p>
            <div class="d-flex flex-wrap gap-2">
                <?php if($showHeroButton): ?>
                    <a href="<?php echo e(url(setting('hero_button_url', '/'))); ?>" class="btn btn-accent btn-lg"><?php echo e(setting('hero_button')); ?></a>
                <?php endif; ?>
                <?php if($showVideo): ?>
                    <a href="#video-profil" class="btn btn-ghost-light btn-lg"><i class="bi bi-play-circle me-1"></i> Tonton video</a>
                <?php endif; ?>
            </div>
            <div class="oa-hero__stats">
                <div><strong data-count="<?php echo e((int) setting('stat_campus')); ?>"><?php echo e(setting('stat_campus')); ?></strong><span>Kampus</span></div>
                <div><strong data-count="<?php echo e((int) setting('stat_students')); ?>"><?php echo e(setting('stat_students')); ?></strong><span>Siswa</span></div>
                <div><strong data-count="<?php echo e((int) setting('stat_teachers')); ?>"><?php echo e(setting('stat_teachers')); ?></strong><span>Guru &amp; staf</span></div>
            </div>
        </div>
        <?php echo $__env->make('web.partials.ridge', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    
    <?php if($shortcuts->isNotEmpty()): ?>
        <div class="container oa-container oa-quicklinks">
            <div class="row g-2 g-md-3">
                <?php $__currentLoopData = $shortcuts->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-lg-3">
                        <a href="<?php echo e($item->link); ?>" class="oa-quicklink" target="<?php echo e($item->target); ?>">
                            <span class="oa-quicklink__icon"><i class="bi <?php echo e($item->type_icon); ?>"></i></span>
                            <span class="min-w-0">
                                <strong><?php echo e($item->title); ?></strong>
                                <small class="d-none d-sm-block text-truncate"><?php echo e($item->subtitle ?: $item->type_label); ?></small>
                            </span>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($blocksTop && $blocks->isNotEmpty()): ?>
        <section class="oa-section">
            <div class="container oa-container">
                <div class="row oa-blocks">
                    <?php echo $__env->make('web.partials.blocks', ['list' => $blocks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <section class="oa-section">
        <div class="container oa-container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="oa-sechead"><h2><?php echo e(setting('welcome_title')); ?></h2></div>
                    <div class="prose mb-3"><?php echo setting('welcome_text'); ?></div>
                    <?php if(setting('welcome_link')): ?>
                        <a href="<?php echo e(url(setting('welcome_link'))); ?>" class="btn btn-primary">Baca selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
                    <?php endif; ?>
                </div>
                <div class="col-lg-5">
                    <div class="oa-welcome__img" data-reveal="right">
                        <img src="<?php echo e(media_url(setting('welcome_image'))); ?>" alt="<?php echo e(setting('welcome_title')); ?>" loading="lazy">
                        <div class="oa-welcome__badge"><strong><?php echo e(setting('stat_campus')); ?> kampus</strong><small>di Papua Pegunungan</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <?php if($branches->isNotEmpty()): ?>
        <section class="oa-section oa-section--surface">
            <div class="container oa-container">
                <div class="oa-sechead">
                    <div><h2>Kampus Ob Anggen</h2><p>Melayani anak-anak di kampung-kampung pegunungan Papua.</p></div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <a href="<?php echo e(\App\Models\Menu::moduleEnabled('fasilitas') ? route('facilities.index', ['jenis' => 'gedung', 'kampus' => $branch->id]) : '#'); ?>" class="oa-card">
                                <div class="oa-card__img"><img src="<?php echo e(media_url($branch->image)); ?>" alt="<?php echo e($branch->name); ?>" loading="lazy"></div>
                                <div class="oa-card__body">
                                    <h3 class="oa-card__title"><?php echo e($branch->name); ?></h3>
                                    <span class="oa-card__meta"><span><i class="bi bi-geo-alt"></i><?php echo e($branch->regency); ?></span></span>
                                    <p class="oa-card__text"><?php echo e($branch->description); ?></p>
                                    <span class="oa-card__foot oa-link-more">Lihat fasilitas <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($agenda->isNotEmpty() || (setting('home_show_quick') && $shortcuts->isNotEmpty())): ?>
        <section class="oa-section">
            <div class="container oa-container">
                <div class="row g-4">
                    <?php if($agenda->isNotEmpty()): ?>
                        <div class="<?php echo e(setting('home_show_quick') ? 'col-lg-7' : 'col-12'); ?>">
                            <div class="oa-panel h-100">
                                <div class="oa-sechead"><h2>Agenda mendatang</h2>
                                    <a href="<?php echo e(route('calendar.index')); ?>" class="oa-link-more">Kalender lengkap <i class="bi bi-arrow-right"></i></a>
                                </div>
                                <ul class="oa-agenda">
                                    <?php $__currentLoopData = $agenda->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li style="--cat: <?php echo e($event->category_color); ?>">
                                            <span class="oa-date"><strong><?php echo e($event->start_date->format('d')); ?></strong><small><?php echo e(tgl($event->start_date, 'M')); ?></small></span>
                                            <span class="min-w-0 flex-fill">
                                                <span class="oa-agenda__title d-block text-truncate"><?php echo e($event->title); ?></span>
                                                <span class="oa-agenda__meta d-block text-truncate"><?php echo e($event->level_label); ?> · <?php echo e($event->date_label); ?></span>
                                            </span>
                                            <span class="oa-when <?php echo e($event->isOngoing() ? 'is-now' : ''); ?>"><?php echo e($event->relative_label); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(setting('home_show_quick') && $shortcuts->isNotEmpty()): ?>
                        <div class="<?php echo e($agenda->isNotEmpty() ? 'col-lg-5' : 'col-12'); ?>">
                            <div class="oa-sechead"><h2>Akses cepat</h2></div>
                            <div class="oa-tiles">
                                <?php $__currentLoopData = $shortcuts->take(9); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($item->link); ?>" class="oa-tile" style="--tile: <?php echo e($quickColors[$i % count($quickColors)]); ?>"><i class="bi <?php echo e($item->type_icon); ?>"></i><span><?php echo e($item->title); ?></span></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($news->isNotEmpty()): ?>
        <section class="oa-section pt-0">
            <div class="container oa-container">
                <div class="oa-sechead">
                    <div><h2>Berita terbaru</h2><p>Kabar dan kegiatan terbaru dari ketiga kampus.</p></div>
                    <a href="<?php echo e(route('news.index')); ?>" class="oa-link-more">Semua berita <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                    <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col"><?php echo $__env->make('web.news._card', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($showVideo): ?>
        <section class="oa-section pt-0" id="video-profil">
            <div class="container oa-container">
                <div class="oa-videofeature">
                    <div>
                        <span class="oa-hero__eyebrow mb-1"><i class="bi bi-camera-reels"></i> Video profil</span>
                        <h2><?php echo e(setting('video_title')); ?></h2>
                        <p><?php echo e(setting('video_text')); ?></p>
                        <small class="d-block" style="color: rgba(255,255,255,.7)"><i class="bi bi-cursor"></i> Arahkan kursor ke video untuk memutar tanpa suara. Klik ikon speaker di video untuk menyalakan suara.</small>
                    </div>
                    <?php echo $__env->make('web.partials.video', ['url' => setting('video_url'), 'title' => setting('video_title')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if($testimonials->isNotEmpty()): ?>
        <section class="oa-section pt-0">
            <div class="container oa-container">
                <div class="oa-sechead oa-sechead--center"><h2>Apa kata mereka</h2><p>Cerita alumni dan orang tua tentang Ob Anggen.</p></div>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <?php $__currentLoopData = $testimonials->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <div class="oa-testi">
                                <p class="oa-testi__quote"><?php echo e(\Illuminate\Support\Str::limit($t->content, 200)); ?></p>
                                <div class="oa-testi__person">
                                    <img src="<?php echo e($t->photo_url); ?>" alt="<?php echo e($t->name); ?>" loading="lazy">
                                    <span><strong><?php echo e($t->name); ?></strong><small><?php echo e($t->role); ?></small></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(! $blocksTop && $blocks->isNotEmpty()): ?>
        <section class="oa-section pt-0">
            <div class="container oa-container">
                <div class="row oa-blocks">
                    <?php echo $__env->make('web.partials.blocks', ['list' => $blocks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/home.blade.php ENDPATH**/ ?>