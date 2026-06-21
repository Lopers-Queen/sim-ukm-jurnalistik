<?php $__env->startSection('title', 'Detail Periode — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', $periode->nama_periode); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('periode.index')); ?>">Periode</a></li>
<li class="breadcrumb-item active">Detail</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="fw-bold"><?php echo e($periode->nama_periode); ?></h4>
                <p class="text-muted"><?php echo e($periode->tahun_mulai); ?> — <?php echo e($periode->tahun_selesai); ?></p>
                <?php $badge = match($periode->status) { 'aktif' => 'success', 'upcoming' => 'info', default => 'secondary' }; ?>
                <span class="badge bg-<?php echo e($badge); ?> fs-6"><?php echo e(ucfirst($periode->status)); ?></span>
                <hr>
                <div class="d-flex justify-content-around">
                    <div><div class="fs-3 fw-bold text-primary"><?php echo e($periode->riwayatKepengurusan->count()); ?></div><div class="small text-muted">Pengurus</div></div>
                    <div><div class="fs-3 fw-bold text-info"><?php echo e($periode->events->count()); ?></div><div class="small text-muted">Event</div></div>
                </div>
                <div class="d-flex gap-2 mt-3 justify-content-center">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('periode.edit')): ?><a href="<?php echo e(route('periode.edit', $periode)); ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a><?php endif; ?>
                    <a href="<?php echo e(route('periode.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Pengurus Periode Ini</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0"><thead class="table-light"><tr><th>Nama</th><th>Jabatan</th><th>Divisi</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $periode->riwayatKepengurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><?php echo e($r->anggota?->nama_lengkap); ?></td><td><?php echo e($r->jabatan_label); ?></td><td><?php echo e($r->divisi ?? '-'); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="text-center text-muted py-3">Belum ada data pengurus</td></tr><?php endif; ?>
                </tbody></table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Event di Periode Ini</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0"><thead class="table-light"><tr><th>Nama Event</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $periode->events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><a href="<?php echo e(route('event.show', $e)); ?>"><?php echo e($e->nama_event); ?></a></td><td><?php echo e($e->tanggal_mulai->format('d/m/Y')); ?></td>
                    <td><span class="badge bg-<?php echo e($e->status_badge); ?>"><?php echo e($e->status_label); ?></span></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="text-center text-muted py-3">Belum ada event</td></tr><?php endif; ?>
                </tbody></table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/periode/show.blade.php ENDPATH**/ ?>