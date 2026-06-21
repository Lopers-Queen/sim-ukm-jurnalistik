<?php $__env->startSection('title', 'Template Kepanitiaan — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', 'Template Kepanitiaan'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item active">Template Panitia</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Template Kepanitiaan</h4>
        <p class="text-muted mb-0">Kelola template struktur kepanitiaan event (FR-19)</p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('template-panitia.create')): ?>
    <a href="<?php echo e(route('template-kepanitiaan.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Buat Template
    </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Template</th>
                        <th>Jumlah Divisi</th>
                        <th>Penggunaan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($templates->firstItem() + $i); ?></td>
                        <td>
                            <div class="fw-semibold"><?php echo e($template->nama_template); ?></div>
                            <small class="text-muted"><?php echo e(Str::limit($template->deskripsi, 50)); ?></small>
                        </td>
                        <td>
                            <?php $divisi = is_array($template->struktur) ? $template->struktur : (json_decode($template->struktur, true) ?? []); ?>
                            <span class="badge bg-primary"><?php echo e(count($divisi)); ?> divisi</span>
                        </td>
                        <td>—</td>
                        <td>
                            <span class="badge bg-<?php echo e($template->is_active ? 'success' : 'secondary'); ?>">
                                <?php echo e($template->is_active ? 'Aktif' : 'Arsip'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('template-kepanitiaan.show', $template)); ?>" class="btn btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('template-panitia.edit')): ?>
                                <a href="<?php echo e(route('template-kepanitiaan.edit', $template)); ?>" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('template-panitia.create')): ?>
                                <form method="POST" action="<?php echo e(route('template-kepanitiaan.duplicate', $template)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-outline-info" title="Duplikasi"><i class="bi bi-copy"></i></button>
                                </form>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('template-panitia.delete')): ?>
                                <form method="POST" action="<?php echo e(route('template-kepanitiaan.destroy', $template)); ?>" class="d-inline" onsubmit="return confirm('Hapus template ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-clipboard-data fs-1 d-block mb-2"></i>
                            Belum ada template kepanitiaan
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($templates->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/template-kepanitiaan/index.blade.php ENDPATH**/ ?>