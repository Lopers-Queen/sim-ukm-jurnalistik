<?php $__env->startSection('title', 'Anggaran Event — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Anggaran Event'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Anggaran Event</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Anggaran Event</h4><p class="text-muted mb-0">Kelola anggaran per event</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-event.create')): ?><a href="<?php echo e(route('anggaran-event.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Item</a><?php endif; ?>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-8"><select class="form-select" name="event_id" onchange="this.form.submit()"><option value="">Semua Event</option>
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($e->id); ?>" <?php echo e(request('event_id')==$e->id?'selected':''); ?>><?php echo e($e->nama_event); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <?php if(request('event_id')): ?>
        <div class="col-md-4"><a href="<?php echo e(route('anggaran-event.index')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        <?php endif; ?>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Event</th><th>Item</th><th>Qty</th><th>Harga Satuan</th><th>Dianggarkan</th><th>Realisasi</th><th>Selisih</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $anggaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($anggaran->firstItem() + $i); ?></td>
            <td class="small"><?php echo e($item->event?->nama_event ?? '-'); ?></td>
            <td class="fw-semibold"><?php echo e($item->item); ?></td>
            <td><?php echo e($item->qty); ?></td>
            <td>Rp <?php echo e(number_format($item->harga_satuan,0,',','.')); ?></td>
            <td>Rp <?php echo e(number_format($item->jumlah_dianggarkan,0,',','.')); ?></td>
            <td>Rp <?php echo e(number_format($item->jumlah_realisasi ?? 0,0,',','.')); ?></td>
            <td class="<?php echo e(($item->selisih ?? 0) >= 0 ? 'text-success' : 'text-danger'); ?>">Rp <?php echo e(number_format($item->selisih ?? $item->jumlah_dianggarkan,0,',','.')); ?></td>
            <td><div class="btn-group btn-group-sm">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-event.edit')): ?><a href="<?php echo e(route('anggaran-event.edit', $item)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-event.delete')): ?><form method="POST" action="<?php echo e(route('anggaran-event.destroy', $item)); ?>" onsubmit="return confirm('Hapus?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-cash-stack fs-1 d-block mb-2"></i>Belum ada data anggaran event</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($anggaran->hasPages()): ?><div class="card-footer"><?php echo e($anggaran->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/anggaran-event/index.blade.php ENDPATH**/ ?>