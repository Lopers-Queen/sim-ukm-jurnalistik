@extends('layouts.app')
@section('title', 'Dashboard Kadiv — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard Divisi ' . ucfirst(str_replace('_', ' & ', $divisi ?? '')))

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Anggota Divisi</div>
                        <div class="fs-3 fw-bold mt-1">{{ $anggotaDivisi }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card stat-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Anggaran Divisi</div>
                        <div class="fs-4 fw-bold mt-1">Rp {{ number_format($anggaranDivisi->total ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-wallet2 fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card stat-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Terpakai</div>
                        <div class="fs-4 fw-bold mt-1">Rp {{ number_format($anggaranDivisi->terpakai ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-cash-stack fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-2"></i>Event Mendatang</h6>
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

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('anggota.index', ['divisi' => $divisi]) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people me-1"></i>Lihat Anggota Divisi
                </a>
                <a href="{{ route('event.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-calendar-event me-1"></i>Lihat Event
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>Laporan
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Cookie: Members sort preference ──
    const sortKey = 'kadiv_{{ $divisi }}_members_sort';
    const savedSort = localStorage.getItem(sortKey) || 'name';

    // ── Cookie: Budget view toggle (summary vs detailed) ──
    const budgetKey = 'kadiv_{{ $divisi }}_budget_view';
    const budgetView = localStorage.getItem(budgetKey) || 'summary';

    // Show remaining budget calculation
    const total = {{ (int) ($anggaranDivisi->total ?? 0) }};
    const terpakai = {{ (int) ($anggaranDivisi->terpakai ?? 0) }};
    const remaining = total - terpakai;
    const pctUsed = total > 0 ? ((terpakai / total) * 100).toFixed(1) : 0;

    // Add budget progress bar to budget stat card
    const budgetCards = document.querySelectorAll('.stat-card');
    if (budgetCards.length >= 3) {
        const progressBar = document.createElement('div');
        progressBar.className = 'progress mt-2';
        progressBar.style.height = '6px';
        progressBar.innerHTML = '<div class="progress-bar bg-' + (pctUsed > 80 ? 'danger' : (pctUsed > 50 ? 'warning' : 'success')) + '" style="width: ' + pctUsed + '%"></div>';
        budgetCards[2].querySelector('.card-body').appendChild(progressBar);

        const remainLabel = document.createElement('div');
        remainLabel.className = 'small text-muted mt-1';
        remainLabel.textContent = 'Sisa: Rp ' + remaining.toLocaleString('id-ID') + ' (' + pctUsed + '% terpakai)';
        budgetCards[2].querySelector('.card-body').appendChild(remainLabel);
    }

    // ── Cookie: Last event filter ──
    const eventFilterKey = 'kadiv_last_event_filter';
    const savedEventFilter = localStorage.getItem(eventFilterKey) || 'all';
});
</script>
@endpush
@endsection
