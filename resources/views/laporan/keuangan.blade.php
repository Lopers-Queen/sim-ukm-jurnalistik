@extends('layouts.app')
@section('title', 'Laporan Keuangan — SIM UKM Jurnalistik')
@section('page-title', 'Laporan Keuangan')
@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Keuangan</h4>
        <p class="text-muted mb-0">Ringkasan anggaran divisi dan event UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.keuangan.pdf') }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($totalDivisiAnggaran, 0, ',', '.') }}</h5>
                <small class="text-muted">Divisi — Anggaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-success mb-0">Rp {{ number_format($totalDivisiTerpakai, 0, ',', '.') }}</h5>
                <small class="text-muted">Divisi — Terpakai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-info mb-0">Rp {{ number_format($totalEventAnggaran, 0, ',', '.') }}</h5>
                <small class="text-muted">Event — Dianggarkan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center py-3">
                <h5 class="fw-bold text-warning mb-0">Rp {{ number_format($totalEventRealisasi, 0, ',', '.') }}</h5>
                <small class="text-muted">Event — Realisasi</small>
            </div>
        </div>
    </div>
</div>

{{-- Anggaran Divisi --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-building me-1"></i> Anggaran per Divisi</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Divisi</th><th>Bulan / Tahun</th><th class="text-end">Anggaran</th><th class="text-end">Terpakai</th><th class="text-end">Sisa</th></tr>
                </thead>
                <tbody>
                    @forelse($anggaranDivisi as $i => $ad)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $ad->divisi)) }}</td>
                        <td>{{ $ad->bulan }}/{{ $ad->tahun }}</td>
                        <td class="text-end">Rp {{ number_format($ad->jumlah_anggaran, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($ad->jumlah_terpakai, 0, ',', '.') }}</td>
                        <td class="text-end fw-semibold {{ $ad->sisa_anggaran < 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($ad->sisa_anggaran, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data anggaran divisi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Anggaran Event --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-1"></i> Anggaran per Event</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Event</th><th>Item</th><th>Kategori</th><th class="text-end">Qty</th><th class="text-end">Dianggarkan</th><th class="text-end">Realisasi</th></tr>
                </thead>
                <tbody>
                    @forelse($anggaranEvent as $i => $ae)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $ae->event->nama_event ?? '-' }}</td>
                        <td>{{ $ae->item }}</td>
                        <td>{{ $ae->kategori ?? '-' }}</td>
                        <td class="text-end">{{ $ae->qty }}</td>
                        <td class="text-end">Rp {{ number_format($ae->jumlah_dianggarkan, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($ae->jumlah_realisasi ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data anggaran event</td></tr>
                    @endforelse
                </tbody>
                @if($anggaranEvent->count())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">TOTAL</td>
                        <td class="text-end">Rp {{ number_format($totalEventAnggaran, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($totalEventRealisasi, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
