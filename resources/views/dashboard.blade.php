@extends('layouts.app')

@section('title', 'Dashboard — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    {{-- Stat Cards --}}
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Anggota</div>
                        <div class="fs-3 fw-bold mt-1">{{ $totalAnggota ?? 0 }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Anggota Aktif</div>
                        <div class="fs-3 fw-bold mt-1">{{ $anggotaAktif ?? 0 }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-person-check fs-3 text-success"></i>
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
                        <div class="fs-3 fw-bold mt-1">{{ $eventAktif ?? 0 }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-calendar-event fs-3 text-info"></i>
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
                        <div class="text-muted small fw-semibold text-uppercase">Anggota Pasif</div>
                        <div class="fs-3 fw-bold mt-1">{{ $anggotaPasif ?? 0 }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-person-dash fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Distribusi Anggota per Divisi</h6>
            </div>
            <div class="card-body">
                <canvas id="chartDistribusiDivisi" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold">Status Keanggotaan</h6>
            </div>
            <div class="card-body">
                <canvas id="chartStatusAnggota" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Info Cards --}}
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-2"></i>Event Mendatang</h6>
            </div>
            <div class="card-body">
                @forelse($eventMendatang ?? [] as $event)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3 text-center" style="min-width: 50px;">
                            <div class="fw-bold text-primary">{{ $event->tanggal_mulai->format('d') }}</div>
                            <div class="small text-muted">{{ $event->tanggal_mulai->format('M') }}</div>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $event->nama_event }}</div>
                            <div class="small text-muted">{{ $event->lokasi ?? '-' }}</div>
                        </div>
                        <span class="badge bg-{{ $event->status_badge }} ms-auto">{{ $event->status_label }}</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada event mendatang</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h6>
            </div>
            <div class="card-body">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="d-flex mb-3">
                        <div class="avatar avatar-sm me-3 flex-shrink-0">
                            {{ strtoupper(substr($activity->causer?->nama_lengkap ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <div class="small">{{ $activity->description }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Detect current theme
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(42, 42, 58, 0.5)' : 'rgba(226, 232, 240, 0.8)';
    const borderBg = isDark ? '#12121a' : '#ffffff';

    // Global Chart.js defaults
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.font.family = "'Inter', 'Manrope', sans-serif";

    // Distribusi per Divisi (Bar Chart)
    const ctxDivisi = document.getElementById('chartDistribusiDivisi');
    if (ctxDivisi) {
        new Chart(ctxDivisi, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartDivisiLabels ?? ['Fotografi', 'Pers & Penyiaran', 'Videografi', 'Kominfo', 'Redaksi', 'Inventory']) !!},
                datasets: [{
                    label: 'Jumlah Anggota',
                    data: {!! json_encode($chartDivisiData ?? [0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(6, 182, 212, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(100, 116, 139, 0.5)',
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: textColor },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Status Keanggotaan (Doughnut Chart)
    const ctxStatus = document.getElementById('chartStatusAnggota');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Pasif', 'Alumni'],
                datasets: [{
                    data: {!! json_encode($chartStatusData ?? [0, 0, 0]) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(100, 116, 139, 0.4)',
                    ],
                    borderWidth: 2,
                    borderColor: borderBg,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, color: textColor, font: { family: "'Inter', sans-serif" } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
