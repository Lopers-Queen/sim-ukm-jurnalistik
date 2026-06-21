<?php $__env->startSection('title', 'Tambah Anggota — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Tambah Anggota Baru'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('anggota.index')); ?>">Anggota</a></li>
<li class="breadcrumb-item active">Tambah</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-semibold">Form Tambah Anggota</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('anggota.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('anggota._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Anggota
                        </button>
                        <a href="<?php echo e(route('anggota.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card mt-3 border-info">
            <div class="card-body small">
                <div class="fw-semibold mb-1"><i class="bi bi-info-circle text-info me-1"></i>Informasi</div>
                <ul class="mb-0 ps-3">
                    <li>Password default akan di-generate otomatis dari tanggal lahir (format DDMMYYYY).</li>
                    <li>Anggota wajib mengganti password saat login pertama kali.</li>
                    <li>Role Spatie akan otomatis di-assign berdasarkan jabatan yang dipilih.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/anggota/create.blade.php ENDPATH**/ ?>