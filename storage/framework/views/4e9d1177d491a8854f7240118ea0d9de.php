
<?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
<nav class="app-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><i class="bi bi-house-door"></i></a></li>
        <?php echo $__env->yieldContent('breadcrumb'); ?>
    </ol>
</nav>
<?php endif; ?>
<?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/layouts/_breadcrumb.blade.php ENDPATH**/ ?>