<?php $__env->startSection('title', 'Jadwal Piket — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Jadwal Piket'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Jadwal Piket</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Jadwal Piket</h4>
        <p class="text-muted mb-0"><i class="bi bi-geo-alt me-1"></i>Lokasi: Sekretariat UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('jadwal-shift.create')): ?>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateModal">
            <i class="bi bi-shuffle me-1"></i> Generate Acak
        </button>
        <a href="<?php echo e(route('jadwal.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Manual</a>
        <?php endif; ?>
    </div>
</div>


<div class="card mb-4"><div class="card-body">
    <form method="GET" action="<?php echo e(route('jadwal.index')); ?>" class="row g-3 align-items-center">
        <div class="col-md-4"><select class="form-select" name="hari" onchange="this.form.submit()"><option value="">Semua Hari</option>
            <?php $__currentLoopData = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($h); ?>" <?php echo e(request('hari')==$h?'selected':''); ?>><?php echo e(ucfirst($h)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-4"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama anggota..."></div></div>
        <?php if(request('hari') || request('search')): ?>
        <div class="col-md-2"><a href="<?php echo e(route('jadwal.index')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        <?php endif; ?>
    </form>
</div></div>


<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th style="width:30%">Hari</th><th>Nama</th><th style="width:120px">Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $jadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><span class="badge bg-primary"><?php echo e(ucfirst($item->hari)); ?></span></td>
            <td class="fw-semibold"><?php echo e($item->anggota?->nama_lengkap ?? '-'); ?></td>
            <td><div class="btn-group btn-group-sm">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('jadwal-shift.edit')): ?><a href="<?php echo e(route('jadwal.edit', $item)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('jadwal-shift.delete')): ?><form method="POST" action="<?php echo e(route('jadwal.destroy', $item)); ?>" class="d-inline" onsubmit="return confirm('Hapus jadwal piket ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="bi bi-calendar3 fs-1 d-block mb-2"></i>Belum ada jadwal piket.<br>Klik <strong>"Generate Acak"</strong> untuk membuat otomatis.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($jadwal->hasPages()): ?><div class="card-footer"><?php echo e($jadwal->links()); ?></div><?php endif; ?>
</div>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('jadwal-shift.create')): ?>
<div class="modal fade" id="generateModal" aria-labelledby="generateModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <form id="formGenerateAcak" method="POST" action="<?php echo e(route('jadwal.generate-acak')); ?>">
        <?php echo csrf_field(); ?>
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="generateModalLabel"><i class="bi bi-shuffle me-2"></i>Generate Jadwal Piket Acak</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info small py-2 mb-3">
                <i class="bi bi-info-circle me-1"></i>
                Seluruh anggota aktif akan diacak dan <strong>dibagi rata</strong> ke hari-hari yang dipilih.
                Jadwal lama akan diganti.
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Pilih Hari <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap gap-3">
                    <?php $__currentLoopData = ['senin','selasa','rabu','kamis','jumat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="hari[]" value="<?php echo e($h); ?>" id="hari_<?php echo e($h); ?>" checked>
                        <label class="form-check-label" for="hari_<?php echo e($h); ?>"><?php echo e(ucfirst($h)); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = ['sabtu','minggu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="hari[]" value="<?php echo e($h); ?>" id="hari_<?php echo e($h); ?>">
                        <label class="form-check-label" for="hari_<?php echo e($h); ?>"><?php echo e(ucfirst($h)); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="form-text mt-2">Contoh: 20 anggota ÷ 5 hari = 4 orang/hari</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success" id="btnGenerateSubmit">
                <i class="bi bi-shuffle me-1"></i> Generate Sekarang
            </button>
        </div>
    </form>
</div>
</div>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btnGenerateSubmit');
    if (btn) {
        btn.addEventListener('click', function() {
            document.getElementById('formGenerateAcak').submit();
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/jadwal/index.blade.php ENDPATH**/ ?>