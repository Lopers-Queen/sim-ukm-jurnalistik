<?php $__env->startSection('title', 'Laporan Anggota — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Laporan Data Anggota'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Laporan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Laporan Anggota</h4><p class="text-muted mb-0">Preview data anggota sebelum export</p></div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('laporan.anggota.pdf', request()->query())); ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Download PDF</a>
        <a href="<?php echo e(route('laporan.anggota.excel', request()->query())); ?>" class="btn btn-success btn-sm"><i class="bi bi-filetype-xlsx me-1"></i>Download Excel</a>
        <a href="<?php echo e(route('laporan.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-4"><select class="form-select" name="divisi" onchange="this.form.submit()"><option value="">Semua Divisi</option>
            <?php $__currentLoopData = ['fotografi','pers_penyiaran','videografi','kominfo','redaksi','inventory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d); ?>" <?php echo e(request('divisi')==$d?'selected':''); ?>><?php echo e(ucfirst(str_replace('_',' ',$d))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-4"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            <?php $__currentLoopData = ['aktif','pasif','alumni']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php echo e(request('status')==$s?'selected':''); ?>><?php echo e(ucfirst($s)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <?php if(request('divisi') || request('status')): ?>
        <div class="col-md-4"><a href="<?php echo e(route('laporan.anggota')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        <?php endif; ?>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-striped table-sm align-middle mb-0">
        <thead class="table-dark"><tr><th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Email</th><th>Divisi</th><th>Jabatan</th><th>Status</th><th>Tgl Bergabung</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $anggotaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($i + 1); ?></td><td><?php echo e($a->nim); ?></td><td><?php echo e($a->nama_lengkap); ?></td><td><?php echo e($a->email); ?></td>
            <td><?php echo e($a->divisi_label); ?></td><td><?php echo e($a->jabatan_lengkap); ?></td>
            <td><span class="badge bg-<?php echo e($a->status_keanggotaan == 'aktif' ? 'success' : 'secondary'); ?>"><?php echo e(ucfirst($a->status_keanggotaan)); ?></span></td>
            <td><?php echo e($a->tanggal_bergabung?->format('d/m/Y') ?? '-'); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="8" class="text-center py-3 text-muted">Tidak ada data</td></tr><?php endif; ?>
        </tbody>
    </table>
</div></div></div>
<div class="text-muted small mt-2"><i class="bi bi-info-circle me-1"></i>Total: <?php echo e($anggotaList->count()); ?> anggota</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/laporan/anggota.blade.php ENDPATH**/ ?>