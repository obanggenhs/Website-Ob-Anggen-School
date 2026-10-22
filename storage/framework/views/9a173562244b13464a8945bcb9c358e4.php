<a href="<?php echo e(route('staff.show', $person->slug)); ?>" class="oa-card oa-person">
    <div class="oa-card__img oa-card__img--portrait"><img src="<?php echo e($person->photo_url); ?>" alt="<?php echo e($person->name); ?>" loading="lazy"></div>
    <div class="oa-card__body">
        <h3 class="oa-card__title"><?php echo e($person->name); ?></h3>
        <span class="oa-person__role"><?php echo e($person->position_title ?: $person->category_label); ?></span>
        <?php if($person->branch): ?><span class="oa-card__meta justify-content-center"><span><i class="bi bi-geo-alt"></i><?php echo e($person->branch->name); ?></span></span><?php endif; ?>
    </div>
</a>
<?php /**PATH S:\oa-school\resources\views/web/staff/_card.blade.php ENDPATH**/ ?>