<?php $__env->startSection('title', 'Manajemen Event — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Manajemen Event'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Event</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Event</h4><p class="text-muted mb-0">Kelola event dan kegiatan UKM</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event.create')): ?>
    <a href="<?php echo e(route('event.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Event</a>
    <?php endif; ?>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" action="<?php echo e(route('event.index')); ?>" class="row g-3 align-items-center">
        <div class="col-md-6"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput" value="<?php echo e(request('search')); ?>" placeholder="Cari nama event..."></div></div>
        <div class="col-md-3"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            <?php $__currentLoopData = ['draft','direncanakan','aktif','selesai','batal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php echo e(request('status')==$s?'selected':''); ?>><?php echo e(ucfirst($s)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <?php if(request('search') || request('status')): ?>
        <div class="col-md-3"><a href="<?php echo e(route('event.index')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        <?php endif; ?>
    </form>
</div></div>
<?php $__env->startPush('scripts'); ?>
<script>
let t;document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(t);t=setTimeout(()=>this.form.submit(),500)});
</script>
<?php $__env->stopPush(); ?>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Nama Event</th><th>Tanggal</th><th>Lokasi</th><th>PIC</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($events->firstItem() + $i); ?></td>
            <td><div class="fw-semibold"><?php echo e($event->nama_event); ?></div><div class="small text-muted"><?php echo e(Str::limit($event->deskripsi, 50)); ?></div></td>
            <td class="small"><?php echo e($event->tanggal_mulai->format('d/m/Y')); ?><?php echo e($event->tanggal_selesai ? ' - '.$event->tanggal_selesai->format('d/m/Y') : ''); ?></td>
            <td><?php echo e($event->lokasi ?? '-'); ?></td>
            <td><?php echo e($event->pic?->nama_lengkap ?? '-'); ?></td>
            <td><span class="badge bg-<?php echo e($event->status_badge); ?>"><?php echo e($event->status_label); ?></span></td>
            <td><div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('event.show', $event)); ?>" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event.edit')): ?><a href="<?php echo e(route('event.edit', $event)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event.delete')): ?><form method="POST" action="<?php echo e(route('event.destroy', $event)); ?>" onsubmit="return confirm('Hapus event ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-1 d-block mb-2"></i>Belum ada event</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($events->hasPages()): ?><div class="card-footer"><?php echo e($events->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/event/index.blade.php ENDPATH**/ ?>