@extends('layouts.app')
@section('title', 'Dashboard — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4" id="welcomeCard">
    {{-- Welcome Card --}}
    <div class="col-12">
        <div class="card bg-primary bg-gradient text-white">
            <div class="card-body py-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg me-3 bg-white bg-opacity-25">
                            {{ strtoupper(substr($user->nama_lengkap, 0, 2)) }}
                        </div>
                        <div>
                            <h5 class="mb-1">Selamat Datang, {{ $user->nama_lengkap }}!</h5>
                            <p class="mb-0 opacity-75">{{ $user->jabatan_lengkap }} — Divisi {{ ucfirst(str_replace('_', ' & ', $user->divisi ?? '-')) }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light btn-opacity-25" id="dismissWelcome" title="Sembunyikan">
                        <i class="bi bi-x-lg"></i>
                    </button>
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
                <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i>Info Keanggotaan</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">NIM</td>
                        <td class="fw-semibold">{{ $user->nim }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Divisi</td>
                        <td class="fw-semibold">{{ ucfirst(str_replace('_', ' & ', $user->divisi ?? '-')) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge bg-success">{{ ucfirst($user->status_keanggotaan) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bergabung</td>
                        <td class="fw-semibold">{{ $user->tanggal_bergabung?->format('d M Y') ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Cookie: Dismiss welcome card ──
    const dismissKey = 'anggota_welcome_dismissed';
    const welcomeCard = document.getElementById('welcomeCard');
    const dismissBtn = document.getElementById('dismissWelcome');

    if (localStorage.getItem(dismissKey) === 'true' && welcomeCard) {
        welcomeCard.style.display = 'none';
    }

    if (dismissBtn) {
        dismissBtn.addEventListener('click', function() {
            localStorage.setItem(dismissKey, 'true');
            welcomeCard.style.transition = 'opacity 0.3s';
            welcomeCard.style.opacity = '0';
            setTimeout(() => welcomeCard.style.display = 'none', 300);
        });
    }

    // ── Cookie: Event reminder preference ──
    const eventPrefKey = 'anggota_event_reminder_pref';
    const savedPref = localStorage.getItem(eventPrefKey) || 'countdown';

    // Show countdown to next event if preference is 'countdown'
    if (savedPref === 'countdown') {
        const eventCards = document.querySelectorAll('[data-event-date]');
        eventCards.forEach(card => {
            const eventDate = new Date(card.dataset.eventDate);
            const now = new Date();
            const diff = Math.ceil((eventDate - now) / (1000 * 60 * 60 * 24));
            if (diff > 0) {
                const countdown = document.createElement('div');
                countdown.className = 'small text-info fw-semibold mt-1';
                countdown.textContent = diff + ' hari lagi';
                card.appendChild(countdown);
            }
        });
    }
});
</script>
@endpush
