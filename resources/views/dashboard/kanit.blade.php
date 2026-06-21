@extends('layouts.app')
@section('title', 'Dashboard Kanit — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard Unit ' . ucfirst($unit ?? ''))

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Staf Unit</div>
                        <div class="fs-3 fw-bold mt-1">{{ $stafUnit }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($naskahData)
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card stat-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Naskah Perlu Review</div>
                        <div class="fs-3 fw-bold mt-1">{{ $naskahData['review'] }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-pencil-square fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card stat-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Naskah</div>
                        <div class="fs-3 fw-bold mt-1">{{ $naskahData['total'] }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-file-earmark-text fs-3 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row g-4">
    {{-- Jadwal Piket Unit --}}
    @if($jadwalPiket && $jadwalPiket->count() > 0)
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2"></i>Jadwal Piket Unit</h6>
            </div>
            <div class="card-body">
                @foreach($jadwalPiket as $hari => $shifts)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="fw-semibold text-uppercase small text-muted mb-1">{{ ucfirst($hari) }}</div>
                        @foreach($shifts as $shift)
                            <span class="badge bg-primary bg-opacity-10 text-primary me-1 mb-1">
                                {{ $shift->anggota->nama_lengkap ?? '?' }}
                            </span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="{{ $jadwalPiket && $jadwalPiket->count() > 0 ? 'col-lg-6' : 'col-lg-6' }}">
        <div class="card h-100 mb-4">
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

        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('jadwal.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calendar3 me-1"></i>Kelola Jadwal Piket
                </a>
                @if($naskahData)
                <a href="{{ route('naskah.index', ['status' => 'review']) }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-pencil-square me-1"></i>Review Naskah ({{ $naskahData['review'] }})
                </a>
                @endif
                <a href="{{ route('event.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-calendar-event me-1"></i>Lihat Event
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Cookie: Schedule view preference (weekly/daily) ──
    const scheduleViewKey = 'kanit_{{ $unit }}_schedule_view';
    const savedScheduleView = localStorage.getItem(scheduleViewKey) || 'weekly';

    // ── Cookie: Staff display preference (grid/list) ──
    const staffDisplayKey = 'kanit_{{ $unit }}_staff_display';
    const savedStaffDisplay = localStorage.getItem(staffDisplayKey) || 'grid';

    @if($naskahData)
    // ── Cookie: Naskah filter preference ──
    const naskahFilterKey = 'kanit_redaksi_naskah_filter';
    const savedNaskahFilter = localStorage.getItem(naskahFilterKey) || 'review';
    @endif
});
</script>
@endpush
@endsection
