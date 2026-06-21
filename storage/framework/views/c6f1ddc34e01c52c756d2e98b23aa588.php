<?php $__env->startSection('title', 'Profil Saya — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Profil Saya'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Profil Saya</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                
                <div class="position-relative d-inline-block mb-3">
                    <?php if($anggota->foto_profil_url): ?>
                        <img src="<?php echo e($anggota->foto_profil_url); ?>" alt="Foto Profil"
                             class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <div class="avatar avatar-lg mx-auto">
                            <?php echo e(strtoupper(substr($anggota->nama_lengkap, 0, 2))); ?>

                        </div>
                    <?php endif; ?>
                </div>

                
                <form method="POST" action="<?php echo e(route('profile.update-foto')); ?>" enctype="multipart/form-data" class="mb-2">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <input type="file" class="form-control form-control-sm <?php $__errorArgs = ['foto_profil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               name="foto_profil" accept="image/jpeg,image/png,image/jpg" id="fotoInput">
                        <?php $__errorArgs = ['foto_profil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">JPG/PNG, maks 2MB</div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-upload me-1"></i>Upload Foto
                    </button>
                </form>

                <?php if($anggota->foto_profil_url): ?>
                <form method="POST" action="<?php echo e(route('profile.delete-foto')); ?>" class="mb-3">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                            onclick="return confirm('Hapus foto profil?')">
                        <i class="bi bi-trash me-1"></i>Hapus Foto
                    </button>
                </form>
                <?php endif; ?>

                <h5 class="fw-bold mb-1"><?php echo e($anggota->nama_lengkap); ?></h5>
                <p class="text-muted mb-1"><?php echo e($anggota->jabatan_lengkap); ?></p>
                <span class="badge bg-<?php echo e($anggota->status_keanggotaan == 'aktif' ? 'success' : 'warning'); ?>">
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
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent p-0">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-profil" type="button">
                            <i class="bi bi-person me-1"></i> Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-password" type="button">
                            <i class="bi bi-lock me-1"></i> Ubah Password
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-login-history" type="button">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keamanan" type="button">
                            <i class="bi bi-shield-lock me-1"></i> Keamanan
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    
                    <div class="tab-pane fade show active" id="tab-profil">
                        <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="nama_lengkap" name="nama_lengkap"
                                           value="<?php echo e(old('nama_lengkap', $anggota->nama_lengkap)); ?>">
                                    <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="email" name="email"
                                           value="<?php echo e(old('email', $anggota->email)); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label fw-semibold">No. HP</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="no_hp" name="no_hp"
                                           value="<?php echo e(old('no_hp', $anggota->no_hp)); ?>">
                                    <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-12">
                                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                    <textarea class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="alamat" name="alamat" rows="2"><?php echo e(old('alamat', $anggota->alamat)); ?></textarea>
                                    <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-password">
                        <form method="POST" action="<?php echo e(route('password.update')); ?>" x-data="passwordStrength()">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                            <?php if(session('status') === 'password-updated'): ?>
                                <div class="alert alert-success small mb-3">
                                    <i class="bi bi-check-circle me-1"></i> Password berhasil diubah.
                                </div>
                            <?php endif; ?>

                            <?php if($errors->updatePassword->any()): ?>
                                <div class="alert alert-danger py-2 small mb-3">
                                    <?php $__currentLoopData = $errors->updatePassword->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div><i class="bi bi-exclamation-circle me-1"></i><?php echo e($error); ?></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Saat Ini</label>
                                <input type="password" class="form-control <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="current_password">
                                <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <input type="password" class="form-control <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="password" x-model="password" x-on:input="checkStrength()">
                                <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                                <div class="mt-2" x-show="password.length > 0">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" :class="strengthClass" :style="'width: ' + strengthPercent + '%'"></div>
                                    </div>
                                    <small :class="strengthTextClass" class="mt-1" x-text="strengthLabel"></small>
                                    <div class="small text-muted mt-1">
                                        <span :class="checks.length ? 'text-success' : 'text-muted'"><i class="bi" :class="checks.length ? 'bi-check-circle' : 'bi-circle'"></i> Min 8 karakter</span>
                                        <span :class="checks.upper ? 'text-success' : 'text-muted'" class="ms-2"><i class="bi" :class="checks.upper ? 'bi-check-circle' : 'bi-circle'"></i> Huruf besar</span>
                                        <span :class="checks.lower ? 'text-success' : 'text-muted'" class="ms-2"><i class="bi" :class="checks.lower ? 'bi-check-circle' : 'bi-circle'"></i> Huruf kecil</span>
                                        <span :class="checks.number ? 'text-success' : 'text-muted'" class="ms-2"><i class="bi" :class="checks.number ? 'bi-check-circle' : 'bi-circle'"></i> Angka</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-lock me-1"></i> Ubah Password
                            </button>
                        </form>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-login-history">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Waktu Login</th>
                                        <th>IP Address</th>
                                        <th>Browser / Device</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $loginHistory ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($log->attempted_at?->format('d/m/Y H:i')); ?></td>
                                        <td><code><?php echo e($log->ip_address); ?></code></td>
                                        <td class="small"><?php echo e(Str::limit($log->user_agent, 40)); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($log->status === 'success' ? 'success' : 'danger'); ?>">
                                                <?php echo e($log->status === 'success' ? 'Berhasil' : 'Gagal'); ?>

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

                    
                    <div class="tab-pane fade" id="tab-keamanan">
                        <div class="mb-4">
                            <h6 class="fw-semibold">Informasi Sesi</h6>
                            <div class="small">
                                <p><strong>Login terakhir:</strong> <?php echo e($anggota->last_login_at?->diffForHumans() ?? 'Belum pernah'); ?></p>
                                <p><strong>IP terakhir:</strong> <code><?php echo e($anggota->last_login_ip ?? '-'); ?></code></p>
                                <p><strong>Percobaan login gagal:</strong> <?php echo e($anggota->failed_login_attempts ?? 0); ?></p>
                            </div>
                        </div>
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            Untuk keamanan, session akan berakhir otomatis setelah <strong>30 menit</strong> tidak aktif.
                            Jangan pernah membagikan password Anda kepada siapapun.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function passwordStrength() {
    return {
        password: '',
        strengthPercent: 0,
        strengthLabel: '',
        strengthClass: 'bg-danger',
        strengthTextClass: 'text-danger',
        checks: { length: false, upper: false, lower: false, number: false },
        checkStrength() {
            this.checks.length = this.password.length >= 8;
            this.checks.upper  = /[A-Z]/.test(this.password);
            this.checks.lower  = /[a-z]/.test(this.password);
            this.checks.number = /[0-9]/.test(this.password);

            const score = Object.values(this.checks).filter(Boolean).length;

            if (score <= 1) {
                this.strengthPercent = 25; this.strengthLabel = 'Lemah'; this.strengthClass = 'bg-danger'; this.strengthTextClass = 'text-danger';
            } else if (score === 2) {
                this.strengthPercent = 50; this.strengthLabel = 'Cukup'; this.strengthClass = 'bg-warning'; this.strengthTextClass = 'text-warning';
            } else if (score === 3) {
                this.strengthPercent = 75; this.strengthLabel = 'Baik'; this.strengthClass = 'bg-info'; this.strengthTextClass = 'text-info';
            } else {
                this.strengthPercent = 100; this.strengthLabel = 'Kuat'; this.strengthClass = 'bg-success'; this.strengthTextClass = 'text-success';
            }
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/profile/edit.blade.php ENDPATH**/ ?>