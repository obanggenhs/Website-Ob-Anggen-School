<?php
    $r = $registration;
    $info = $r->status_info;
    $sections = $r->type->fields->groupBy('section');
?>

<?php $__env->startSection('title', $r->full_name); ?>
<?php $__env->startSection('page_title', $r->full_name); ?>
<?php $__env->startSection('page_subtitle', $r->number.' · '.$r->type->name.' · daftar '.tgl($r->created_at, 'd F Y, H.i')); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.applicants.index')); ?>">Data Pendaftar</a></li>
    <li class="breadcrumb-item active"><?php echo e($r->number); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('dashboard.applicants.print', $r)); ?>" target="_blank" class="btn btn-light"><i class="bi bi-printer"></i> Cetak</a>
    <a href="<?php echo e(wa_link($r->phone, 'Halo '.$r->full_name.', kami dari panitia pendaftaran '.setting('site_name').'. Status pendaftaran '.$r->number.': '.$r->status_label.'. Cek detail di '.route('registration.status', ['nomor' => $r->number, 'email' => $r->email]))); ?>" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim WhatsApp</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card dash-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Data pendaftar</h2>
                    <span class="badge text-bg-<?php echo e($info['color']); ?> fs-7"><i class="bi <?php echo e($info['icon']); ?>"></i> <?php echo e($info['label']); ?></span>
                </div>
                <div class="card-body">
                    <dl class="detail-list">
                        <dt>Nama lengkap</dt><dd class="fw-bold"><?php echo e($r->full_name); ?></dd>
                        <dt>Email</dt><dd><a href="mailto:<?php echo e($r->email); ?>"><?php echo e($r->email); ?></a></dd>
                        <dt>No. HP / WA</dt><dd><a href="<?php echo e(wa_link($r->phone)); ?>" target="_blank"><?php echo e($r->phone); ?></a></dd>
                    </dl>
                    <?php $__currentLoopData = \App\Models\RegistrationField::SECTIONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($key !== 'dokumen' && $sections->has($key)): ?>
                            <h3 class="h6 mt-4 mb-2 text-muted-d"><?php echo e($label); ?></h3>
                            <dl class="detail-list">
                                <?php $__currentLoopData = $sections[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <dt><?php echo e($field->label); ?></dt>
                                    <dd><?php echo e(($r->data[$field->name] ?? null) ?: '-'); ?></dd>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </dl>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2>Dokumen</h2></div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php $__empty_1 = true; $__currentLoopData = $sections['dokumen'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $file = $r->files[$field->name] ?? null; ?>
                            <div class="col-md-6">
                                <?php if($file): ?>
                                    <a href="<?php echo e(route('dashboard.applicants.file', [$r, $field->name])); ?>" target="_blank" class="file-tile">
                                        <i class="bi <?php echo e(str_ends_with(strtolower($file['name'] ?? ''), '.pdf') ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image'); ?>"></i>
                                        <span class="min-w-0"><strong class="d-block small"><?php echo e($field->label); ?></strong><small class="text-muted-d text-truncate d-block"><?php echo e($file['name'] ?? ''); ?> · <?php echo e(number_format(($file['size'] ?? 0) / 1024, 0)); ?> KB</small></span>
                                    </a>
                                <?php else: ?>
                                    <div class="file-tile opacity-50"><i class="bi bi-file-earmark-x"></i><span><strong class="d-block small"><?php echo e($field->label); ?></strong><small>Tidak diunggah</small></span></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 small text-muted-d">Jalur ini tidak meminta dokumen.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2>Riwayat proses</h2></div>
                <div class="card-body">
                    <ul class="timeline">
                        <?php $__currentLoopData = $r->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="<?php echo e($log->is_public ? '' : 'is-private'); ?>">
                                <strong><?php echo e($log->title); ?></strong>
                                <?php if (! ($log->is_public)): ?><span class="badge text-bg-light border">internal</span><?php endif; ?>
                                <?php if($log->note): ?><div><?php echo e($log->note); ?></div><?php endif; ?>
                                <small class="text-muted-d"><?php echo e(tgl($log->created_at, 'd M Y, H.i')); ?> · <?php echo e($log->user?->name ?? 'Pendaftar / sistem'); ?></small>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card dash-card mb-4" style="border-top: 4px solid var(--dash-primary)">
                <div class="card-header"><h2><span class="badge rounded-pill text-bg-dark me-1">1</span> Ubah status</h2></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('dashboard.applicants.status', $r)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="d-grid gap-2 mb-3" style="grid-template-columns: 1fr 1fr">
                            <?php $__currentLoopData = \App\Models\Registration::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" class="btn-check" name="status" id="st_<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php if($r->status === $key): echo 'checked'; endif; ?>>
                                <label class="btn btn-outline-<?php echo e($s['color']); ?> btn-sm text-start" for="st_<?php echo e($key); ?>"><i class="bi <?php echo e($s['icon']); ?>"></i> <?php echo e($s['label']); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <label class="form-label" for="public_note">Pesan untuk pendaftar</label>
                        <textarea name="public_note" id="public_note" rows="3" class="form-control mb-2" placeholder="mis. Berkas lengkap, silakan tunggu jadwal wawancara."><?php echo e($r->public_note); ?></textarea>
                        <?php echo $__env->make('dashboard.applicants._channels', ['n' => 1, 'r' => $r, 'mailText' => 'Kirim email pemberitahuan ke', 'waText' => 'Kirim WhatsApp pemberitahuan ke'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <button class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Simpan status</button>
                    </form>
                </div>
            </div>

            <?php echo $__env->make('dashboard.applicants._notifications', ['r' => $r], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2><span class="badge rounded-pill text-bg-dark me-1">2</span> Jadwal tes / wawancara</h2></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('dashboard.applicants.schedule', $r)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label" for="schedule_at">Tanggal & jam (WIT)</label>
                                <input type="datetime-local" name="schedule_at" id="schedule_at" value="<?php echo e($r->schedule_at?->format('Y-m-d\TH:i')); ?>" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="schedule_place">Tempat</label>
                                <input type="text" name="schedule_place" id="schedule_place" value="<?php echo e($r->schedule_place); ?>" class="form-control" placeholder="Ruang guru kampus Bokondini" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <?php echo $__env->make('dashboard.applicants._channels', ['n' => 2, 'r' => $r, 'mailText' => 'Kirim email jadwal ke', 'waText' => 'Kirim WhatsApp jadwal ke'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <button class="btn btn-soft w-100"><i class="bi bi-calendar-check me-1"></i> Simpan jadwal</button>
                    </form>
                </div>
            </div>

            <div class="card dash-card mb-4">
                <div class="card-header"><h2><span class="badge rounded-pill text-bg-dark me-1">3</span> Catatan internal</h2></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('dashboard.applicants.note', $r)); ?>">
                        <?php echo csrf_field(); ?>
                        <textarea name="note" rows="3" class="form-control mb-2" placeholder="Hanya terlihat oleh admin." required></textarea>
                        <button class="btn btn-light w-100"><i class="bi bi-journal-plus me-1"></i> Tambah catatan</button>
                    </form>
                    <?php if($r->internal_note): ?><div class="small text-muted-d mt-2">Catatan terakhir: <?php echo e($r->internal_note); ?></div><?php endif; ?>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <small class="text-muted-d">Ditangani: <?php echo e($r->handler?->name ?? 'belum ada'); ?></small>
                    <?php echo $__env->make('dashboard.partials.delete', ['url' => route('dashboard.applicants.destroy', $r), 'class' => 'btn-outline-danger', 'text' => 'Hapus data', 'confirm' => 'Hapus data pendaftar '.$r->full_name.' beserta dokumennya?'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/dashboard/applicants/show.blade.php ENDPATH**/ ?>