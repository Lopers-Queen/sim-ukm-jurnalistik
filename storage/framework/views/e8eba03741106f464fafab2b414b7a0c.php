<?php $__env->startSection('title', 'Activity Log — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Activity Log'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Log Keamanan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">Log Aktivitas Sistem</h4>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Waktu</th><th>Pelaku</th><th>Model</th><th>Deskripsi</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td class="small"><?php echo e($act->created_at->format('d/m/Y H:i')); ?></td>
            <td><?php echo e($act->causer?->nama_lengkap ?? 'Sistem'); ?></td>
            <td><code class="small"><?php echo e(class_basename($act->subject_type ?? '-')); ?></code></td>
            <td><?php echo e($act->description); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada aktivitas</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php if($activities->hasPages()): ?><div class="card-footer"><?php echo e($activities->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/keamanan/activity-log.blade.php ENDPATH**/ ?>