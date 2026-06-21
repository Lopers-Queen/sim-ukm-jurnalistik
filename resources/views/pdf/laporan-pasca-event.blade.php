<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pasca Event — {{ $event->nama_event }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px solid #1a56db; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0; color: #1a56db; }
        .header h2 { font-size: 13px; margin: 5px 0 0; font-weight: normal; }
        .header p { margin: 3px 0; color: #666; font-size: 10px; }
        .section { margin-bottom: 15px; }
        .section-title { background: #1a56db; color: #fff; padding: 6px 12px; font-size: 12px; font-weight: bold; margin-bottom: 10px; }
        .section-content { padding: 0 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 10px; }
        table th { background: #f0f4ff; font-weight: bold; }
        .keuangan-box { display: inline-block; width: 30%; text-align: center; border: 1px solid #ddd; padding: 8px; margin: 0 1%; }
        .keuangan-box .label { font-size: 9px; color: #666; text-transform: uppercase; }
        .keuangan-box .value { font-size: 14px; font-weight: bold; }
        .rating { font-size: 14px; color: #f59e0b; }
        .footer { text-align: center; margin-top: 30px; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .ttd-area { margin-top: 40px; display: table; width: 100%; }
        .ttd-col { display: table-cell; width: 50%; text-align: center; }
        .ttd-line { border-bottom: 1px solid #333; width: 200px; margin: 60px auto 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PASCA EVENT</h1>
        <h2>{{ $event->nama_event }}</h2>
        <p>{{ $event->tanggal_mulai?->format('d M Y') }} — {{ $event->lokasi ?? '-' }}</p>
        <p>UKM Jurnalistik Politeknik Negeri Samarinda</p>
    </div>

    <div class="section">
        <div class="section-title">I. NOTULENSI</div>
        <div class="section-content">{!! nl2br(e($laporan->notulensi)) !!}</div>
    </div>

    @if($laporan->daftar_peserta)
    <div class="section">
        <div class="section-title">II. DAFTAR PESERTA</div>
        <div class="section-content">{!! nl2br(e($laporan->daftar_peserta)) !!}</div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">III. EVALUASI</div>
        <div class="section-content">
            <p><strong>Kendala:</strong><br>{!! nl2br(e($laporan->kendala)) !!}</p>
            <p><strong>Pencapaian:</strong><br>{!! nl2br(e($laporan->pencapaian)) !!}</p>
            <p><strong>Saran Perbaikan:</strong><br>{!! nl2br(e($laporan->saran_perbaikan)) !!}</p>
            <p><strong>Rating Internal:</strong> <span class="rating">{{ str_repeat('★', $laporan->rating_internal) }}{{ str_repeat('☆', 5 - $laporan->rating_internal) }}</span> ({{ $laporan->rating_internal }}/5)</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">IV. LAPORAN KEUANGAN</div>
        <div class="section-content" style="text-align: center;">
            <div class="keuangan-box">
                <div class="label">Total Pemasukan</div>
                <div class="value" style="color: green;">Rp {{ number_format($laporan->total_pemasukan, 0, ',', '.') }}</div>
            </div>
            <div class="keuangan-box">
                <div class="label">Total Pengeluaran</div>
                <div class="value" style="color: red;">Rp {{ number_format($laporan->total_pengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="keuangan-box">
                <div class="label">Sisa Anggaran</div>
                <div class="value" style="color: {{ $laporan->sisa_anggaran >= 0 ? 'green' : 'red' }};">Rp {{ number_format($laporan->sisa_anggaran, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">V. DAFTAR PANITIA</div>
        <div class="section-content">
            <table>
                <thead><tr><th>No</th><th>Nama</th><th>NIM</th><th>Divisi Panitia</th><th>Jabatan</th></tr></thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($event->divisiPanitia as $div)
                        @foreach($div->anggotaPanitia as $ap)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $ap->anggota->nama_lengkap ?? '-' }}</td>
                            <td>{{ $ap->anggota->nim ?? '-' }}</td>
                            <td>{{ $div->nama_divisi }}</td>
                            <td>{{ $ap->jabatan_panitia }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="ttd-area">
        <div class="ttd-col">
            <p>Disusun oleh,</p>
            <div class="ttd-line"></div>
            <p><strong>{{ $laporan->pelapor->nama_lengkap ?? '-' }}</strong><br>Sekretaris Panitia</p>
        </div>
        <div class="ttd-col">
            <p>Diketahui oleh,</p>
            <div class="ttd-line"></div>
            <p><strong>{{ $event->pic->nama_lengkap ?? '-' }}</strong><br>Ketua Panitia</p>
        </div>
    </div>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh SIM UKM Jurnalistik Polnes pada {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
