@extends('layouts.app')
@section('title', 'Dashboard Bendahara — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard Keuangan')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Anggaran</div>
                        <div class="fs-4 fw-bold mt-1">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-wallet2 fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Terpakai</div>
                        <div class="fs-4 fw-bold mt-1">Rp {{ number_format($totalTerpakai, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-cash-stack fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Sisa Anggaran</div>
                        <div class="fs-4 fw-bold mt-1">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-piggy-bank fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Event Aktif</div>
                        <div class="fs-3 fw-bold mt-1">{{ $eventAktif }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-calendar-event fs-3 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold">Anggaran vs Realisasi per Divisi</h6>
            </div>
            <div class="card-body">
                <canvas id="chartAnggaranDivisi" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('anggaran-divisi.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Anggaran Divisi
                </a>
                <a href="{{ route('anggaran-event.create') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Anggaran Event
                </a>
                <a href="{{ route('laporan.keuangan') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>Laporan Keuangan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Cookie: Currency display format ──
    const currencyKey = 'bendahara_currency_format';
    const format = localStorage.getItem(currencyKey) || 'full';

    // Apply abbreviated format if saved preference is 'abbreviated'
    if (format === 'abbreviated') {
        document.querySelectorAll('[data-currency]').forEach(el => {
            const val = parseInt(el.dataset.currency);
            if (val >= 1000000) {
                el.textContent = 'Rp ' + (val / 1000000).toFixed(1) + ' Jt';
            } else if (val >= 1000) {
                el.textContent = 'Rp ' + (val / 1000).toFixed(0) + ' Rb';
            }
        });
    }

    // Toggle currency format on double-click of stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('dblclick', () => {
            const current = localStorage.getItem(currencyKey) || 'full';
            const next = current === 'full' ? 'abbreviated' : 'full';
            localStorage.setItem(currencyKey, next);
            location.reload();
        });
    });

    // ── Cookie: Divisi filter for drill-down ──
    const divisiFilterKey = 'bendahara_divisi_filter';

    // ── Cookie: Chart type preference ──
    const chartTypeKey = 'bendahara_chart_type';
    const savedChartType = localStorage.getItem(chartTypeKey) || 'bar';

    const ctx = document.getElementById('chartAnggaranDivisi');
    if (ctx) {
        new Chart(ctx, {
            type: savedChartType === 'horizontal' ? 'bar' : 'bar',
            data: {
                labels: {!! json_encode($divisiLabels) !!},
                datasets: [
                    { label: 'Anggaran', data: {!! json_encode($anggaranPerDivisi) !!}, backgroundColor: 'rgba(59, 130, 246, 0.7)', borderRadius: 6 },
                    { label: 'Terpakai', data: {!! json_encode($terpakaiPerDivisi) !!}, backgroundColor: 'rgba(245, 158, 11, 0.7)', borderRadius: 6 },
                ]
            },
            options: {
                indexAxis: savedChartType === 'horizontal' ? 'y' : 'x',
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
            }
        });
    }
});
</script>
@endpush
