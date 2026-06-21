<?php $__env->startSection('title', 'Detail Anggota — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Detail Anggota'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('anggota.index')); ?>">Anggota</a></li>
<li class="breadcrumb-item active">Detail</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-3">
                    <?php echo e(strtoupper(substr($anggota->nama_lengkap, 0, 2))); ?>

                </div>
                <h5 class="fw-bold"><?php echo e($anggota->nama_lengkap); ?></h5>
                <p class="text-muted mb-1"><?php echo e($anggota->jabatan_lengkap); ?></p>
                <span class="badge bg-<?php echo e($anggota->status_keanggotaan == 'aktif' ? 'success' : ($anggota->status_keanggotaan == 'pasif' ? 'warning' : 'secondary')); ?>">
                    <?php echo e(ucfirst($anggota->status_keanggotaan)); ?>

                </span>
                <hr>
                <div class="text-start small">
                    <p><i class="bi bi-credit-card me-2"></i><strong>NIM:</strong> <?php echo e($anggota->nim); ?></p>
                    <p><i class="bi bi-envelope me-2"></i><strong>Email:</strong> <?php echo e($anggota->email); ?></p>
                    <p><i class="bi bi-building me-2"></i><strong>Divisi:</strong> <?php echo e($anggota->divisi_label); ?></p>
                    <p><i class="bi bi-telephone me-2"></i><strong>No HP:</strong> <?php echo e($anggota->no_hp ?? '-'); ?></p>
                    <p><i class="bi bi-calendar me-2"></i><strong>Bergabung:</strong> <?php echo e($anggota->tanggal_bergabung?->translatedFormat('d F Y') ?? '-'); ?></p>
                    <p><i class="bi bi-clock me-2"></i><strong>Masa Keanggotaan:</strong> <?php echo e($anggota->masaKeanggotaan()); ?> tahun</p>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.reset-password-anggota')): ?>
                <form method="POST" action="<?php echo e(route('anggota.reset-password', $anggota)); ?>"
                      onsubmit="return confirm('Reset password <?php echo e($anggota->nama_lengkap); ?> ke default?')">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-warning btn-sm w-100 mt-2">
                        <i class="bi bi-key me-1"></i> Reset Password
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent p-0">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-riwayat-jabatan" type="button">
                            <i class="bi bi-person-badge me-1"></i> Riwayat Jabatan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-riwayat-panitia" type="button">
                            <i class="bi bi-people me-1"></i> Riwayat Kepanitiaan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-login-log" type="button">
                            <i class="bi bi-clock-history me-1"></i> Log Login
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    
                    <div class="tab-pane fade show active" id="tab-riwayat-jabatan">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Periode</th>
                                        <th>Jabatan</th>
                                        <th>Divisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $anggota->riwayatKepengurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $riwayat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($riwayat->periode->nama_periode ?? '-'); ?></td>
                                        <td><span class="fw-semibold"><?php echo e($riwayat->jabatan_label); ?></span></td>
                                        <td><?php echo e($riwayat->divisi ?? '-'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($riwayat->status === 'aktif' ? 'success' : 'secondary'); ?>">
                                                <?php echo e(ucfirst($riwayat->status ?? 'selesai')); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat kepengurusan</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-riwayat-panitia">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Divisi Panitia</th>
                                        <th>Jabatan</th>
                                        <th>Tanggal</th>
                                        <th>Status Event</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $anggota->kepanitiaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $panitia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('event.show', $panitia->event_id)); ?>" class="text-decoration-none fw-semibold">
                                                <?php echo e($panitia->event->nama_event ?? '-'); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($panitia->divisiPanitia->nama_divisi ?? '-'); ?></td>
                                        <td><span class="badge bg-info text-dark"><?php echo e($panitia->jabatan_panitia); ?></span></td>
                                        <td class="small"><?php echo e($panitia->event->tanggal_mulai?->format('d/m/Y') ?? '-'); ?></td>
                                        <td>
                                            <?php $eventStatus = $panitia->event->status ?? 'draft'; ?>
                                            <span class="badge bg-<?php echo e(match($eventStatus) {
                                                    'selesai' => 'success',
                                                    'aktif' => 'primary',
                                                    'direncanakan' => 'warning',
                                                    'batal' => 'danger',
                                                    default => 'secondary'
                                                }); ?>"><?php echo e(ucfirst($eventStatus)); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Belum pernah menjadi panitia</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-login-log">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $anggota->loginHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($log->attempted_at?->format('d/m/Y H:i')); ?></td>
                                        <td><code><?php echo e($log->ip_address); ?></code></td>
                                        <td class="small"><?php echo e(Str::limit($log->user_agent, 35)); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($log->status === 'success' ? 'success' : 'danger'); ?>">
                                                <?php echo e($log->status === 'success' ? 'Berhasil' : ucfirst($log->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat login</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.edit')): ?>
            <a href="<?php echo e(route('anggota.edit', $anggota)); ?>" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            <?php endif; ?>
            <a href="<?php echo e(route('anggota.index')); ?>" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/anggota/show.blade.php ENDPATH**/ ?>