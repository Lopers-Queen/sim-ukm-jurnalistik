<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan — SIM UKM Jurnalistik</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a237e; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1a237e; font-size: 16px; }
        .header p { margin: 2px 0; color: #666; font-size: 10px; }
        .summary { display: table; width: 100%; margin-bottom: 15px; }
        .summary-item { display: table-cell; text-align: center; padding: 8px; background: #f5f5f5; border: 1px solid #ddd; }
        .summary-item .value { font-size: 12px; font-weight: bold; color: #1a237e; }
        .summary-item .label { font-size: 9px; color: #888; }
        .section-title { font-size: 12px; font-weight: bold; color: #1a237e; margin: 15px 0 8px; border-left: 3px solid #1a237e; padding-left: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #1a237e; color: white; padding: 6px 8px; text-align: left; font-size: 9px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9px; }
        tr:nth-child(even) { background: #fafafa; }
        tfoot td { background: #f0f0f0; font-weight: bold; border-top: 2px solid #1a237e; }
        .text-end { text-align: right; }
        .footer { text-align: center; font-size: 8px; color: #aaa; margin-top: 20px; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN</h2>
        <p>UKM Jurnalistik — Politeknik Negeri Samarinda</p>
        <p>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WITA</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="value">Rp {{ number_format($totalDivisiAnggaran, 0, ',', '.') }}</div>
            <div class="label">Divisi — Anggaran</div>
        </div>
        <div class="summary-item">
            <div class="value">Rp {{ number_format($totalDivisiTerpakai, 0, ',', '.') }}</div>
            <div class="label">Divisi — Terpakai</div>
        </div>
        <div class="summary-item">
            <div class="value">Rp {{ number_format($totalEventAnggaran, 0, ',', '.') }}</div>
            <div class="label">Event — Dianggarkan</div>
        </div>
        <div class="summary-item">
            <div class="value">Rp {{ number_format($totalEventRealisasi, 0, ',', '.') }}</div>
            <div class="label">Event — Realisasi</div>
        </div>
    </div>

    <div class="section-title">A. Anggaran per Divisi</div>
    <table>
        <thead>
            <tr><th>No</th><th>Divisi</th><th>Bulan/Tahun</th><th class="text-end">Anggaran</th><th class="text-end">Terpakai</th><th class="text-end">Sisa</th></tr>
        </thead>
        <tbody>
            @forelse($anggaranDivisi as $i => $ad)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $ad->divisi)) }}</td>
                <td>{{ $ad->bulan }}/{{ $ad->tahun }}</td>
                <td class="text-end">Rp {{ number_format($ad->jumlah_anggaran, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($ad->jumlah_terpakai, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($ad->jumlah_anggaran - $ad->jumlah_terpakai, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">B. Anggaran per Event</div>
    <table>
        <thead>
            <tr><th>No</th><th>Event</th><th>Item</th><th>Kategori</th><th class="text-end">Qty</th><th class="text-end">Dianggarkan</th><th class="text-end">Realisasi</th></tr>
        </thead>
        <tbody>
            @forelse($anggaranEvent as $i => $ae)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $ae->event->nama_event ?? '-' }}</td>
                <td>{{ $ae->item }}</td>
                <td>{{ $ae->kategori ?? '-' }}</td>
                <td class="text-end">{{ $ae->qty }}</td>
                <td class="text-end">Rp {{ number_format($ae->jumlah_dianggarkan, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($ae->jumlah_realisasi ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        @if($anggaranEvent->count())
        <tfoot>
            <tr>
                <td colspan="5" class="text-end">TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalEventAnggaran, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totalEventRealisasi, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        SIM UKM Jurnalistik — Politeknik Negeri Samarinda © {{ date('Y') }}
    </div>
</body>
</html>
