<?php $__env->startSection('title', 'Anggaran Divisi — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Anggaran Divisi'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Anggaran Divisi</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Anggaran Divisi</h4><p class="text-muted mb-0">Kelola anggaran per divisi/bulan</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-divisi.create')): ?><a href="<?php echo e(route('anggaran-divisi.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Anggaran</a><?php endif; ?>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-4"><select class="form-select" name="divisi" onchange="this.form.submit()"><option value="">Semua Divisi</option>
            <?php $__currentLoopData = ['fotografi','pers_penyiaran','videografi','kominfo','redaksi','inventory','bpi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d); ?>" <?php echo e(request('divisi')==$d?'selected':''); ?>><?php echo e(ucfirst(str_replace('_',' ',$d))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-4"><select class="form-select" name="periode_id" onchange="this.form.submit()"><option value="">Semua Periode</option>
            <?php $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p->id); ?>" <?php echo e(request('periode_id')==$p->id?'selected':''); ?>><?php echo e($p->nama_periode); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <?php if(request('divisi') || request('periode_id')): ?>
        <div class="col-md-2"><a href="<?php echo e(route('anggaran-divisi.index')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        <?php endif; ?>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Divisi</th><th>Bulan</th><th>Anggaran</th><th>Terpakai</th><th>Sisa</th><th>%</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $anggaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($anggaran->firstItem() + $i); ?></td>
            <td><?php echo e(ucfirst(str_replace('_',' ',$item->divisi))); ?></td>
            <td class="fw-semibold"><?php echo e($item->nama_bulan); ?> <?php echo e($item->tahun); ?></td>
            <td>Rp <?php echo e(number_format($item->jumlah_anggaran,0,',','.')); ?></td>
            <td>Rp <?php echo e(number_format($item->jumlah_terpakai,0,',','.')); ?></td>
            <td class="<?php echo e($item->sisa_anggaran < 0 ? 'text-danger' : 'text-success'); ?>">Rp <?php echo e(number_format($item->sisa_anggaran,0,',','.')); ?></td>
            <td><div class="progress" style="width:60px;height:6px"><div class="progress-bar <?php echo e($item->persentase_terpakai > 90 ? 'bg-danger' : ($item->persentase_terpakai > 70 ? 'bg-warning' : 'bg-success')); ?>" style="width:<?php echo e(min($item->persentase_terpakai, 100)); ?>%"></div></div>
                <small class="text-muted"><?php echo e($item->persentase_terpakai); ?>%</small></td>
            <td><div class="btn-group btn-group-sm">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-divisi.edit')): ?><a href="<?php echo e(route('anggaran-divisi.edit', $item)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-divisi.delete')): ?><form method="POST" action="<?php echo e(route('anggaran-divisi.destroy', $item)); ?>" onsubmit="return confirm('Hapus?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-wallet2 fs-1 d-block mb-2"></i>Belum ada data anggaran</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($anggaran->hasPages()): ?><div class="card-footer"><?php echo e($anggaran->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/anggaran-divisi/index.blade.php ENDPATH**/ ?>