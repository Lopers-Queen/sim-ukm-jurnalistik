<?php $__env->startSection('title', 'Laporan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Pusat Laporan'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Laporan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-1">Pusat Laporan & Ekspor</h4>
<p class="text-muted mb-4">Pilih jenis laporan untuk melihat preview atau langsung ekspor ke PDF/Excel</p>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px">
                    <i class="bi bi-people fs-3 text-primary"></i>
                </div>
                <h5 class="fw-bold">Laporan Anggota</h5>
                <p class="text-muted small">Data anggota lengkap dengan divisi, jabatan, dan status keanggotaan</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo e(route('laporan.anggota')); ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i>Preview</a>
                    <a href="<?php echo e(route('laporan.anggota.pdf')); ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
                    <a href="<?php echo e(route('laporan.anggota.excel')); ?>" class="btn btn-success btn-sm"><i class="bi bi-filetype-xlsx me-1"></i>Excel</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px">
                    <i class="bi bi-calendar-event fs-3 text-info"></i>
                </div>
                <h5 class="fw-bold">Laporan Event</h5>
                <p class="text-muted small">Ringkasan event beserta status, anggaran, dan realisasi</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo e(route('laporan.event')); ?>" class="btn btn-outline-info btn-sm"><i class="bi bi-eye me-1"></i>Preview</a>
                    <a href="<?php echo e(route('laporan.event.pdf')); ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px">
                    <i class="bi bi-wallet2 fs-3 text-warning"></i>
                </div>
                <h5 class="fw-bold">Laporan Keuangan</h5>
                <p class="text-muted small">Ringkasan anggaran divisi dan event (diajukan vs realisasi)</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo e(route('laporan.keuangan')); ?>" class="btn btn-outline-warning btn-sm"><i class="bi bi-eye me-1"></i>Preview</a>
                    <a href="<?php echo e(route('laporan.keuangan.pdf')); ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/laporan/index.blade.php ENDPATH**/ ?>