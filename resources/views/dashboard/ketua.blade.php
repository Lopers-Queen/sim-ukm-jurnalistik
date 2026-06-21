@extends('layouts.app')

@section('title', 'Dashboard Ketua — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard Pimpinan')

@section('content')
<div class="row g-4 mb-4">
    {{-- Stat Cards --}}
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Anggota</div>
                        <div class="fs-3 fw-bold mt-1">{{ $totalAnggota }}</div>
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
                        <div class="fs-3 fw-bold mt-1">{{ $anggotaAktif }}</div>
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
                        <div class="fs-3 fw-bold mt-1">{{ $eventAktif }}</div>
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
                        <div class="text-muted small fw-semibold text-uppercase">Surat Pending</div>
                        <div class="fs-3 fw-bold mt-1">{{ $suratPending }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-envelope-exclamation fs-3 text-warning"></i>
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
            <div class="card-header bg-transparent border-0">
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

{{-- Quick Actions + Event + Activity --}}
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('pergantian.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-repeat me-1"></i>Pergantian Kepengurusan
                </a>
                <a href="{{ route('anggota.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-person-plus me-1"></i>Tambah Anggota
                </a>
                <a href="{{ route('event.create') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-calendar-plus me-1"></i>Buat Event
                </a>
                <a href="{{ route('surat-pernyataan.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-envelope me-1"></i>Surat Pernyataan ({{ $suratPending }})
                </a>
                <a href="{{ route('keamanan.login-history') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-shield-lock me-1"></i>Log Keamanan
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold" id="eventHeader"><i class="bi bi-calendar-event me-2"></i>Event Mendatang</h6>
            </div>
            <div class="card-body">
                @forelse($eventMendatang as $event)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3 text-center" style="min-width: 50px;">
                            <div class="fw-bold text-primary">{{ $event->tanggal_mulai->format('d') }}</div>
                            <div class="small text-muted">{{ $event->tanggal_mulai->format('M') }}</div>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $event->nama_event }}</div>
                            <div class="small text-muted">{{ $event->lokasi ?? '-' }}</div>
                        </div>
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

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h6>
            </div>
            <div class="card-body">
                @forelse($recentActivities as $activity)
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
    // ── Cookie: Activity log filter preference ──
    const activityFilterKey = 'ketua_activity_filter';
    const savedFilter = localStorage.getItem(activityFilterKey);

    // ── Cookie: Event count for "new" badge ──
    const eventCountKey = 'ketua_last_event_count';
    const lastCount = parseInt(localStorage.getItem(eventCountKey)) || 0;
    const currentCount = {{ (int) count($eventMendatang) }};
    if (currentCount > lastCount && lastCount > 0) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-danger ms-2';
        badge.textContent = 'Baru';
        const eventHeader = document.querySelector('#eventHeader');
        if (eventHeader) eventHeader.appendChild(badge);
    }
    localStorage.setItem(eventCountKey, currentCount);

    // ── Charts ──
    const ctxDivisi = document.getElementById('chartDistribusiDivisi');
    if (ctxDivisi) {
        new Chart(ctxDivisi, {
            type: 'bar',
            data: {
                labels: {!! json_encode($divisiLabels) !!},
                datasets: [{
                    label: 'Jumlah Anggota',
                    data: {!! json_encode($divisiData) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)', 'rgba(6, 182, 212, 0.8)',
                        'rgba(239, 68, 68, 0.8)', 'rgba(100, 116, 139, 0.6)',
                    ],
                    borderRadius: 8, borderSkipped: false,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }
    const ctxStatus = document.getElementById('chartStatusAnggota');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Pasif', 'Alumni'],
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(100, 116, 139, 0.6)'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { padding: 20 } } }
            }
        });
    }
});
</script>
@endpush
