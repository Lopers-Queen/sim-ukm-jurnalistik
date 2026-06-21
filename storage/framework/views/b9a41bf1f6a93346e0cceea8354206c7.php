<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title>Laporan Data Anggota</title>
<style>
    body { font-family: sans-serif; font-size: 11px; }
    h1 { font-size: 16px; text-align: center; margin-bottom: 5px; }
    h2 { font-size: 12px; text-align: center; color: #666; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 5px 8px; text-align: left; }
    th { background: #2c3e50; color: #fff; font-size: 10px; text-transform: uppercase; }
    tr:nth-child(even) { background: #f8f9fa; }
    .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: right; }
</style>
</head><body>
<h1>LAPORAN DATA ANGGOTA</h1>
<h2>UKM Jurnalistik — Politeknik Negeri Samarinda</h2>
<table>
    <thead><tr><th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Email</th><th>Divisi</th><th>Jabatan</th><th>Status</th><th>Tgl Bergabung</th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $anggotaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($i + 1); ?></td><td><?php echo e($a->nim); ?></td><td><?php echo e($a->nama_lengkap); ?></td><td><?php echo e($a->email); ?></td>
        <td><?php echo e($a->divisi_label); ?></td><td><?php echo e($a->jabatan_lengkap); ?></td><td><?php echo e(ucfirst($a->status_keanggotaan)); ?></td>
        <td><?php echo e($a->tanggal_bergabung?->format('d/m/Y') ?? '-'); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<div class="footer">Dicetak pada: <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIT</div>
</body></html>
<?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/pdf/laporan-anggota.blade.php ENDPATH**/ ?>