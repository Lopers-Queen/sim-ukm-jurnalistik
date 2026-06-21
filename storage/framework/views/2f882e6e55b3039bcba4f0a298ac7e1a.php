<?php $__env->startSection('title', 'Pergantian Kepengurusan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Pergantian Kepengurusan'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Pergantian</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Pergantian Kepengurusan</h4>
        <p class="text-muted mb-0">Proses transisi periode kepengurusan tahunan (FR-17)</p>
    </div>
</div>

<?php if($periodeAktif): ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    Periode aktif saat ini: <strong><?php echo e($periodeAktif->nama_periode); ?></strong>
    (<?php echo e($periodeAktif->tanggal_mulai?->format('d/m/Y')); ?> — <?php echo e($periodeAktif->tanggal_selesai?->format('d/m/Y')); ?>)
</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('pergantian.store')); ?>" x-data="pergantianForm()">
    <?php echo csrf_field(); ?>

    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-1-circle me-2"></i>Data Periode Baru</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Periode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_periode" value="<?php echo e(old('nama_periode')); ?>" placeholder="Contoh: 2026/2027" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai')); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai')); ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Deskripsi / Catatan</label>
                    <input type="text" class="form-control" name="deskripsi" value="<?php echo e(old('deskripsi')); ?>" placeholder="Opsional">
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-2-circle me-2"></i>Susunan Badan Pengurus Inti (BPI)</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__currentLoopData = [
                    'ketua_umum' => 'Ketua Umum (Hasil MUBES)',
                    'wakil_ketua_umum' => 'Wakil Ketua Umum (Hasil MUBES)',
                    'sekretaris_umum_1' => 'Sekretaris Umum 1',
                    'sekretaris_umum_2' => 'Sekretaris Umum 2',
                    'bendahara_umum_1' => 'Bendahara Umum 1',
                    'bendahara_umum_2' => 'Bendahara Umum 2',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?php echo e($label); ?> <span class="text-danger">*</span></label>
                    <select class="form-select select-search" name="<?php echo e($key); ?>" x-on:change="checkEligibility('<?php echo e($key); ?>', $event.target.value)" required>
                        <option value="">— Pilih Anggota —</option>
                        <?php $__currentLoopData = $anggotaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($anggota->id); ?>" <?php echo e(old($key) == $anggota->id ? 'selected' : ''); ?>>
                                <?php echo e($anggota->nama_lengkap); ?> (<?php echo e($anggota->nim); ?>) — <?php echo e($anggota->masaKeanggotaan()); ?> thn
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    <div x-show="validationResults['<?php echo e($key); ?>']" class="mt-1">
                        <template x-if="validationResults['<?php echo e($key); ?>']?.eligible">
                            <small class="text-success"><i class="bi bi-check-circle"></i> <span x-text="validationResults['<?php echo e($key); ?>']?.reason"></span></small>
                        </template>
                        <template x-if="validationResults['<?php echo e($key); ?>'] && !validationResults['<?php echo e($key); ?>']?.eligible">
                            <div>
                                <small class="text-danger"><i class="bi bi-x-circle"></i> <span x-text="validationResults['<?php echo e($key); ?>']?.reason"></span></small>
                                <div class="mt-1">
                                    <label class="form-label small text-warning fw-semibold">Alasan Override (min. 50 karakter):</label>
                                    <textarea class="form-control form-control-sm" name="override_reasons[<?php echo e($key); ?>]" rows="2" minlength="50" placeholder="Wajib diisi jika ingin melanjutkan pengangkatan..."></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-3-circle me-2"></i>Kepala Divisi</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__currentLoopData = [
                    'kadiv_fotografi' => 'Kepala Divisi Fotografi',
                    'kadiv_pers_penyiaran' => 'Kepala Divisi Pers & Penyiaran',
                    'kadiv_videografi' => 'Kepala Divisi Videografi',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?php echo e($label); ?> <span class="text-danger">*</span></label>
                    <select class="form-select select-search" name="<?php echo e($key); ?>" x-on:change="checkEligibility('<?php echo e($key); ?>', $event.target.value)" required>
                        <option value="">— Pilih Anggota —</option>
                        <?php $__currentLoopData = $anggotaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($anggota->id); ?>" <?php echo e(old($key) == $anggota->id ? 'selected' : ''); ?>>
                                <?php echo e($anggota->nama_lengkap); ?> (<?php echo e($anggota->nim); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div x-show="validationResults['<?php echo e($key); ?>']" class="mt-1">
                        <template x-if="validationResults['<?php echo e($key); ?>']?.eligible">
                            <small class="text-success"><i class="bi bi-check-circle"></i> Layak</small>
                        </template>
                        <template x-if="validationResults['<?php echo e($key); ?>'] && !validationResults['<?php echo e($key); ?>']?.eligible">
                            <div>
                                <small class="text-danger"><i class="bi bi-x-circle"></i> <span x-text="validationResults['<?php echo e($key); ?>']?.reason"></span></small>
                                <textarea class="form-control form-control-sm mt-1" name="override_reasons[<?php echo e($key); ?>]" rows="2" minlength="50" placeholder="Alasan override (min. 50 karakter)..."></textarea>
                            </div>
                        </template>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-4-circle me-2"></i>Kepala Unit</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__currentLoopData = [
                    'kanit_kominfo' => 'Kepala Unit Kominfo',
                    'kanit_redaksi' => 'Kepala Unit Redaksi',
                    'kanit_inventory' => 'Kepala Unit Inventory',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?php echo e($label); ?> <span class="text-danger">*</span></label>
                    <select class="form-select select-search" name="<?php echo e($key); ?>" x-on:change="checkEligibility('<?php echo e($key); ?>', $event.target.value)" required>
                        <option value="">— Pilih Anggota —</option>
                        <?php $__currentLoopData = $anggotaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($anggota->id); ?>" <?php echo e(old($key) == $anggota->id ? 'selected' : ''); ?>>
                                <?php echo e($anggota->nama_lengkap); ?> (<?php echo e($anggota->nim); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div x-show="validationResults['<?php echo e($key); ?>']" class="mt-1">
                        <template x-if="validationResults['<?php echo e($key); ?>']?.eligible">
                            <small class="text-success"><i class="bi bi-check-circle"></i> Layak</small>
                        </template>
                        <template x-if="validationResults['<?php echo e($key); ?>'] && !validationResults['<?php echo e($key); ?>']?.eligible">
                            <div>
                                <small class="text-danger"><i class="bi bi-x-circle"></i> <span x-text="validationResults['<?php echo e($key); ?>']?.reason"></span></small>
                                <textarea class="form-control form-control-sm mt-1" name="override_reasons[<?php echo e($key); ?>]" rows="2" minlength="50" placeholder="Alasan override (min. 50 karakter)..."></textarea>
                            </div>
                        </template>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="<?php echo e(route('periode.index')); ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Apakah Anda yakin ingin memfinalisasi pergantian kepengurusan? Tindakan ini tidak dapat dibatalkan.')">
            <i class="bi bi-check-circle me-1"></i> Finalisasi Pergantian Kepengurusan
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function pergantianForm() {
    return {
        validationResults: {},
        async checkEligibility(jabatan, anggotaId) {
            if (!anggotaId) {
                delete this.validationResults[jabatan];
                return;
            }
            try {
                const response = await fetch('<?php echo e(route("pergantian.validate")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ susunan: { [jabatan]: anggotaId } }),
                });
                const data = await response.json();
                if (data.results && data.results[jabatan]) {
                    this.validationResults[jabatan] = data.results[jabatan];
                }
            } catch (e) {
                console.error('Validation error:', e);
            }
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/pergantian/index.blade.php ENDPATH**/ ?>