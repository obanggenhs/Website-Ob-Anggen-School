<?php $__env->startSection('title', 'Struktur & Konten'); ?>
<?php $__env->startSection('page_title', 'Studio Struktur & Konten'); ?>
<?php $__env->startSection('page_subtitle', 'Satu tempat untuk menyusun menu website dan mengisi konten setiap menu.'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-xl-4">
            <?php echo $__env->make('dashboard.studio._tree', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="col-xl-8">
            <div class="card dash-card mb-4">
                <div class="card-body p-4">
                    <h2 class="h5">Cara kerja Studio</h2>
                    <p class="text-muted-d">Setiap menu di kiri adalah satu bagian website. Pilih menu untuk membuka ruang kerjanya.</p>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <i class="bi bi-1-circle-fill text-primary fs-4"></i>
                                <h3 class="h6 mt-2">Susun menu</h3>
                                <p class="small text-muted-d mb-0">Tambah Menu Utama atau Submenu, lalu seret untuk mengatur urutan di navbar.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <i class="bi bi-2-circle-fill text-primary fs-4"></i>
                                <h3 class="h6 mt-2">Isi kontennya</h3>
                                <p class="small text-muted-d mb-0">Halaman disusun dari blok. Menu modul (berita, galeri, dsb.) cukup diberi header & teks pembuka.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <i class="bi bi-3-circle-fill text-primary fs-4"></i>
                                <h3 class="h6 mt-2">Bagikan tugas</h3>
                                <p class="small text-muted-d mb-0">Di tab Akses Dashboard, pilih apakah menu ini juga dikelola Admin Publikasi atau Admin Pendaftaran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2>Jenis isi yang tersedia</h2></div>
                <div class="card-body">
                    <div class="type-grid">
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="type-option">
                                <label class="pe-none">
                                    <i class="bi <?php echo e($t['icon']); ?>"></i>
                                    <span><strong><?php echo e($t['label']); ?></strong><small><?php echo e($t['desc']); ?></small></span>
                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
    <?php echo $__env->make('dashboard.studio._create-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/studio/index.blade.php ENDPATH**/ ?>