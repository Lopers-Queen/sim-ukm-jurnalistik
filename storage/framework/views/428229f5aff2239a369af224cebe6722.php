<?php $__env->startSection('title', 'Notulensi — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Notulensi Rapat'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Notulensi</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Notulensi</h4><p class="text-muted mb-0">Catatan hasil rapat UKM</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notulensi.create')): ?><a href="<?php echo e(route('notulensi.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Notulensi</a><?php endif; ?>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Judul</th><th>Tanggal</th><th>Jenis</th><th>Pencatat</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $notulensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($notulensi->firstItem() + $i); ?></td>
            <td class="fw-semibold"><?php echo e($item->judul); ?></td>
            <td><?php echo e($item->tanggal_rapat->format('d/m/Y')); ?></td>
            <td><span class="badge bg-info"><?php echo e($item->jenis_rapat_label); ?></span></td>
            <td><?php echo e($item->pencatat?->nama_lengkap ?? '-'); ?></td>
            <td><div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('notulensi.show', $item)); ?>" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notulensi.edit')): ?><a href="<?php echo e(route('notulensi.edit', $item)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notulensi.delete')): ?><form method="POST" action="<?php echo e(route('notulensi.destroy', $item)); ?>" class="d-inline" onsubmit="return confirm('Hapus notulensi ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button></form><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-journal-text fs-1 d-block mb-2"></i>Belum ada notulensi</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($notulensi->hasPages()): ?><div class="card-footer"><?php echo e($notulensi->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/notulensi/index.blade.php ENDPATH**/ ?>