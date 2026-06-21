<?php $__env->startSection('title', 'Detail Surat — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Detail Surat Pernyataan'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('surat-pernyataan.index')); ?>">Surat Pernyataan</a></li>
<li class="breadcrumb-item active">Detail</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Surat Pernyataan #<?php echo e($suratPernyataan->id); ?></h5>
            <?php $badge = match($suratPernyataan->status) {
                'pending_ttd' => 'warning', 'menunggu_konfirmasi' => 'info',
                'disetujui' => 'success', 'ditolak' => 'danger', default => 'secondary'
            }; ?>
            <span class="badge bg-<?php echo e($badge); ?> fs-6"><?php echo e(ucwords(str_replace('_',' ',$suratPernyataan->status))); ?></span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-2"><i class="bi bi-person me-1"></i>Informasi Anggota</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" style="width:120px">Nama</td><td><?php echo e($suratPernyataan->anggota?->nama_lengkap); ?></td></tr>
                        <tr><td class="text-muted">NIM</td><td><?php echo e($suratPernyataan->anggota?->nim); ?></td></tr>
                        <tr><td class="text-muted">Divisi</td><td><?php echo e($suratPernyataan->anggota?->divisi_label); ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-2"><i class="bi bi-calendar-event me-1"></i>Informasi Event</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" style="width:120px">Event</td><td><?php echo e($suratPernyataan->event?->nama_event); ?></td></tr>
                        <tr><td class="text-muted">Tanggal</td><td><?php echo e($suratPernyataan->event?->tanggal_mulai?->format('d/m/Y') ?? '-'); ?></td></tr>
                        <tr><td class="text-muted">Lokasi</td><td><?php echo e($suratPernyataan->event?->lokasi ?? '-'); ?></td></tr>
                    </table>
                </div>
            </div>

            <?php if($suratPernyataan->approver): ?>
            <div class="alert alert-<?php echo e($suratPernyataan->status === 'disetujui' ? 'success' : 'danger'); ?> py-2 small">
                <strong><?php echo e($suratPernyataan->status === 'disetujui' ? 'Disetujui' : 'Ditolak'); ?> oleh:</strong>
                <?php echo e($suratPernyataan->approver->nama_lengkap); ?> pada <?php echo e($suratPernyataan->tanggal_approval?->translatedFormat('d F Y, H:i')); ?>

                <?php if($suratPernyataan->alasan_penolakan): ?><br><strong>Alasan:</strong> <?php echo e($suratPernyataan->alasan_penolakan); ?><?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if($suratPernyataan->status === 'pending_ttd'): ?>
            <div class="card bg-light mb-3"><div class="card-body">
                <h6 class="fw-semibold">Upload Tanda Tangan</h6>
                <form method="POST" action="<?php echo e(route('surat-pernyataan.upload-ttd', $suratPernyataan)); ?>" enctype="multipart/form-data"><?php echo csrf_field(); ?>
                    <div class="input-group"><input type="file" class="form-control" name="file_ttd" accept="image/*" required>
                    <button class="btn btn-primary"><i class="bi bi-upload me-1"></i>Upload</button></div>
                </form>
            </div></div>
            <?php endif; ?>

            
            <?php if($suratPernyataan->file_ttd): ?>
            <div class="mb-3">
                <h6 class="fw-semibold">Tanda Tangan</h6>
                <img src="<?php echo e(asset('storage/' . $suratPernyataan->file_ttd)); ?>" class="border rounded" style="max-height:120px" alt="TTD">
            </div>
            <?php endif; ?>

            
            <?php if($suratPernyataan->status === 'menunggu_konfirmasi'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('surat-pernyataan.approve')): ?>
            <div class="d-flex gap-2 mb-3">
                <form method="POST" action="<?php echo e(route('surat-pernyataan.approve', $suratPernyataan)); ?>"><?php echo csrf_field(); ?>
                    <button class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Setujui</button></form>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-lg me-1"></i>Tolak</button>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <div class="d-flex gap-2 mt-3">
                <?php if($suratPernyataan->file_pdf): ?><a href="<?php echo e(route('surat-pernyataan.download', $suratPernyataan)); ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Download PDF</a><?php endif; ?>
                <a href="<?php echo e(route('surat-pernyataan.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div></div>


<div class="modal fade" id="rejectModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="<?php echo e(route('surat-pernyataan.reject', $suratPernyataan)); ?>"><?php echo csrf_field(); ?>
    <div class="modal-header"><h5 class="modal-title">Tolak Surat Pernyataan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><textarea class="form-control" name="alasan_penolakan" rows="3" placeholder="Alasan penolakan (min 10 karakter)..." required minlength="10"></textarea></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Tolak</button></div>
    </form>
</div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/surat-pernyataan/show.blade.php ENDPATH**/ ?>