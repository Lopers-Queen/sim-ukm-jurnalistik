<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 2cm 2.5cm;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header h3 {
            margin: 0;
            font-size: 14pt;
        }
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .judul {
            text-align: center;
            margin: 30px 0;
        }
        .judul h2 {
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .judul p {
            font-size: 10pt;
            margin: 0;
        }
        .isi {
            text-align: justify;
        }
        .identitas {
            margin: 15px 0 15px 30px;
        }
        .identitas table td {
            padding: 2px 10px 2px 0;
            vertical-align: top;
        }
        .identitas table td:first-child {
            width: 150px;
        }
        .pernyataan {
            margin: 20px 0;
        }
        .ttd-container {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .ttd-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .ttd-space {
            height: 80px;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <h3>UNIT KEGIATAN MAHASISWA JURNALISTIK</h3>
        <h3>POLITEKNIK NEGERI SAMARINDA</h3>
        <p>Jl. Dr. Cipto Mangunkusumo, Kampus Gunung Lipan, Samarinda</p>
        <p>Email: ukm.jurnalistik@polnes.ac.id</p>
    </div>

    
    <div class="judul">
        <h2>SURAT PERNYATAAN</h2>
        <p>Nomor: <?php echo e($surat->nomor_surat ?? '___/SP-UKM/___'); ?></p>
    </div>

    
    <div class="isi">
        <p>Yang bertanda tangan di bawah ini:</p>

        <div class="identitas">
            <table>
                <tr><td>Nama</td><td>: <?php echo e($anggota->nama_lengkap); ?></td></tr>
                <tr><td>NIM</td><td>: <?php echo e($anggota->nim); ?></td></tr>
                <tr><td>Program Studi</td><td>: <?php echo e($anggota->program_studi ?? '-'); ?></td></tr>
                <tr><td>Jurusan</td><td>: <?php echo e($anggota->jurusan ?? '-'); ?></td></tr>
                <tr><td>Status</td><td>: <?php echo e(ucfirst($anggota->status_keanggotaan)); ?></td></tr>
            </table>
        </div>

        <div class="pernyataan">
            <p>Dengan ini menyatakan bahwa saya bersedia untuk berpartisipasi dalam kepanitiaan
               event <strong>"<?php echo e($event->nama_event); ?>"</strong> yang akan dilaksanakan pada tanggal
               <strong><?php echo e($event->tanggal_mulai->translatedFormat('d F Y')); ?></strong>
               <?php echo e($event->tanggal_selesai ? 'sampai dengan ' . $event->tanggal_selesai->translatedFormat('d F Y') : ''); ?>

               di <?php echo e($event->lokasi ?? '(lokasi akan ditentukan)'); ?>.</p>

            <p>Saya memahami dan menyetujui bahwa:</p>
            <ol>
                <li>Saya akan menjalankan tugas dan tanggung jawab sesuai dengan posisi yang diberikan.</li>
                <li>Saya akan mengikuti seluruh rangkaian kegiatan kepanitiaan sesuai jadwal yang telah ditentukan.</li>
                <li>Saya akan berkoordinasi dengan baik dengan seluruh anggota panitia lainnya.</li>
                <li>Saya bertanggung jawab penuh atas tugas yang diamanahkan kepada saya.</li>
            </ol>

            <p>Demikian surat pernyataan ini saya buat dengan sebenarnya tanpa ada paksaan dari pihak manapun.</p>
        </div>
    </div>

    
    <div class="ttd-container">
        <div class="ttd-col">
            <p>Mengetahui,<br>Ketua Umum UKM Jurnalistik</p>
            <div class="ttd-space"></div>
            <p>(_________________________)</p>
        </div>
        <div class="ttd-col">
            <p>Samarinda, <?php echo e(now()->translatedFormat('d F Y')); ?><br>Yang Menyatakan,</p>
            <div class="ttd-space"></div>
            <p><strong><?php echo e($anggota->nama_lengkap); ?></strong><br>NIM: <?php echo e($anggota->nim); ?></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/pdf/surat-pernyataan.blade.php ENDPATH**/ ?>