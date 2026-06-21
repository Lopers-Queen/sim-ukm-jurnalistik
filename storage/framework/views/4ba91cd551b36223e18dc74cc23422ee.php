<?php $__env->startSection('title', 'Manajemen Keaktifan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Manajemen Keaktifan'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Keaktifan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Keaktifan Anggota</h4>
        <p class="text-muted mb-0">Perpanjangan keaktifan anggota per periode (FR-13)</p>
    </div>
    <?php if($periodeAktif): ?>
    <span class="badge bg-primary fs-6">Periode: <?php echo e($periodeAktif->nama_periode); ?></span>
    <?php endif; ?>
</div>


<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" class="form-control" name="search" id="searchInput" value="<?php echo e(request('search')); ?>" placeholder="Cari NIM, nama...">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') === 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="pasif" <?php echo e(request('status') === 'pasif' ? 'selected' : ''); ?>>Pasif</option>
                    <option value="alumni" <?php echo e(request('status') === 'alumni' ? 'selected' : ''); ?>>Alumni</option>
                </select>
            </div>
            <?php if(request('search') || request('status')): ?>
            <div class="col-md-2">
                <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
let t;document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(t);t=setTimeout(()=>this.form.submit(),500)});
</script>
<?php $__env->stopPush(); ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.edit')): ?>
<form method="POST" action="<?php echo e(route('keaktifan.batch-update')); ?>" x-data="{ selectedIds: [], selectAll: false }" id="batchForm">
    <?php echo csrf_field(); ?>
    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">Daftar Anggota</h6>
            <div class="d-flex gap-2" x-show="selectedIds.length > 0">
                <span class="badge bg-primary" x-text="selectedIds.length + ' dipilih'"></span>
                <select class="form-select form-select-sm" name="status" style="width: 150px;" required>
                    <option value="aktif">Set Aktif</option>
                    <option value="pasif">Set Pasif</option>
                    <option value="alumni">Set Alumni</option>
                </select>
                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Update status anggota yang dipilih?')">
                    <i class="bi bi-check-lg"></i> Update Batch
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" x-model="selectAll" x-on:change="selectedIds = selectAll ? [<?php echo e($anggota->pluck('id')->implode(',')); ?>] : []"></th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="anggota_ids[]" value="<?php echo e($a->id); ?>" x-model="selectedIds" :value="<?php echo e($a->id); ?>">
                            </td>
                            <td class="fw-semibold text-primary"><?php echo e($a->nim); ?></td>
                            <td><?php echo e($a->nama_lengkap); ?></td>
                            <td><?php echo e($a->divisi_label); ?></td>
                            <td><?php echo e($a->jabatan_lengkap); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($a->status_keanggotaan === 'aktif' ? 'success' : ($a->status_keanggotaan === 'pasif' ? 'warning' : 'secondary')); ?>">
                                    <?php echo e(ucfirst($a->status_keanggotaan)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Status</button>
                                    <ul class="dropdown-menu">
                                        <?php $__currentLoopData = ['aktif', 'pasif', 'alumni']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <form method="POST" action="<?php echo e(route('keaktifan.toggle', $a)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="<?php echo e($s); ?>">
                                                <button class="dropdown-item <?php echo e($a->status_keanggotaan === $s ? 'active' : ''); ?>">
                                                    <?php echo e(ucfirst($s)); ?>

                                                </button>
                                            </form>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada anggota ditemukan</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($anggota->links()); ?>

        </div>
    </div>
</form>
<?php else: ?>

<div class="card">
    <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 fw-semibold">Daftar Anggota</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold text-primary"><?php echo e($a->nim); ?></td>
                        <td><?php echo e($a->nama_lengkap); ?></td>
                        <td><?php echo e($a->divisi_label); ?></td>
                        <td><?php echo e($a->jabatan_lengkap); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($a->status_keanggotaan === 'aktif' ? 'success' : ($a->status_keanggotaan === 'pasif' ? 'warning' : 'secondary')); ?>">
                                <?php echo e(ucfirst($a->status_keanggotaan)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada anggota ditemukan</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($anggota->links()); ?>

    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/keaktifan/index.blade.php ENDPATH**/ ?>