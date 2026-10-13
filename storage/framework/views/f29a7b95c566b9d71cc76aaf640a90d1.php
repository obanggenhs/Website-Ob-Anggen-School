<?php $__env->startSection('title', $menu?->title ?? 'Pendaftaran'); ?>

<?php $__env->startSection('page'); ?>
    <div class="oa-chips mb-4">
        <a href="<?php echo e(route('registration.index')); ?>" class="oa-chip <?php echo e(! $audience ? 'active' : ''); ?>">Semua jalur</a>
        <?php $__currentLoopData = \App\Models\RegistrationType::AUDIENCES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('registration.index', ['untuk' => $key])); ?>" class="oa-chip <?php echo e($audience === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if(\App\Models\Menu::moduleEnabled('cek_status')): ?>
            <a href="<?php echo e(route('registration.status')); ?>" class="oa-chip ms-auto"><i class="bi bi-search"></i> Cek status</a>
        <?php endif; ?>
    </div>

    <?php if($types->isEmpty()): ?>
        <div class="oa-empty"><i class="bi bi-clipboard-x"></i>Belum ada jalur pendaftaran.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $open = $type->isAccepting(); ?>
                <div class="col-md-6" data-reveal>
                    <a href="<?php echo e(route('registration.show', $type->slug)); ?>" class="oa-card">
                        <div class="oa-card__img">
                            <img src="<?php echo e($type->image_url); ?>" alt="<?php echo e($type->name); ?>" loading="lazy">
                            <span class="oa-card__badge oa-badge <?php echo e($open ? 'oa-badge--green' : 'oa-badge--grey'); ?>">
                                <i class="bi <?php echo e($open ? 'bi-door-open' : 'bi-door-closed'); ?>"></i> <?php echo e($type->status_label); ?>

                            </span>
                        </div>
                        <div class="oa-card__body">
                            <span class="oa-card__meta">
                                <span><i class="bi bi-person-badge"></i><?php echo e(\App\Models\RegistrationType::AUDIENCES[$type->audience]); ?><?php echo e($type->level_label ? ' · '.$type->level_label : ''); ?></span>
                                <?php if($type->period_label): ?><span><i class="bi bi-calendar-range"></i><?php echo e($type->period_label); ?></span><?php endif; ?>
                            </span>
                            <h3 class="oa-card__title"><?php echo e($type->name); ?></h3>
                            <p class="oa-card__text"><?php echo e($type->summary); ?></p>
                            <span class="oa-card__foot oa-link-more"><?php echo e($open ? 'Lihat persyaratan & daftar' : 'Lihat informasi'); ?> <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('sidebar'); ?>
    <?php if($announcements->isNotEmpty()): ?>
        <div class="oa-widget">
            <h2 class="oa-widget__title">Info pendaftaran</h2>
            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="oa-mini-post">
                    <img src="<?php echo e($item->cover_url); ?>" alt="" loading="lazy">
                    <span class="min-w-0"><strong><?php echo e($item->title); ?></strong><small><?php echo e(tgl($item->published_at, 'd M Y')); ?></small></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.web-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\oa-school\resources\views/web/registration/index.blade.php ENDPATH**/ ?>