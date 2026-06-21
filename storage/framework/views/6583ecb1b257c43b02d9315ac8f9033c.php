<?php $__env->startSection('title', 'Daftar Anggota — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Manajemen Anggota'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Anggota</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Anggota</h4>
        <p class="text-muted mb-0">Kelola data anggota UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'super_admin')): ?>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetAllModal">
            <i class="bi bi-arrow-repeat me-1"></i> Reset Semua Password
        </button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.create')): ?>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
        <a href="<?php echo e(route('anggota.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
        </a>
        <?php endif; ?>
    </div>
</div>


<?php if(session('import_errors') && count(session('import_errors')) > 0): ?>
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <h6 class="alert-heading fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>Detail Import</h6>
    <div class="small" style="max-height: 200px; overflow-y: auto;">
        <ul class="mb-0 ps-3">
            <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($err); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.create')): ?>
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-file-earmark-excel me-2 text-success"></i>Import Anggota dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('anggota.import')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    
                    <div class="alert alert-info small mb-3">
                        <div class="fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i>Aturan Import</div>
                        <ul class="mb-0 ps-3">
                            <li>Download template terlebih dahulu</li>
                            <li>Kolom <strong>wajib</strong>: NIM, Nama Lengkap, Email, Tanggal Lahir, Jenis Kelamin</li>
                            <li>Format tanggal lahir: <code>DD/MM/YYYY</code> (contoh: 15/05/2004)</li>
                            <li>Jenis kelamin: <code>L</code> (Laki-laki) atau <code>P</code> (Perempuan)</li>
                            <li>Password otomatis = tanggal lahir format <code>DDMMYYYY</code></li>
                            <li>NIM & email yang sudah ada akan dilewati</li>
                            <li>Format: <code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code> (maks 5MB)</li>
                        </ul>
                    </div>

                    
                    <div class="mb-3">
                        <a href="<?php echo e(route('anggota.template')); ?>" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-download me-1"></i>Download Template Excel
                        </a>
                    </div>

                    
                    <div class="mb-3">
                        <label for="file_import" class="form-label fw-semibold">Pilih File</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['file_import'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               name="file_import" id="file_import"
                               accept=".xlsx,.xls,.csv" required>
                        <?php $__errorArgs = ['file_import'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Format: .xlsx, .xls, .csv — Maks 5MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i>Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="<?php echo e(route('anggota.index')); ?>" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" id="searchInput"
                           value="<?php echo e(request('search')); ?>" placeholder="Cari NIM, nama, email...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="divisi" onchange="this.form.submit()">
                    <option value="">Semua Divisi</option>
                    <option value="fotografi" <?php echo e(request('divisi') == 'fotografi' ? 'selected' : ''); ?>>Fotografi</option>
                    <option value="pers_penyiaran" <?php echo e(request('divisi') == 'pers_penyiaran' ? 'selected' : ''); ?>>Pers & Penyiaran</option>
                    <option value="videografi" <?php echo e(request('divisi') == 'videografi' ? 'selected' : ''); ?>>Videografi</option>
                    <option value="kominfo" <?php echo e(request('divisi') == 'kominfo' ? 'selected' : ''); ?>>Kominfo</option>
                    <option value="redaksi" <?php echo e(request('divisi') == 'redaksi' ? 'selected' : ''); ?>>Redaksi</option>
                    <option value="inventory" <?php echo e(request('divisi') == 'inventory' ? 'selected' : ''); ?>>Inventory</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="pasif" <?php echo e(request('status') == 'pasif' ? 'selected' : ''); ?>>Pasif</option>
                    <option value="alumni" <?php echo e(request('status') == 'alumni' ? 'selected' : ''); ?>>Alumni</option>
                </select>
            </div>
            <?php if(request('search') || request('divisi') || request('status')): ?>
            <div class="col-md-2">
                <a href="<?php echo e(route('anggota.index')); ?>" class="btn btn-outline-secondary w-100 btn-sm">
                    <i class="bi bi-x-lg me-1"></i> Reset
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-submit search setelah user berhenti mengetik 500ms
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => this.form.submit(), 500);
    });
