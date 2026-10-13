<?php if(! empty($menu?->intro) && strip_tags($menu->intro) !== '' && ! View::hasSection('no_intro')): ?>
    <div class="oa-panel oa-intro prose" data-reveal>
        <?php echo $menu->intro; ?>

    </div>
<?php endif; ?>
<?php /**PATH S:\oa-school\resources\views/web/partials/intro.blade.php ENDPATH**/ ?>