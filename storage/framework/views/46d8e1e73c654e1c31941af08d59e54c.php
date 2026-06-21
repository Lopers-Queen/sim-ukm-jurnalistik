<?php $__env->startSection('title', 'Detail Event — SIM UKM Jurnalistik'); ?>
<?php $__env->startSection('page-title', $event->nama_event); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('event.index')); ?>">Event</a></li>
<li class="breadcrumb-item active">Detail</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="fw-bold"><?php echo e($event->nama_event); ?></h5>
                <span class="badge bg-<?php echo e($event->status_badge); ?> mb-3"><?php echo e($event->status_label); ?></span>
                <div class="small">
                    <p><i class="bi bi-calendar me-2"></i><strong>Mulai:</strong> <?php echo e($event->tanggal_mulai->translatedFormat('d F Y')); ?></p>
                    <?php if($event->tanggal_selesai): ?><p><i class="bi bi-calendar-check me-2"></i><strong>Selesai:</strong> <?php echo e($event->tanggal_selesai->translatedFormat('d F Y')); ?></p><?php endif; ?>
                    <p><i class="bi bi-geo-alt me-2"></i><strong>Lokasi:</strong> <?php echo e($event->lokasi ?? '-'); ?></p>
                    <p><i class="bi bi-person me-2"></i><strong>PIC:</strong> <?php echo e($event->pic?->nama_lengkap ?? '-'); ?></p>
                    <p><i class="bi bi-calendar-range me-2"></i><strong>Periode:</strong> <?php echo e($event->periode?->nama_periode ?? '-'); ?></p>
                    <p><i class="bi bi-cash me-2"></i><strong>Anggaran:</strong> Rp <?php echo e(number_format($event->anggaran_total ?? 0, 0, ',', '.')); ?></p>
                </div>
                <?php if($event->deskripsi): ?><hr><p class="small text-muted"><?php echo e($event->deskripsi); ?></p><?php endif; ?>
                <div class="d-flex gap-2 mt-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event.edit')): ?><a href="<?php echo e(route('event.edit', $event)); ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a><?php endif; ?>
                    <a href="<?php echo e(route('event.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kepanitiaan.create')): ?>
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold">Tambah Divisi Panitia</h6></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('event.add-divisi', $event)); ?>"><?php echo csrf_field(); ?>
                    <div class="mb-2"><input type="text" class="form-control form-control-sm" name="nama_divisi" placeholder="Nama divisi" required></div>
                    <div class="mb-2"><input type="text" class="form-control form-control-sm" name="deskripsi" placeholder="Deskripsi (opsional)"></div>
                    <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-plus me-1"></i>Tambah Divisi</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-8">
        <?php $__currentLoopData = $event->divisiPanitia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $divisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-diagram-3 me-1"></i><?php echo e($divisi->nama_divisi); ?></h6>
                <span class="badge bg-primary"><?php echo e($divisi->anggotaPanitia->count()); ?> anggota</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>Jabatan Panitia</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $divisi->anggotaPanitia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($ap->anggota?->nama_lengkap); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($ap->jabatan_panitia); ?></span></td>
                        <td><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kepanitiaan.delete')): ?>
                            <form method="POST" action="<?php echo e(route('event.remove-panitia', [$event, $ap])); ?>" onsubmit="return confirm('Hapus dari panitia?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger btn-sm"><i class="bi bi-x"></i></button>
                            </form><?php endif; ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="3" class="text-center text-muted py-3">Belum ada anggota</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kepanitiaan.create')): ?>
        <?php if($event->divisiPanitia->isNotEmpty()): ?>
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold">Assign Anggota ke Panitia</h6></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('event.assign-panitia', $event)); ?>"><?php echo csrf_field(); ?>
                    <div class="row g-2">
                        <div class="col-md-4"><select class="form-select form-select-sm select-search" name="anggota_id" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php $__currentLoopData = \App\Models\Anggota::where('status_keanggotaan','aktif')->where('jabatan_struktural','!=','admin')->orderBy('nama_lengkap')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($a->id); ?>"><?php echo e($a->nama_lengkap); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                        <div class="col-md-3"><select class="form-select form-select-sm" name="divisi_panitia_id" required>
                            <option value="">-- Divisi --</option>
                            <?php $__currentLoopData = $event->divisiPanitia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>"><?php echo e($d->nama_divisi); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="jabatan_panitia" placeholder="Jabatan" required></div>
                        <div class="col-md-2"><button class="btn btn-primary btn-sm w-100"><i class="bi bi-plus me-1"></i>Assign</button></div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if($event->divisiPanitia->isEmpty()): ?>
        <div class="text-center text-muted py-5"><i class="bi bi-diagram-3 fs-1 d-block mb-2"></i>Belum ada divisi panitia. Tambahkan divisi terlebih dahulu.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/event/show.blade.php ENDPATH**/ ?>