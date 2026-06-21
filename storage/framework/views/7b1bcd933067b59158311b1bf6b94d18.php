<?php $__env->startSection('title', 'Detail Notulensi — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Detail Notulensi'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('notulensi.index')); ?>">Notulensi</a></li>
<li class="breadcrumb-item active">Detail</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><?php echo e($notulensi->judul); ?></h5>
            <span class="badge bg-info"><?php echo e($notulensi->jenis_rapat_label); ?></span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal:</strong> <?php echo e($notulensi->tanggal_rapat->translatedFormat('d F Y')); ?></div>
                <div class="col-md-4"><strong>Lokasi:</strong> <?php echo e($notulensi->lokasi ?? '-'); ?></div>
                <div class="col-md-4"><strong>Pencatat:</strong> <?php echo e($notulensi->pencatat?->nama_lengkap ?? '-'); ?></div>
            </div>
            <h6 class="fw-semibold">Isi Notulensi</h6>
            <div class="bg-light rounded p-4 mb-4" style="white-space: pre-wrap;"><?php echo e($notulensi->isi_notulensi); ?></div>
            <div class="d-flex gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notulensi.edit')): ?><a href="<?php echo e(route('notulensi.edit', $notulensi)); ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a><?php endif; ?>
                <a href="<?php echo e(route('notulensi.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/notulensi/show.blade.php ENDPATH**/ ?>