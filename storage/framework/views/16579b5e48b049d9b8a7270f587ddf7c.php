<?php $__env->startSection('title', 'Formulir '.$type->name); ?>
<?php $__env->startSection('page_title', 'Formulir '.$type->name); ?>
<?php $__env->startSection('page_subtitle', 'Lengkapi data berikut dengan benar. Tanda * wajib diisi.'); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('registration.index')); ?>">Pendaftaran</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('registration.show', $type->slug)); ?>"><?php echo e($type->name); ?></a></li>
    <li class="breadcrumb-item active">Formulir</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('no_intro', true); ?>

<?php $__env->startSection('page'); ?>
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i> Ada <?php echo e($errors->count()); ?> isian yang perlu diperbaiki. Periksa tanda merah di bawah.</div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-warning"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('registration.submit', $type->slug)); ?>" enctype="multipart/form-data" data-once>
                <?php echo csrf_field(); ?>

                <div class="oa-form-section">
                    <h2 class="oa-form-section__title"><span>1</span> Kontak utama</h2>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="full_name" class="form-label">Nama lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" value="<?php echo e(old('full_name')); ?>" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Dipakai untuk cek status & menerima pemberitahuan.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="08xxxxxxxxxx" required>
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <?php $no = 2; ?>
                <?php $__currentLoopData = \App\Models\RegistrationField::SECTIONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionKey => $sectionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($sections->has($sectionKey)): ?>
                        <div class="oa-form-section">
                            <h2 class="oa-form-section__title"><span><?php echo e($no++); ?></span> <?php echo e($sectionLabel); ?></h2>
                            <div class="row g-3">
                                <?php $__currentLoopData = $sections[$sectionKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('web.registration._field', ['field' => $field], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="oa-form-section">
                    <div class="form-check">
                        <input class="form-check-input <?php $__errorArgs = ['consent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="checkbox" name="consent" id="consent" value="1" <?php if(old('consent')): echo 'checked'; endif; ?> required>
                        <label class="form-check-label" for="consent">
                            Saya menyatakan data yang diisi adalah benar dan menyetujui data ini digunakan hanya untuk keperluan seleksi <?php echo e(setting('site_name')); ?>.
                        </label>
                        <?php $__errorArgs = ['consent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mt-4">
                        <a href="<?php echo e(route('registration.show', $type->slug)); ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-accent btn-lg"><i class="bi bi-send me-1"></i> Kirim pendaftaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web-full', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/registration/form.blade.php ENDPATH**/ ?>