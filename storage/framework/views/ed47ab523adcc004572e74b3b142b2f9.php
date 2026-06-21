<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-key text-white fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Ganti Password</h5>
                <p class="text-muted small mb-0">Admin telah mereset password Anda.</p>
                <p class="text-muted small">Anda dapat mengganti password sekarang atau melewatkannya.</p>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger py-2 small">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><i class="bi bi-exclamation-circle me-1"></i><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
                <div class="alert alert-warning py-2 small">
                    <i class="bi bi-exclamation-triangle me-1"></i><?php echo e(session('warning')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="alert alert-info py-2 small">
                    <i class="bi bi-info-circle me-1"></i><?php echo e(session('info')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.first-change.update')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small">Password Baru</label>
                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="password" name="password" autofocus>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation">
                </div>

                
                <div class="p-3 bg-light rounded small mb-3">
                    <div class="fw-semibold mb-1">Syarat Password:</div>
                    <ul class="mb-0 ps-3 text-muted">
                        <li>Minimal 8 karakter</li>
                        <li>Minimal 1 huruf besar & 1 huruf kecil</li>
                        <li>Minimal 1 angka</li>
                        <li>Tidak boleh sama dengan NIM</li>
                        <li>Tidak boleh sama dengan tanggal lahir</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold mb-2">
                    <i class="bi bi-check-lg me-1"></i> Simpan Password Baru
                </button>
            </form>

            
            <form method="POST" action="<?php echo e(route('password.first-change.skip')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-secondary w-100 fw-semibold"
                        onclick="return confirm('Anda yakin ingin melewati ganti password? Password dapat diganti kapan saja melalui menu Profil.')">
                    <i class="bi bi-arrow-right-circle me-1"></i> Lewati — Gunakan Password Saat Ini
                </button>
            </form>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/auth/first-password-change.blade.php ENDPATH**/ ?>