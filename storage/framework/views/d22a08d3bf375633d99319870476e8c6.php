<?php $__env->startSection('title', 'Data Menu Baru'); ?>
<?php $__env->startSection('page_title', 'Data Menu Baru'); ?>
<?php $__env->startSection('page_subtitle', 'Isi Menu Utama, Submenu, dan halaman yang dibuat admin (bukan menu bawaan). Tersimpan di database sekolah dan diperbarui otomatis setiap kali disimpan.'); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.archive.export', request()->only('menu', 'cari'))); ?>" class="btn btn-soft"><i class="bi bi-file-earmark-spreadsheet"></i> Unduh Excel (CSV)</a>
    <?php if(auth()->user()->isMaster()): ?>
        <form method="POST" action="<?php echo e(route('dashboard.archive.rebuild')); ?>" class="d-inline">
            <?php echo csrf_field(); ?>
            <button class="btn btn-light" title="Bangun ulang data dari isi website terbaru"><i class="bi bi-arrow-repeat"></i> Perbarui data</button>
        </form>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-diagram-3"></i><span><strong><?php echo e($stats['menus']); ?></strong><small>Menu & submenu buatan admin</small></span></div></div>
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-layout-text-window"></i><span><strong><?php echo e($stats['items']); ?></strong><small>Isi halaman tersimpan</small></span></div></div>
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-paperclip"></i><span><strong><?php echo e($stats['files']); ?></strong><small>File (foto, video, dokumen)</small></span></div></div>
        <div class="col-6 col-xl-3"><div class="dash-stat"><i class="bi bi-database-check"></i><span><strong><?php echo e($stats['synced'] ? \Illuminate\Support\Carbon::parse($stats['synced'])->diffForHumans() : '-'); ?></strong><small>Terakhir diperbarui</small></span></div></div>
    </div>

    <div class="alert alert-light border small d-flex gap-2 align-items-start">
        <i class="bi bi-database text-primary"></i>
        <div>
            Di database <strong><?php echo e(config('database.connections.'.config('database.default').'.database')); ?></strong>, data ini ada di tabel
            <code>menu_contents</code> dengan kolom <code>main_menu</code> (Menu Utama), <code>submenu</code>, <code>page_title</code> (halaman),
            <code>content_type</code>, <code>content_title</code>, <code>content_text</code> (isi), dan <code>file_names</code>.
            Menu bawaan tidak ikut dicatat.
        </div>
    </div>

    <div class="card dash-card">
        <div class="card-header">
            <form class="dash-filter" method="GET">
                <select name="menu" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Pilih menu">
                    <option value="">Semua menu buatan admin</option>
                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m->id); ?>" <?php if($current?->id === $m->id): echo 'selected'; endif; ?>><?php echo e($m->parent ? $m->parent->title.' › '.$m->title : $m->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="search" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control form-control-sm" placeholder="Cari menu, judul, isi, nama file...">
                <button class="btn btn-sm btn-soft"><i class="bi bi-search"></i></button>
                <?php if(request()->filled('cari') || $current): ?>
                    <a href="<?php echo e(route('dashboard.archive.index')); ?>" class="btn btn-sm btn-light" title="Hapus filter"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table dash-table archive-table">
                <thead>
                    <tr><th style="width:56px">Urutan</th><th>Jenis isi</th><th>Judul &amp; isi</th><th>File</th><th>Status</th><th>Diubah</th></tr>
                </thead>
                <tbody>
                    <?php $group = null; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if($group !== $row->menu_id): ?>
                            <?php $group = $row->menu_id; ?>
                            <tr class="archive-group">
                                <td colspan="6">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div class="min-w-0">
                                            <span class="archive-path">
                                                <i class="bi bi-folder2-open"></i> <?php echo e($row->main_menu); ?>

                                                <?php if($row->submenu): ?><i class="bi bi-chevron-right small"></i> <?php echo e($row->submenu); ?><?php endif; ?>
                                            </span>
                                            <span class="badge badge-soft ms-1"><?php echo e($row->page_type); ?></span>
                                            <?php if($row->created_by): ?><small class="text-muted-d ms-1">dibuat <?php echo e($row->created_by); ?></small><?php endif; ?>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <?php if($row->page_url): ?>
                                                <a href="<?php echo e($row->page_url); ?>" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-up-right"></i> Lihat</a>
                                            <?php endif; ?>
                                            <?php if($row->menu): ?>
                                                <a href="<?php echo e(route('dashboard.studio.show', $row->menu_id)); ?>" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Kelola</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr class="<?php echo e($row->is_active ? '' : 'is-hidden'); ?>">
                            <td class="text-muted-d"><?php echo e($row->position ?: '–'); ?></td>
                            <td class="text-nowrap"><span class="badge text-bg-light border"><?php echo e($row->content_type); ?></span></td>
                            <td class="archive-text">
                                <?php if($row->content_title): ?><strong class="d-block"><?php echo e($row->content_title); ?></strong><?php endif; ?>
                                <?php if($row->content_text): ?><span class="text-muted-d"><?php echo e(\Illuminate\Support\Str::limit($row->content_text, 180)); ?></span><?php endif; ?>
                                <?php if($row->link): ?><div class="small text-truncate"><i class="bi bi-link-45deg"></i> <?php echo e($row->link); ?></div><?php endif; ?>
                                <?php if(! $row->content_title && ! $row->content_text && ! $row->link): ?><span class="text-muted-d">—</span><?php endif; ?>
                            </td>
                            <td class="small">
                                <?php if($row->files): ?>
                                    <span class="fw-semibold"><?php echo e(count($row->files)); ?> file</span>
                                    <div class="text-muted-d archive-files" title="<?php echo e($row->file_names); ?>"><?php echo e(\Illuminate\Support\Str::limit($row->file_names, 70)); ?></div>
                                <?php else: ?>
                                    <span class="text-muted-d">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $__env->make('dashboard.partials.status', ['active' => $row->is_active], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            <td class="small text-nowrap"><?php echo e($row->updated_at ? tgl($row->updated_at, 'd M Y, H.i') : '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <?php echo $__env->make('dashboard.partials.empty', [
                                    'icon' => 'bi-database',
                                    'text' => request()->filled('cari')
                                        ? 'Tidak ada isi yang cocok dengan pencarian.'
                                        : 'Belum ada menu buatan admin. Buat Menu Utama atau Submenu baru di Struktur & Konten; isinya otomatis tercatat di sini.',
                                    'action' => auth()->user()->isMaster() && ! request()->filled('cari') ? route('dashboard.studio.index') : null,
                                    'actionLabel' => 'Buka Struktur & Konten',
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($rows->hasPages()): ?><div class="card-footer"><?php echo e($rows->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/archive/index.blade.php ENDPATH**/ ?>