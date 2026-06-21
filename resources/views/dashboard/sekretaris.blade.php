@extends('layouts.app')
@section('title', 'Dashboard Sekretaris — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard Sekretaris')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Notulensi</div>
                        <div class="fs-3 fw-bold mt-1">{{ $totalNotulensi }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-journal-text fs-3 text-primary"></i>
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
                        <div class="text-muted small fw-semibold text-uppercase">Total Anggota</div>
                        <div class="fs-3 fw-bold mt-1">{{ $totalAnggota }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people fs-3 text-success"></i>
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
                        <div class="text-muted small fw-semibold text-uppercase">Anggota Aktif</div>
                        <div class="fs-3 fw-bold mt-1">{{ $anggotaAktif }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-person-check fs-3 text-info"></i>
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

<div class="row g-4 mb-4">
    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('notulensi.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-journal-plus me-1"></i>Buat Notulensi
                </a>
                <a href="{{ route('notulensi.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-journal-text me-1"></i>Lihat Semua Notulensi
                </a>
                <a href="{{ route('event.create') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-calendar-plus me-1"></i>Buat Event
                </a>
                <a href="{{ route('surat-pernyataan.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-envelope me-1"></i>Surat Pernyataan ({{ $suratPending }})
                </a>
            </div>
        </div>
    </div>

    {{-- Notulensi Terbaru --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-journal-text me-2"></i>Notulensi Terbaru</h6>
            </div>
            <div class="card-body">
                @forelse($notulensiTerbaru as $n)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3 text-center" style="min-width: 50px;">
                            <div class="fw-bold text-primary">{{ $n->tanggal_rapat->format('d') }}</div>
                            <div class="small text-muted">{{ $n->tanggal_rapat->format('M') }}</div>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ Str::limit($n->judul, 40) }}</div>
                            <div class="small text-muted">{{ ucfirst(str_replace('_', ' ', $n->jenis_rapat)) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada notulensi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Event Mendatang --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-2"></i>Event Mendatang</h6>
            </div>
            <div class="card-body">
                @forelse($eventMendatang as $event)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="bg-info bg-opacity-10 rounded-3 p-2 me-3 text-center" style="min-width: 50px;">
                            <div class="fw-bold text-info">{{ $event->tanggal_mulai->format('d') }}</div>
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cookie: Event view preference (calendar vs list)
    const eventViewKey = 'sekretaris_event_view_pref';
    const savedView = localStorage.getItem(eventViewKey) || 'list';

    // Cookie: Last notulensi page for quick return
    const notulensiPageKey = 'sekretaris_last_notulensi_page';
    const notulensiLinks = document.querySelectorAll('a[href*="notulensi"]');
    notulensiLinks.forEach(link => {
        link.addEventListener('click', () => {
            localStorage.setItem(notulensiPageKey, link.getAttribute('href'));
        });
    });
});
</script>
@endpush
@endsection
