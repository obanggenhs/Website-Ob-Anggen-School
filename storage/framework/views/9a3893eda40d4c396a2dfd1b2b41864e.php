<?php $__env->startSection('title', 'Beranda Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="dash-hero">
        <div class="position-relative" style="z-index:1">
            <h1>Halo, <?php echo e(auth()->user()->name); ?></h1>
            <p>Apa yang ingin dipublikasikan hari ini? Tulis berita, unggah album kegiatan, atau perbarui agenda sekolah.</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="<?php echo e(route('dashboard.news.create')); ?>" class="btn btn-light"><i class="bi bi-pencil-square"></i> Tulis berita</a>
                <a href="<?php echo e(route('dashboard.gallery.create')); ?>" class="btn btn-outline-light"><i class="bi bi-images"></i> Buat album</a>
                <a href="<?php echo e(route('dashboard.calendar.create')); ?>" class="btn btn-outline-light"><i class="bi bi-calendar-plus"></i> Tambah agenda</a>
                <a href="<?php echo e(route('dashboard.graduates.create')); ?>" class="btn btn-outline-light"><i class="bi bi-mortarboard"></i> Tambah lulusan</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi <?php echo e($s['icon']); ?>"></i><span><strong><?php echo e($s['value']); ?></strong><small><?php echo e($s['label']); ?></small></span></div></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Berita terakhir</h2>
                    <a href="<?php echo e(route('dashboard.news.index')); ?>" class="small">Semua berita <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $latestNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td style="width:64px"><img src="<?php echo e($n->cover_url); ?>" class="dash-thumb" alt=""></td>
                                    <td class="min-w-0">
                                        <a href="<?php echo e(route('dashboard.news.edit', $n)); ?>" class="fw-bold text-body text-decoration-none"><?php echo e(\Illuminate\Support\Str::limit($n->title, 70)); ?></a>
                                        <div class="small text-muted-d"><?php echo e($n->category?->name ?? 'Tanpa kategori'); ?> · <?php echo e(tgl($n->published_at ?? $n->created_at, 'd M Y')); ?></div>
                                    </td>
                                    <td class="text-end"><span class="badge <?php echo e($n->status === 'published' ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($n->status === 'published' ? 'Terbit' : 'Draf'); ?></span></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td><?php echo $__env->make('dashboard.partials.empty', ['text' => 'Belum ada berita.', 'icon' => 'bi-newspaper'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Album terbaru</h2>
                    <a href="<?php echo e(route('dashboard.gallery.index')); ?>" class="small">Semua album <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php $__empty_1 = true; $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-6 col-md-3">
                                <a href="<?php echo e(route('dashboard.gallery.edit', $album)); ?>" class="text-decoration-none text-body">
                                    <img src="<?php echo e($album->cover_url); ?>" alt="" class="w-100 rounded-3 mb-2" style="aspect-ratio:4/3;object-fit:cover">
                                    <div class="small fw-bold text-truncate"><?php echo e($album->title); ?></div>
                                    <div class="small text-muted-d"><?php echo e($album->photos_count); ?> foto</div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12"><?php echo $__env->make('dashboard.partials.empty', ['text' => 'Belum ada album.', 'icon' => 'bi-images'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <?php if($assigned->isNotEmpty()): ?>
                <div class="card dash-card mb-4" style="border-top: 4px solid var(--dash-primary)">
                    <div class="card-header"><h2><i class="bi bi-person-check me-1"></i> Tugas dari Master Admin</h2></div>
                    <div class="card-body py-2">
                        <ul class="dash-list">
                            <?php $__currentLoopData = $assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <i class="bi <?php echo e($m->type_icon); ?> text-primary"></i>
                                    <span class="min-w-0 flex-fill">
                                        <a href="<?php echo e(route('dashboard.studio.show', $m)); ?>" class="dash-list__title d-block"><?php echo e($m->title); ?></a>
                                        <span class="dash-list__meta"><?php echo e($m->type_label); ?></span>
                                    </span>
                                    <a href="<?php echo e($m->link); ?>" target="_blank" class="btn btn-sm btn-light btn-icon" title="Lihat di website"><i class="bi bi-box-arrow-up-right"></i></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Agenda terdekat</h2>
                    <a href="<?php echo e(route('dashboard.calendar.index')); ?>" class="small">Kalender <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body py-2">
                    <ul class="dash-list">
                        <?php $__empty_1 = true; $__currentLoopData = $agenda; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <span class="badge" style="background: <?php echo e($e->category_color); ?>"><?php echo e($e->start_date->format('d/m')); ?></span>
                                <span class="min-w-0"><span class="dash-list__title d-block text-truncate"><?php echo e($e->title); ?></span><span class="dash-list__meta"><?php echo e($e->level_label); ?> · <?php echo e($e->relative_label); ?></span></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="small text-muted-d">Tidak ada agenda mendatang.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2>Berita paling banyak dibaca</h2></div>
                <div class="card-body py-2">
                    <ol class="mb-0 ps-3 small">
                        <?php $__currentLoopData = $popular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="py-1"><a href="<?php echo e(route('news.show', $p->slug)); ?>" target="_blank" class="text-body"><?php echo e(\Illuminate\Support\Str::limit($p->title, 55)); ?></a> <span class="text-muted-d">· <?php echo e($p->views); ?>x</span></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/home/publikasi.blade.php ENDPATH**/ ?>