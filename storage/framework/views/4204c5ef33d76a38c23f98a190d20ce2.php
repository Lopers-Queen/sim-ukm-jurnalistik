<?php $__env->startSection('title', 'Naskah Redaksi — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Naskah Redaksi'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Naskah Redaksi</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Naskah</h4><p class="text-muted mb-0">Kelola naskah redaksi UKM</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('naskah-redaksi.create')): ?><a href="<?php echo e(route('naskah.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tulis Naskah</a><?php endif; ?>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" action="<?php echo e(route('naskah.index')); ?>" class="row g-3 align-items-center">
        <div class="col-md-6"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput" value="<?php echo e(request('search')); ?>" placeholder="Cari judul naskah..."></div></div>
        <div class="col-md-3"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            <?php $__currentLoopData = ['draft','review','revisi','disetujui','ditolak','published']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php echo e(request('status')==$s?'selected':''); ?>><?php echo e(ucfirst($s)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <?php if(request('search') || request('status')): ?>
        <div class="col-md-3"><a href="<?php echo e(route('naskah.index')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
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
        <thead class="table-light"><tr><th>#</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $naskah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($naskah->firstItem() + $i); ?></td>
            <td class="fw-semibold"><?php echo e($item->judul); ?></td>
            <td><?php echo e($item->penulis?->nama_lengkap ?? '-'); ?></td>
            <td><?php echo e($item->kategori ?? '-'); ?></td>
            <td><span class="badge bg-<?php echo e($item->status_badge); ?>"><?php echo e($item->status_label); ?></span></td>
            <td><div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('naskah.show', $item)); ?>" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('naskah-redaksi.edit')): ?><a href="<?php echo e(route('naskah.edit', $item)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('naskah-redaksi.delete')): ?><form method="POST" action="<?php echo e(route('naskah.destroy', $item)); ?>" class="d-inline" onsubmit="return confirm('Hapus naskah &quot;<?php echo e($item->judul); ?>&quot;?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button></form><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>Belum ada naskah</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($naskah->hasPages()): ?><div class="card-footer"><?php echo e($naskah->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/naskah/index.blade.php ENDPATH**/ ?>