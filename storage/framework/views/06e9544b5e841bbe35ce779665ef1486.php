<?php $__env->startSection('title', 'Log Keamanan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Log Login'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Log Keamanan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">Riwayat Login</h4>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-5"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput" value="<?php echo e(request('search')); ?>" placeholder="Cari NIM/nama..."></div></div>
        <div class="col-md-3"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            <option value="success" <?php echo e(request('status')=='success'?'selected':''); ?>>Berhasil</option>
            <option value="failed" <?php echo e(request('status')=='failed'?'selected':''); ?>>Gagal</option></select></div>
        <?php if(request('search') || request('status')): ?>
        <div class="col-md-4"><a href="<?php echo e(route('keamanan.login-history')); ?>" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        <?php endif; ?>
    </form>
</div></div>
<?php $__env->startPush('scripts'); ?>
<script>
let t;document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(t);t=setTimeout(()=>this.form.submit(),500)});
</script>
<?php $__env->stopPush(); ?>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Waktu</th><th>Anggota</th><th>IP Address</th><th>User Agent</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td class="small"><?php echo e($h->attempted_at->format('d/m/Y H:i:s')); ?></td>
            <td><?php echo e($h->anggota?->nama_lengkap ?? $h->anggota_id); ?></td>
            <td><code><?php echo e($h->ip_address); ?></code></td>
            <td class="small text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($h->user_agent); ?></td>
            <td><span class="badge bg-<?php echo e($h->status === 'success' ? 'success' : 'danger'); ?>"><?php echo e($h->status === 'success' ? 'Berhasil' : 'Gagal'); ?></span></td>
            <td>
                <?php if($h->status === 'failed' && $h->anggota && ($h->anggota->is_locked || $h->anggota->failed_login_attempts >= 3)): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('keamanan.manage-lockout')): ?>
                <form method="POST" action="<?php echo e(route('keamanan.unlock-account')); ?>" class="d-inline" onsubmit="return confirm('Unlock akun <?php echo e($h->anggota->nama_lengkap); ?>?')">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="anggota_id" value="<?php echo e($h->anggota->id); ?>">
                    <button class="btn btn-outline-warning btn-sm"><i class="bi bi-unlock me-1"></i>Unlock</button>
                </form>
                <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat login</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($histories->hasPages()): ?><div class="card-footer"><?php echo e($histories->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/keamanan/login-history.blade.php ENDPATH**/ ?>