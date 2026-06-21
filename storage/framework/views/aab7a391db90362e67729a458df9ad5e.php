<?php $__env->startSection('title', 'Rekrutmen — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Rekrutmen Anggota'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Rekrutmen</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Rekrutmen</h4><p class="text-muted mb-0">Kelola rekrutmen anggota baru</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rekrutmen.create')): ?><a href="<?php echo e(route('rekrutmen.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Rekrutmen</a><?php endif; ?>
</div>
<div class="row g-4">
<?php $__empty_1 = true; $__currentLoopData = $rekrutmen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="col-md-6">
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold mb-0"><?php echo e($item->nama_rekrutmen); ?></h5>
                <?php $badge = match($item->status) { 'dibuka' => 'success', 'draft' => 'secondary', 'ditutup' => 'warning', default => 'info' }; ?>
                <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($item->status)); ?></span>
            </div>
            <p class="text-muted small mb-2">Periode: <?php echo e($item->periode?->nama_periode ?? '-'); ?></p>
            <p class="small"><i class="bi bi-calendar me-1"></i><?php echo e($item->tanggal_buka->format('d/m/Y')); ?> - <?php echo e($item->tanggal_tutup->format('d/m/Y')); ?></p>
            <p class="small text-muted"><?php echo e(Str::limit($item->deskripsi, 100)); ?></p>
            <div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('rekrutmen.show', $item)); ?>" class="btn btn-outline-info"><i class="bi bi-eye me-1"></i>Detail</a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rekrutmen.edit')): ?><a href="<?php echo e(route('rekrutmen.edit', $item)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rekrutmen.delete')): ?><form method="POST" action="<?php echo e(route('rekrutmen.destroy', $item)); ?>" class="d-inline" onsubmit="return confirm('Hapus rekrutmen &quot;<?php echo e($item->nama_rekrutmen); ?>&quot;?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button></form><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="col-12"><div class="text-center text-muted py-5"><i class="bi bi-person-plus fs-1 d-block mb-2"></i>Belum ada rekrutmen</div></div>
<?php endif; ?>
</div>
<?php if($rekrutmen->hasPages()): ?><div class="mt-4"><?php echo e($rekrutmen->links()); ?></div><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/rekrutmen/index.blade.php ENDPATH**/ ?>