<?php $__env->startSection('title', 'Laporan Event — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Laporan Event'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Laporan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Event</h4>
        <p class="text-muted mb-0">Ringkasan seluruh event UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('laporan.event.pdf')); ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Export PDF</a>
        <a href="<?php echo e(route('laporan.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h3 class="fw-bold text-primary mb-0"><?php echo e($events->count()); ?></h3>
                <small class="text-muted">Total Event</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h3 class="fw-bold text-success mb-0">Rp <?php echo e(number_format($totalAnggaran, 0, ',', '.')); ?></h3>
                <small class="text-muted">Total Anggaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center py-3">
                <h3 class="fw-bold text-info mb-0">Rp <?php echo e(number_format($totalRealisasi, 0, ',', '.')); ?></h3>
                <small class="text-muted">Total Realisasi</small>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>PIC</th>
                        <th>Status</th>
                        <th class="text-end">Anggaran</th>
                        <th class="text-end">Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($i + 1); ?></td>
                        <td>
                            <strong><?php echo e($event->nama_event); ?></strong>
                            <?php if($event->lokasi): ?><br><small class="text-muted"><?php echo e($event->lokasi); ?></small><?php endif; ?>
                        </td>
                        <td><?php echo e($event->tanggal_mulai->format('d/m/Y')); ?>

                            <?php if($event->tanggal_selesai): ?> - <?php echo e($event->tanggal_selesai->format('d/m/Y')); ?><?php endif; ?>
                        </td>
                        <td><?php echo e($event->pic->nama_lengkap ?? '-'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e(match($event->status) {
                                'selesai' => 'success', 'aktif' => 'primary', 'direncanakan' => 'warning',
                                'batal' => 'danger', default => 'secondary'
                            }); ?>"><?php echo e(ucfirst($event->status)); ?></span>
                        </td>
                        <td class="text-end">Rp <?php echo e(number_format($event->anggaran_total, 0, ',', '.')); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($event->anggaranEvent->sum('jumlah_realisasi'), 0, ',', '.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada event terdaftar</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if($events->count()): ?>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">TOTAL</td>
                        <td class="text-end">Rp <?php echo e(number_format($totalAnggaran, 0, ',', '.')); ?></td>
                        <td class="text-end">Rp <?php echo e(number_format($totalRealisasi, 0, ',', '.')); ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/laporan/event.blade.php ENDPATH**/ ?>