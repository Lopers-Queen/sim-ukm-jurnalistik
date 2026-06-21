<?php $__env->startSection('title', 'Periode Kepengurusan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Periode Kepengurusan'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Periode</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Periode</h4><p class="text-muted mb-0">Kelola periode kepengurusan UKM</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('periode.create')): ?><a href="<?php echo e(route('periode.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Periode</a><?php endif; ?>
</div>
<div class="row g-4">
<?php $__empty_1 = true; $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="col-md-6 col-xl-4">
    <div class="card h-100 <?php echo e($periode->status === 'aktif' ? 'border-success' : ''); ?>">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold mb-0"><?php echo e($periode->nama_periode); ?></h5>
                <?php $badge = match($periode->status) { 'aktif' => 'success', 'upcoming' => 'info', default => 'secondary' }; ?>
                <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($periode->status)); ?></span>
            </div>
            <p class="text-muted mb-3"><?php echo e($periode->tahun_mulai); ?> — <?php echo e($periode->tahun_selesai); ?></p>
            <div class="d-flex gap-4 mb-3">
                <div class="text-center"><div class="fs-4 fw-bold text-primary"><?php echo e($periode->riwayat_kepengurusan_count); ?></div><div class="small text-muted">Pengurus</div></div>
                <div class="text-center"><div class="fs-4 fw-bold text-info"><?php echo e($periode->events_count); ?></div><div class="small text-muted">Event</div></div>
            </div>
            <div class="btn-group btn-group-sm w-100">
                <a href="<?php echo e(route('periode.show', $periode)); ?>" class="btn btn-outline-info"><i class="bi bi-eye me-1"></i>Detail</a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('periode.edit')): ?><a href="<?php echo e(route('periode.edit', $periode)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('periode.delete')): ?><form method="POST" action="<?php echo e(route('periode.destroy', $periode)); ?>" class="d-inline" onsubmit="return confirm('Hapus periode &quot;<?php echo e($periode->nama_periode); ?>&quot;?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button></form><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="col-12"><div class="text-center text-muted py-5"><i class="bi bi-calendar-range fs-1 d-block mb-2"></i>Belum ada periode kepengurusan</div></div>
<?php endif; ?>
</div>
<?php if($periodes->hasPages()): ?><div class="mt-4"><?php echo e($periodes->links()); ?></div><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/periode/index.blade.php ENDPATH**/ ?>