</script>
<?php $__env->stopPush(); ?>


<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive table-mobile-cards">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="#" class="text-muted"><?php echo e($anggota->firstItem() + $index); ?></td>
                        <td data-label="NIM"><code><?php echo e($item->nim); ?></code></td>
                        <td data-label="Nama">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <?php echo e(strtoupper(substr($item->nama_lengkap, 0, 2))); ?>

                                </div>
                                <div>
                                    <div class="fw-semibold"><?php echo e($item->nama_lengkap); ?></div>
                                    <div class="small text-muted"><?php echo e($item->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Divisi"><?php echo e($item->divisi_label); ?></td>
                        <td data-label="Jabatan"><span class="small"><?php echo e($item->jabatan_lengkap); ?></span></td>
                        <td data-label="Status">
                            <?php
                                $statusBadge = match($item->status_keanggotaan) {
                                    'aktif' => 'success',
                                    'pasif' => 'warning',
                                    'alumni' => 'secondary',
                                    default => 'secondary',
                                };
                            ?>
                            <span class="badge bg-<?php echo e($statusBadge); ?>"><?php echo e(ucfirst($item->status_keanggotaan)); ?></span>
                        </td>
                        <td data-label="Aksi">
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('anggota.show', $item)); ?>" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.edit')): ?>
                                <a href="<?php echo e(route('anggota.edit', $item)); ?>" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'super_admin')): ?>
                                <button type="button" class="btn btn-outline-warning" title="Reset Password"
                                        data-bs-toggle="modal" data-bs-target="#resetSingleModal-<?php echo e($item->id); ?>">
                                    <i class="bi bi-key"></i>
                                </button>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.delete')): ?>
                                <form method="POST" action="<?php echo e(route('anggota.destroy', $item)); ?>"
                                      onsubmit="return confirm('Hapus anggota <?php echo e($item->nama_lengkap); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Belum ada data anggota
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($anggota->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($anggota->links()); ?>

    </div>
    <?php endif; ?>
</div>


<?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'super_admin')): ?>
<div class="modal fade" id="resetAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-arrow-repeat me-2"></i>Reset Semua Password Anggota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('anggota.reset-all-passwords')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Perhatian:</strong> Semua password anggota (kecuali admin) akan direset.
                        Anggota akan diminta mengganti password saat login berikutnya.
                    </div>
                    <div class="mb-3">
                        <label for="bulk_password" class="form-label fw-semibold small">Password Baru</label>
                        <input type="text" class="form-control" id="bulk_password" name="password"
                               value="12345678" placeholder="Kosongkan untuk default: 12345678">
                        <div class="form-text">Kosongkan untuk menggunakan password default: <code>12345678</code></div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmBulk" required>
                        <label class="form-check-label small" for="confirmBulk">
                            Saya yakin ingin mereset <strong>semua password anggota</strong>.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset Semua Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="resetSingleModal-<?php echo e($item->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-key me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('anggota.reset-password', $item)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Reset password untuk: <strong><?php echo e($item->nama_lengkap); ?></strong><br>
                        <code class="small"><?php echo e($item->nim); ?></code>
                    </p>
                    <div class="mb-3">
                        <label for="single_password_<?php echo e($item->id); ?>" class="form-label fw-semibold small">Password Baru</label>
                        <input type="text" class="form-control" id="single_password_<?php echo e($item->id); ?>" name="password"
                               value="12345678" placeholder="Kosongkan untuk default: 12345678">
                        <div class="form-text">Kosongkan untuk default: <code>12345678</code></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold btn-sm">
                        <i class="bi bi-key me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/anggota/index.blade.php ENDPATH**/ ?>