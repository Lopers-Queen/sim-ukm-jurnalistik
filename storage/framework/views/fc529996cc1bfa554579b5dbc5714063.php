<?php $__env->startSection('title', 'Buat Notulensi — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Buat Notulensi'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('notulensi.index')); ?>">Notulensi</a></li>
<li class="breadcrumb-item active">Tambah</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Form Notulensi Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('notulensi.store')); ?>"><?php echo csrf_field(); ?>
            <?php echo $__env->make('notulensi._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                <a href="<?php echo e(route('notulensi.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/notulensi/create.blade.php ENDPATH**/ ?>