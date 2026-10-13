
<?php
    $isHome = $menu->type === 'beranda';
    $current = old('banner_type', $menu->banner_type ?: 'image');
    $slides = array_values((array) $menu->banner_slides);
    $ytId = youtube_id(old('banner_youtube', $menu->banner_youtube));
    $active = $menu->headerMedia();
    $defaultImage = media_url($isHome ? setting('hero_image') : setting('page_header_image'));

    // Lapisan warna: "ikut" = mengikuti pengaturan semua halaman
    $overlays = \App\Models\Menu::OVERLAYS;
    $globalOverlay = \App\Models\Menu::defaultOverlay();
    $overlayValue = old('banner_overlay', $menu->banner_overlay ?: 'ikut');
    $previewImage = $active['poster'] ?? $defaultImage;
?>

<div class="card dash-card mb-4" id="header-halaman">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title"><?php echo e($isHome ? 'Latar belakang hero Beranda' : 'Header halaman & teks pembuka'); ?></h2>
            <small class="text-muted-d">
                <?php echo e($isHome ? 'Bagian paling atas Beranda di website.' : 'Tampil di bagian atas halaman "'.$menu->title.'" di website.'); ?>

            </small>
        </div>
        <span class="badge badge-soft">
            <i class="bi <?php echo e($active ? \App\Models\Menu::BANNER_TYPES[$active['type']]['icon'] : 'bi-image'); ?>"></i>
            Sedang tampil: <?php echo e($active ? \App\Models\Menu::BANNER_TYPES[$active['type']]['label'] : 'Gambar bawaan'); ?>

        </span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('dashboard.studio.content', $menu)); ?>" enctype="multipart/form-data" data-media-form>
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <?php if (! ($isHome)): ?>
                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'subtitle','label' => 'Sub judul di bawah nama halaman','value' => $menu->subtitle,'placeholder' => 'mis. Kabar terbaru dari kampus Ob Anggen']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'subtitle','label' => 'Sub judul di bawah nama halaman','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->subtitle),'placeholder' => 'mis. Kabar terbaru dari kampus Ob Anggen']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                <?php endif; ?>

                <div class="col-12">
                    <label class="form-label">Latar header</label>

                    
                    <div class="media-switch mb-3" role="radiogroup" aria-label="Jenis latar header">
                        <?php $__currentLoopData = \App\Models\Menu::BANNER_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <input type="radio" class="btn-check" name="banner_type" id="bt_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($current === $key): echo 'checked'; endif; ?>>
                            <label class="media-switch__item" for="bt_<?php echo e($key); ?>"><i class="bi <?php echo e($type['icon']); ?>"></i> <?php echo e($type['label']); ?></label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="media-panel" data-media-panel="image">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-5">
                                <div class="media-preview">
                                    <img src="<?php echo e($menu->banner ? media_url($menu->banner) : $defaultImage); ?>" alt="" id="bannerPreview">
                                    <?php if (! ($menu->banner)): ?><span class="media-preview__tag">Gambar bawaan</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label" for="f_banner">Unggah foto</label>
                                <input type="file" name="banner" id="f_banner" accept="image/*" class="form-control <?php $__errorArgs = ['banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-preview="bannerPreview">
                                <?php $__errorArgs = ['banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">JPG / PNG / WEBP, tanpa batas ukuran. Ukuran ideal 1600×500 px. Foto ini juga dipakai sebagai sampul sementara saat video sedang dimuat.</div>
                                <?php if($menu->banner): ?>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_banner" value="1" id="f_remove_banner">
                                        <label class="form-check-label small" for="f_remove_banner">Hapus foto ini (kembali ke gambar bawaan)</label>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="media-panel" data-media-panel="video">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-5">
                                <div class="media-preview">
                                    <video id="videoPreview" muted loop playsinline preload="metadata" <?php if($menu->banner_video): ?> src="<?php echo e(media_url($menu->banner_video)); ?>" <?php endif; ?>
                                           poster="<?php echo e($menu->banner ? media_url($menu->banner) : ''); ?>" class="<?php echo e($menu->banner_video ? '' : 'd-none'); ?>"></video>
                                    <div class="media-preview__empty <?php echo e($menu->banner_video ? 'd-none' : ''); ?>" data-video-empty><i class="bi bi-camera-video"></i>Belum ada video</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label" for="f_banner_video">Unggah video dari komputer</label>
                                <input type="file" name="banner_video" id="f_banner_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" class="form-control <?php $__errorArgs = ['banner_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-video-input="videoPreview">
                                <?php $__errorArgs = ['banner_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">MP4 / WEBM / MOV, tanpa batas ukuran. Video diputar otomatis tanpa suara dan berulang. Pilih video pendek (10–30 detik) agar halaman tetap ringan.</div>
                                <?php if($menu->banner_video): ?>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="f_remove_video">
                                        <label class="form-check-label small" for="f_remove_video">Hapus video ini</label>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="media-panel" data-media-panel="youtube">
                        <div class="row g-3 align-items-start">
                            <div class="col-md-5">
                                <div class="media-preview">
                                    <img src="<?php echo e($ytId ? 'https://img.youtube.com/vi/'.$ytId.'/hqdefault.jpg' : ''); ?>" alt="" id="ytPreview" class="<?php echo e($ytId ? '' : 'd-none'); ?>">
                                    <span class="media-preview__play <?php echo e($ytId ? '' : 'd-none'); ?>" data-yt-play><i class="bi bi-youtube"></i></span>
                                    <div class="media-preview__empty <?php echo e($ytId ? 'd-none' : ''); ?>" data-yt-empty><i class="bi bi-youtube"></i>Tempel link YouTube</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label" for="f_banner_youtube">Link YouTube</label>
                                <input type="url" name="banner_youtube" id="f_banner_youtube" value="<?php echo e(old('banner_youtube', $menu->banner_youtube)); ?>"
                                       class="form-control <?php $__errorArgs = ['banner_youtube'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="https://www.youtube.com/watch?v=..." data-yt-input="ytPreview">
                                <?php $__errorArgs = ['banner_youtube'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Link biasa, youtu.be, atau Shorts. Video diputar otomatis tanpa suara dan berulang di latar header.</div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="media-panel" data-media-panel="slider">
                        <?php if($slides): ?>
                            <div class="photo-grid mb-3" style="grid-template-columns: repeat(auto-fill, minmax(110px, 1fr))">
                                <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="photo-grid__item" title="Centang untuk menghapus">
                                        <img src="<?php echo e(media_url($slide)); ?>" alt="">
                                        <span class="photo-grid__badge badge text-bg-dark"><?php echo e($i + 1); ?></span>
                                        <span class="photo-grid__tools"><input type="checkbox" class="form-check-input" name="remove_slides[]" value="<?php echo e($i); ?>"></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="form-text mb-2"><?php echo e(count($slides)); ?> slide tersimpan. Centang slide yang ingin dihapus.</div>
                        <?php endif; ?>
                        <label class="form-label" for="f_slides">Tambah foto slide (boleh pilih banyak sekaligus)</label>
                        <input type="file" name="slides[]" id="f_slides" accept="image/*" multiple class="form-control <?php $__errorArgs = ['slides'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['slides.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-slides-input="slidesNew">
                        <?php $__errorArgs = ['slides'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['slides.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Minimal 2 foto, tanpa batas jumlah dan ukuran. Foto bergantian otomatis setiap 5 detik.</div>
                        <div class="photo-grid mt-2" id="slidesNew" style="grid-template-columns: repeat(auto-fill, minmax(90px, 1fr))"></div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="overlay-picker" data-global="<?php echo e($globalOverlay); ?>">
                        <label class="form-label mb-0">Lapisan warna di atas latar</label>
                        <div class="form-text mt-0 mb-2">Atur seberapa gelap warna di atas foto/video. Tulisan selalu diberi bayangan halus sehingga tetap jelas dibaca di semua pilihan.</div>
                        <div class="row g-3 align-items-stretch">
                            <div class="col-lg-6">
                                <div class="overlay-options" role="radiogroup" aria-label="Lapisan warna di atas latar">
                                    <input type="radio" class="btn-check" name="banner_overlay" id="ov_ikut" value="ikut" <?php if($overlayValue === 'ikut'): echo 'checked'; endif; ?>>
                                    <label class="overlay-option" for="ov_ikut">
                                        <span class="overlay-option__swatch overlay-option__swatch--<?php echo e($globalOverlay); ?>" aria-hidden="true"></span>
                                        <span class="min-w-0"><strong>Ikuti semua halaman</strong><small>Saat ini: <?php echo e($overlays[$globalOverlay]['label']); ?></small></span>
                                    </label>
                                    <?php $__currentLoopData = $overlays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="banner_overlay" id="ov_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($overlayValue === $key): echo 'checked'; endif; ?>>
                                        <label class="overlay-option" for="ov_<?php echo e($key); ?>">
                                            <span class="overlay-option__swatch overlay-option__swatch--<?php echo e($key); ?>" aria-hidden="true"></span>
                                            <span class="min-w-0"><strong><?php echo e($o['label']); ?></strong><small><?php echo e($o['desc']); ?></small></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php $__errorArgs = ['banner_overlay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php if(auth()->user()->isMaster()): ?>
                                    <div class="form-check mt-2 overlay-all">
                                        <input class="form-check-input" type="checkbox" name="overlay_all" value="1" id="f_overlay_all">
                                        <label class="form-check-label small" for="f_overlay_all">
                                            Pakai pilihan ini untuk <strong>semua halaman</strong>, termasuk Beranda
                                            <span class="d-block text-muted-d">Pilihan yang sudah diatur sendiri di halaman lain ikut diganti.</span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-6">
                                <div class="overlay-preview <?php echo e($isHome ? 'overlay-preview--hero' : ''); ?>"
                                     style="--pv-img: url('<?php echo e($previewImage); ?>'); --pv-dark: <?php echo e(setting('color_primary_dark')); ?>; --pv-primary: <?php echo e(setting('color_primary')); ?>; --pv-accent: <?php echo e(setting('color_accent')); ?>">
                                    <span class="overlay-preview__tag">Pratinjau</span>
                                    <?php if($isHome): ?>
                                        <span class="overlay-preview__eyebrow"><?php echo e(setting('site_tagline')); ?></span>
                                        <strong class="overlay-preview__title"><?php echo e(setting('hero_title')); ?></strong>
                                        <span class="overlay-preview__sub"><?php echo e(\Illuminate\Support\Str::limit((string) setting('hero_subtitle'), 110)); ?></span>
                                    <?php else: ?>
                                        <span class="overlay-preview__crumb"><i class="bi bi-house-door"></i> Beranda<?php echo e($menu->parent ? ' / '.$menu->parent->title : ''); ?> / <?php echo e($menu->title); ?></span>
                                        <strong class="overlay-preview__title"><?php echo e($menu->title); ?></strong>
                                        <?php if($menu->subtitle): ?><span class="overlay-preview__sub"><?php echo e($menu->subtitle); ?></span><?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (! ($isHome)): ?>
                    <?php if (isset($component)) { $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.editor','data' => ['name' => 'intro','label' => 'Teks pembuka (opsional)','value' => $menu->intro,'height' => 140,'help' => $menu->isPage() ? 'Tampil sebelum blok pertama.' : 'Tampil di atas daftar '.\Illuminate\Support\Str::lower($menu->type_label).'.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'intro','label' => 'Teks pembuka (opsional)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->intro),'height' => 140,'help' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->isPage() ? 'Tampil sebelum blok pertama.' : 'Tampil di atas daftar '.\Illuminate\Support\Str::lower($menu->type_label).'.')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1)): ?>
<?php $attributes = $__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1; ?>
<?php unset($__attributesOriginal65f5d4c5080b482f0bd4438c65c7fdc1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1)): ?>
<?php $component = $__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1; ?>
<?php unset($__componentOriginal65f5d4c5080b482f0bd4438c65c7fdc1); ?>
<?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top pt-3 mt-3">
                <a href="<?php echo e($menu->link); ?>" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat hasilnya di website</a>
                <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> <?php echo e($isHome ? 'Simpan latar hero' : 'Simpan header'); ?></button>
            </div>
        </form>
    </div>
</div><?php /**PATH S:\oa-school\resources\views/dashboard/studio/_content-header.blade.php ENDPATH**/ ?>