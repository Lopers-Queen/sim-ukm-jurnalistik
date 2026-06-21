<?php $__env->startSection('title', 'Akses Ditolak — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Akses Ditolak'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="text-center">
        <div class="mb-4">
            <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
        </div>
        <h2 class="fw-bold text-danger mb-2">403 — Akses Ditolak</h2>
        <p class="text-muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.<br>Hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">
                <i class="bi bi-speedometer2 me-1"></i>Kembali ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/errors/403.blade.php ENDPATH**/ ?>