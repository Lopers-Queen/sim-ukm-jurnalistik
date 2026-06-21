@extends('layouts.app')
@section('title', 'Dashboard Staf — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard Staf')

@section('content')
<div class="row g-4 mb-4">
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
                <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Naskah Saya</h6>
            </div>
            <div class="card-body">
                @forelse($naskahSaya as $naskah)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-semibold">{{ Str::limit($naskah->judul, 40) }}</div>
                            <div class="small text-muted">{{ $naskah->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge bg-{{ $naskah->status === 'published' ? 'success' : ($naskah->status === 'review' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($naskah->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-file-earmark-x fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada naskah</p>
                    </div>
                @endforelse

                <a href="{{ route('naskah.create') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    <i class="bi bi-plus-circle me-1"></i>Tulis Naskah Baru
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Jadwal Piket Saya --}}
@if($jadwalPiket && $jadwalPiket->count() > 0)
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2"></i>Jadwal Piket Saya</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($jadwalPiket as $shift)
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                            <i class="bi bi-calendar-day me-1"></i>{{ ucfirst($shift->hari) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Cookie: Naskah sort preference ──
    const sortKey = 'staf_naskah_sort';
    const savedSort = localStorage.getItem(sortKey) || 'date';

    // ── Cookie: Last event viewed ──
    const eventViewedKey = 'staf_last_event_viewed';
    const eventLinks = document.querySelectorAll('a[href*="event"]');
    eventLinks.forEach(link => {
        link.addEventListener('click', () => {
            localStorage.setItem(eventViewedKey, link.getAttribute('href'));
        });
    });
});
</script>
@endpush
