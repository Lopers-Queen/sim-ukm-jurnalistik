<?php $__env->startSection('title', 'Surat Pernyataan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Surat Pernyataan'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Surat Pernyataan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Surat Pernyataan</h4><p class="text-muted mb-0">Kelola surat pernyataan anggota</p></div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('surat-pernyataan.generate')): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal"><i class="bi bi-plus-lg me-1"></i> Generate Surat</button>
    <?php endif; ?>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Anggota</th><th>Event</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $suratList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($suratList->firstItem() + $i); ?></td>
            <td class="fw-semibold"><?php echo e($surat->anggota?->nama_lengkap ?? '-'); ?></td>
            <td><?php echo e($surat->event?->nama_event ?? '-'); ?></td>
            <td>
                <?php $badge = match($surat->status) {
                    'pending_ttd' => 'warning', 'menunggu_konfirmasi' => 'info',
                    'disetujui' => 'success', 'ditolak' => 'danger', default => 'secondary'
                }; ?>
                <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucwords(str_replace('_',' ',$surat->status))); ?></span>
            </td>
            <td class="small"><?php echo e($surat->created_at->format('d/m/Y H:i')); ?></td>
            <td><div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('surat-pernyataan.show', $surat)); ?>" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                <?php if($surat->file_pdf): ?><a href="<?php echo e(route('surat-pernyataan.download', $surat)); ?>" class="btn btn-outline-danger"><i class="bi bi-filetype-pdf"></i></a><?php endif; ?>
            </div></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-file-earmark-check fs-1 d-block mb-2"></i>Belum ada surat pernyataan</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($suratList->hasPages()): ?><div class="card-footer"><?php echo e($suratList->links()); ?></div><?php endif; ?>
</div>


<div class="modal fade" id="generateModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="<?php echo e(route('surat-pernyataan.generate')); ?>"><?php echo csrf_field(); ?>
    <div class="modal-header"><h5 class="modal-title">Generate Surat Pernyataan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-semibold">Anggota <span class="text-danger">*</span></label>
            <select class="form-select select-search" name="anggota_id" required>
                <option value="">-- Pilih Anggota --</option>
                <?php $__currentLoopData = \App\Models\Anggota::where('status_keanggotaan','aktif')->where('jabatan_struktural','!=','admin')->orderBy('nama_lengkap')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($a->id); ?>"><?php echo e($a->nama_lengkap); ?> (<?php echo e($a->nim); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3"><label class="form-label fw-semibold">Event <span class="text-danger">*</span></label>
            <select class="form-select" name="event_id" required>
                <option value="">-- Pilih Event --</option>
                <?php $__currentLoopData = \App\Models\Event::latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($e->id); ?>"><?php echo e($e->nama_event); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Generate</button></div>
    </form>
</div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/surat-pernyataan/index.blade.php ENDPATH**/ ?>