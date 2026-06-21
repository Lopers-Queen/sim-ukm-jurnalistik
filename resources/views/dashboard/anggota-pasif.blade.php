@extends('layouts.app')
@section('title', 'Dashboard — SIM UKM Jurnalistik')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    {{-- Status Alert --}}
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-body py-4">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-exclamation-triangle fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-warning">Status: Pasif</h5>
                        <p class="mb-0 text-muted">Keanggotaan Anda saat ini berstatus <strong>Pasif</strong>. Isi form keaktifan untuk mengaktifkan kembali keanggotaan Anda.</p>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('keaktifan.perpanjangan') }}" class="btn btn-warning">
                        <i class="bi bi-arrow-repeat me-1"></i>Perpanjang Keaktifan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
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
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold">{{ $user->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge bg-warning text-dark">Pasif</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bergabung</td>
                        <td class="fw-semibold">{{ $user->tanggal_bergabung?->format('d M Y') ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-text me-2"></i>Surat Pernyataan</h6>
            </div>
            <div class="card-body">
                @forelse($suratSaya as $surat)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-semibold">Surat #{{ $surat->id }}</div>
                            <div class="small text-muted">{{ $surat->created_at->format('d M Y') }}</div>
                        </div>
                        <span class="badge bg-{{ $surat->status === 'disetujui' ? 'success' : ($surat->status === 'ditolak' ? 'danger' : 'warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $surat->status)) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-file-earmark-x fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada surat pernyataan</p>
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
    // ── Cookie: Reactivation progress tracking ──
    const reactKey = 'pasif_reactivation_progress';
    const reactLink = document.querySelector('a[href*="keaktifan"]');
    if (reactLink) {
        reactLink.addEventListener('click', () => {
            localStorage.setItem(reactKey, 'started');
        });
    }

    // ── Cookie: Surat pernyataan expanded state ──
    const suratKey = 'pasif_surat_expanded_id';
    const suratBadges = document.querySelectorAll('.badge');
    suratBadges.forEach(badge => {
        badge.style.cursor = 'pointer';
        badge.addEventListener('click', () => {
            const suratId = badge.closest('.d-flex')?.dataset?.suratId;
            if (suratId) localStorage.setItem(suratKey, suratId);
        });
    });
});
</script>
@endpush
