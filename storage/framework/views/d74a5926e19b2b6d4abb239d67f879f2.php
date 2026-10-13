
<?php
    $current = old('login_bg_type', setting('login_bg_type') ?: 'image');
    $overlay = old('login_overlay', setting('login_overlay') ?: 'sedang');
    $image = setting('login_bg_image');
    $video = setting('login_bg_video');
    $slides = \App\Support\LoginBackground::slides();
    $active = \App\Support\LoginBackground::media();
?>

<form method="POST" action="<?php echo e(route('dashboard.settings.update', 'login')); ?>" enctype="multipart/form-data" class="card dash-card" data-media-form>
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2><i class="bi <?php echo e($tabInfo['icon']); ?> me-1"></i> <?php echo e($tabInfo['label']); ?></h2>
            <small class="text-muted-d">Latar belakang di sisi kiri halaman masuk dashboard (/login).</small>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge badge-soft"><i class="bi <?php echo e(\App\Support\LoginBackground::TYPES[$active['type']]['icon']); ?>"></i> Sedang tampil: <?php echo e(\App\Support\LoginBackground::TYPES[$active['type']]['label']); ?></span>
            <a href="<?php echo e(route('dashboard.settings.login-preview')); ?>" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-eye"></i> Lihat pratinjau</a>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-4">
            
            <div class="col-xl-7">
                <label class="form-label">Jenis latar</label>
                <div class="media-switch media-switch--3 mb-3" role="radiogroup" aria-label="Jenis latar halaman login">
                    <?php $__currentLoopData = \App\Support\LoginBackground::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="radio" class="btn-check" name="login_bg_type" id="lb_<?php echo e($key); ?>" value="<?php echo e($key); ?>" data-media-type <?php if($current === $key): echo 'checked'; endif; ?>>
                        <label class="media-switch__item" for="lb_<?php echo e($key); ?>"><i class="bi <?php echo e($type['icon']); ?>"></i> <?php echo e($type['label']); ?></label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="media-panel" data-media-panel="image">
                    <div class="media-preview mb-2">
                        <img src="<?php echo e($image ? media_url($image) : \App\Support\LoginBackground::fallbackImage()); ?>" alt="" id="loginImagePreview">
                        <?php if (! ($image)): ?><span class="media-preview__tag">Foto bawaan (latar Beranda)</span><?php endif; ?>
                    </div>
                    <label class="form-label" for="f_login_bg_image">Unggah foto</label>
                    <input type="file" name="login_bg_image" id="f_login_bg_image" accept="image/*" class="form-control <?php $__errorArgs = ['login_bg_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-preview="loginImagePreview">
                    <?php $__errorArgs = ['login_bg_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">JPG / PNG / WEBP, tanpa batas ukuran. Disarankan foto tegak atau persegi (mis. 1200×1400 px). Foto ini juga dipakai sebagai sampul sementara saat video sedang dimuat.</div>
                    <?php if($image): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="f_remove_image">
                            <label class="form-check-label small" for="f_remove_image">Hapus foto ini (kembali ke foto bawaan)</label>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="media-panel" data-media-panel="slider">
                    <?php if($slides): ?>
                        <div class="photo-grid mb-2" style="grid-template-columns: repeat(auto-fill, minmax(100px, 1fr))">
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
                    <label class="form-label" for="f_login_slides">Tambah foto slide (boleh pilih banyak sekaligus)</label>
                    <input type="file" name="slides[]" id="f_login_slides" accept="image/*" multiple class="form-control <?php $__errorArgs = ['slides'];
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
unset($__errorArgs, $__bag); ?>" data-slides-input="loginSlidesNew">
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
                    <div class="photo-grid mt-2" id="loginSlidesNew" style="grid-template-columns: repeat(auto-fill, minmax(84px, 1fr))"></div>
                </div>

                
                <div class="media-panel" data-media-panel="video">
                    <div class="media-preview mb-2">
                        <video id="loginVideoPreview" muted loop playsinline preload="metadata" <?php if($video): ?> src="<?php echo e(media_url($video)); ?>" <?php endif; ?>
                               poster="<?php echo e($image ? media_url($image) : ''); ?>" class="<?php echo e($video ? '' : 'd-none'); ?>"></video>
                        <div class="media-preview__empty <?php echo e($video ? 'd-none' : ''); ?>" data-video-empty><i class="bi bi-camera-video"></i>Belum ada video</div>
                    </div>
                    <label class="form-label" for="f_login_bg_video">Unggah video dari komputer</label>
                    <input type="file" name="login_bg_video" id="f_login_bg_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.m4v,.webm,.ogv,.ogg,.mov" class="form-control <?php $__errorArgs = ['login_bg_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-video-input="loginVideoPreview">
                    <?php $__errorArgs = ['login_bg_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">MP4 / WEBM / MOV, tanpa batas ukuran. Diputar otomatis tanpa suara dan berulang. Pilih video pendek (10–30 detik) agar halaman login tetap ringan.</div>
                    <?php if($video): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="f_remove_video">
                            <label class="form-check-label small" for="f_remove_video">Hapus video ini</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="col-xl-5">
                <label class="form-label">Gambaran halaman login saat ini</label>
                <div class="login-mock login-mock--<?php echo e($active['overlay']); ?>" data-login-mock>
                    <div class="login-mock__visual" style="background-image: url('<?php echo e($active['poster']); ?>')">
                        <?php if($active['type'] === 'video'): ?>
                            <video src="<?php echo e($active['src']); ?>" poster="<?php echo e($active['poster']); ?>" muted loop playsinline autoplay preload="metadata"></video>
                        <?php endif; ?>
                        <div class="login-mock__brand">
                            <img src="<?php echo e(media_url(setting('logo_main'))); ?>" alt="">
                            <span><?php echo e(setting('site_name')); ?></span>
                        </div>
                        <?php if($active['type'] === 'slider'): ?>
                            <span class="login-mock__tag"><i class="bi bi-collection-play"></i> <?php echo e(count($active['slides'])); ?> slide</span>
                        <?php elseif($active['type'] === 'video'): ?>
                            <span class="login-mock__tag"><i class="bi bi-play-fill"></i> Video</span>
                        <?php endif; ?>
                    </div>
                    <div class="login-mock__form">
                        <span class="login-mock__line login-mock__line--title"></span>
                        <span class="login-mock__line"></span>
                        <span class="login-mock__input"></span>
                        <span class="login-mock__input"></span>
                        <span class="login-mock__button"></span>
                    </div>
                </div>
                <div class="form-text mb-3">Gambaran ini menampilkan latar yang sudah tersimpan. Klik <strong>Lihat pratinjau</strong> untuk membuka halaman login sungguhan.</div>

                <label class="form-label" for="f_login_overlay">Lapisan warna di atas latar</label>
                <select name="login_overlay" id="f_login_overlay" class="form-select <?php $__errorArgs = ['login_overlay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-overlay-select>
                    <?php $__currentLoopData = \App\Support\LoginBackground::OVERLAYS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if($overlay === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">Lapisan memakai warna utama sekolah. Pilih <em>Pekat</em> bila foto/video sangat terang agar tulisan tetap mudah dibaca.</div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a href="<?php echo e(route('dashboard.settings.login-preview')); ?>" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Buka pratinjau halaman login</a>
        <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Simpan latar login</button>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Gambaran langsung berubah saat pilihan lapisan warna diganti
        (function () {
            var select = document.querySelector('[data-overlay-select]');
            var mock = document.querySelector('[data-login-mock]');
            if (!select || !mock) return;
            select.addEventListener('change', function () {
                mock.className = mock.className.replace(/login-mock--\w+/, 'login-mock--' + select.value);
            });
        })();
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH S:\oa-school\resources\views/dashboard/settings/_login.blade.php ENDPATH**/ ?>