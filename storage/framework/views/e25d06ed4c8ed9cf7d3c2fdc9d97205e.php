<?php
    $isRegistration = $channel === 'pendaftaran';
    $heading = $isRegistration ? 'Berita Pendaftaran' : ($channel === 'umum' ? 'Berita & Pengumuman' : 'Semua Berita');
?>

<?php $__env->startSection('title', $heading); ?>
<?php $__env->startSection('page_title', $heading); ?>
<?php $__env->startSection('page_subtitle', $isRegistration ? 'Pengumuman resmi panitia, tampil di halaman Pendaftaran dan daftar berita.' : 'Tulis, terbitkan, dan atur berita sekolah.'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.news.create', array_filter(['kanal' => $channel]))); ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tulis berita</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <?php if(count($channels) > 1): ?>
                    <select name="kanal" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua kanal</option>
                        <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c); ?>" <?php if($channel === $c): echo 'selected'; endif; ?>><?php echo e(\App\Models\News::CHANNELS[$c]); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php else: ?>
                    <input type="hidden" name="kanal" value="<?php echo e($channel); ?>">
                <?php endif; ?>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua status (<?php echo e($counts['published'] + $counts['draft']); ?>)</option>
                    <option value="published" <?php if(request('status') === 'published'): echo 'selected'; endif; ?>>Terbit (<?php echo e($counts['published']); ?>)</option>
                    <option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>Draf (<?php echo e($counts['draft']); ?>)</option>
                </select>
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($cat->id); ?>" <?php if(request('kategori') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="search" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control form-control-sm" placeholder="Cari judul...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead><tr><th colspan="2">Berita</th><th>Kategori</th><th>Status</th><th>Dibaca</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="width:64px"><img src="<?php echo e($n->cover_url); ?>" alt="" class="dash-thumb"></td>
                            <td>
                                <a href="<?php echo e(route('dashboard.news.edit', $n)); ?>" class="fw-bold text-body text-decoration-none"><?php echo e(\Illuminate\Support\Str::limit($n->title, 80)); ?></a>
                                <?php if($n->is_featured): ?><span class="badge text-bg-warning ms-1"><i class="bi bi-star-fill"></i> Penting</span><?php endif; ?>
                                <div class="small text-muted-d">
                                    <?php echo e(tgl($n->published_at ?? $n->created_at, 'd M Y, H.i')); ?> · <?php echo e($n->author?->name ?? '-'); ?>

                                    <?php if(count($channels) > 1): ?> · <span class="role-chip role-chip--<?php echo e($n->channel === 'pendaftaran' ? 'pendaftaran' : 'publikasi'); ?>"><?php echo e(\App\Models\News::CHANNELS[$n->channel]); ?></span><?php endif; ?>
                                </div>
                            </td>
                            <td class="small"><?php echo e($n->category?->name ?? '-'); ?></td>
                            <td>
                                <form method="POST" action="<?php echo e(route('dashboard.news.status', $n)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="badge border-0 <?php echo e($n->status === 'published' ? 'text-bg-success' : 'text-bg-secondary'); ?>" title="Klik untuk <?php echo e($n->status === 'published' ? 'menarik jadi draf' : 'menerbitkan'); ?>">
                                        <?php echo e($n->status === 'published' ? 'Terbit' : 'Draf'); ?>

                                    </button>
                                </form>
                            </td>
                            <td class="small"><?php echo e(number_format($n->views, 0, ',', '.')); ?></td>
                            <td>
                                <div class="dash-actions">
                                    <a href="<?php echo e(route('news.show', $n->slug)); ?>" target="_blank" class="btn btn-sm btn-light btn-icon" title="Lihat"><i class="bi bi-eye"></i></a>
                                    <a href="<?php echo e(route('dashboard.news.edit', $n)); ?>" class="btn btn-sm btn-soft btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                                    <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.news.destroy', $n), 'label' => 'berita ini'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6"><?php echo $__env->make('dashboard.partials.empty', ['icon' => 'bi-newspaper', 'text' => 'Belum ada berita.', 'action' => route('dashboard.news.create', array_filter(['kanal' => $channel])), 'actionLabel' => 'Tulis berita pertama'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($news->hasPages()): ?><div class="card-footer"><?php echo e($news->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/news/index.blade.php ENDPATH**/ ?>