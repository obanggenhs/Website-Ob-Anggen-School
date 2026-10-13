<?php
    // $preview = true saat Master Admin membuka pratinjau dari Pengaturan Website > Halaman Login
    $preview = $preview ?? false;
?>

<?php $__env->startSection('title', $preview ? 'Pratinjau Halaman Login' : 'Masuk Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-card">
        <?php if($preview): ?>
            <div class="alert alert-warning d-flex gap-2 align-items-start py-2 small">
                <i class="bi bi-eye"></i>
                <div>
                    <strong>Mode pratinjau.</strong> Beginilah tampilan halaman login bagi pengunjung. Tombol Masuk dinonaktifkan.
                    <a href="<?php echo e(route('dashboard.settings.edit', 'login')); ?>" class="d-block mt-1">Kembali ke Pengaturan Halaman Login</a>
                </div>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('home')); ?>" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke website</a>
        <h2 class="mt-4 mb-1">Masuk ke dashboard</h2>
        <p class="text-secondary mb-4">Gunakan akun yang diberikan oleh Master Admin.</p>

        <?php if(session('success')): ?><div class="alert alert-success py-2"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div class="alert alert-danger py-2"><?php echo e(session('error')); ?></div><?php endif; ?>

        <form method="POST" action="<?php echo e($preview ? '#' : route('login.attempt')); ?>" <?php if($preview): ?> onsubmit="return false" <?php endif; ?>>
            <?php echo csrf_field(); ?>
            <fieldset <?php if($preview): echo 'disabled'; endif; ?>>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required <?php if (! ($preview)): ?> autofocus <?php endif; ?> autocomplete="username">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="password" aria-label="Lihat kata sandi"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember">Ingat saya di perangkat ini</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>
            </fieldset>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/auth/login.blade.php ENDPATH**/ ?>