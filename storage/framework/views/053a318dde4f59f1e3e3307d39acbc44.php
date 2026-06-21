<?php $__env->startSection('title', 'Laporan Keuangan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Laporan Keuangan'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Laporan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Keuangan</h4>
        <p class="text-muted mb-0">Ringkasan anggaran divisi dan event UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('laporan.keuangan.pdf')); ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Export PDF</a>
        <a href="<?php echo e(route('laporan.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-primary mb-0">Rp <?php echo e(number_format($totalDivisiAnggaran, 0, ',', '.')); ?></h5>
                <small class="text-muted">Divisi — Anggaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-success mb-0">Rp <?php echo e(number_format($totalDivisiTerpakai, 0, ',', '.')); ?></h5>
                <small class="text-muted">Divisi — Terpakai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-info mb-0">Rp <?php echo e(number_format($totalEventAnggaran, 0, ',', '.')); ?></h5>
                <small class="text-muted">Event — Dianggarkan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-warning mb-0">Rp <?php echo e(number_format($totalEventRealisasi, 0, ',', '.')); ?></h5>
                <small class="text-muted">Event — Realisasi</small>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-building me-1"></i> Anggaran per Divisi</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Divisi</th><th>Bulan / Tahun</th><th class="text-end">Anggaran</th><th class="text-end">Terpakai</th><th class="text-end">Sisa</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $anggaranDivisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td><?php echo e(ucfirst(str_replace('_', ' ', $ad->divisi))); ?></td>
                        <td><?php echo e($ad->bulan); ?>/<?php echo e($ad->tahun); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($ad->jumlah_anggaran, 0, ',', '.')); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($ad->jumlah_terpakai, 0, ',', '.')); ?></td>
                        <td class="text-end fw-semibold <?php echo e($ad->sisa_anggaran < 0 ? 'text-danger' : 'text-success'); ?>">Rp <?php echo e(number_format($ad->sisa_anggaran, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data anggaran divisi</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-1"></i> Anggaran per Event</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Event</th><th>Item</th><th>Kategori</th><th class="text-end">Qty</th><th class="text-end">Dianggarkan</th><th class="text-end">Realisasi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $anggaranEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ae): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td><?php echo e($ae->event->nama_event ?? '-'); ?></td>
                        <td><?php echo e($ae->item); ?></td>
                        <td><?php echo e($ae->kategori ?? '-'); ?></td>
                        <td class="text-end"><?php echo e($ae->qty); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($ae->jumlah_dianggarkan, 0, ',', '.')); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($ae->jumlah_realisasi ?? 0, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data anggaran event</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if($anggaranEvent->count()): ?>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">TOTAL</td>
                        <td class="text-end">Rp <?php echo e(number_format($totalEventAnggaran, 0, ',', '.')); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($totalEventRealisasi, 0, ',', '.')); ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/laporan/keuangan.blade.php ENDPATH**/ ?>