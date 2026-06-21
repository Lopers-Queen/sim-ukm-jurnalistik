<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Event — SIM UKM Jurnalistik</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a237e; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1a237e; font-size: 16px; }
        .header p { margin: 2px 0; color: #666; font-size: 10px; }
        .summary { display: table; width: 100%; margin-bottom: 15px; }
        .summary-item { display: table-cell; text-align: center; padding: 8px; background: #f5f5f5; border: 1px solid #ddd; }
        .summary-item .value { font-size: 14px; font-weight: bold; color: #1a237e; }
        .summary-item .label { font-size: 9px; color: #888; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #1a237e; color: white; padding: 6px 8px; text-align: left; font-size: 9px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9px; }
        tr:nth-child(even) { background: #fafafa; }
        tfoot td { background: #f0f0f0; font-weight: bold; border-top: 2px solid #1a237e; }
        .text-end { text-align: right; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; color: white; }
        .bg-success { background: #28a745; }
        .bg-warning { background: #ffc107; color: #333; }
        .bg-primary { background: #1a237e; }
        .bg-danger { background: #dc3545; }
        .bg-secondary { background: #6c757d; }
        .footer { text-align: center; font-size: 8px; color: #aaa; margin-top: 20px; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN EVENT</h2>
        <p>UKM Jurnalistik — Politeknik Negeri Samarinda</p>
        <p>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WITA</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="value">{{ $events->count() }}</div>
            <div class="label">Total Event</div>
        </div>
        <div class="summary-item">
            <div class="value">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</div>
            <div class="label">Total Anggaran</div>
        </div>
        <div class="summary-item">
            <div class="value">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</div>
            <div class="label">Total Realisasi</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Event</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>PIC</th>
                <th>Status</th>
                <th class="text-end">Anggaran</th>
                <th class="text-end">Realisasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $i => $event)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $event->nama_event }}</strong></td>
                <td>{{ $event->tanggal_mulai->format('d/m/Y') }}</td>
                <td>{{ $event->lokasi ?? '-' }}</td>
                <td>{{ $event->pic->nama_lengkap ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ match($event->status) {
                        'selesai' => 'success', 'aktif' => 'primary', 'direncanakan' => 'warning',
                        'batal' => 'danger', default => 'secondary'
                    } }}">{{ ucfirst($event->status) }}</span>
                </td>
                <td class="text-end">Rp {{ number_format($event->anggaran_total, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($event->anggaranEvent->sum('jumlah_realisasi'), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end">TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        SIM UKM Jurnalistik — Politeknik Negeri Samarinda © {{ date('Y') }}
    </div>
</body>
</html>
