@extends('layouts.app')
@section('title', 'Laporan Event — SIM UKM Jurnalistik')
@section('page-title', 'Laporan Event')
@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Event</h4>
        <p class="text-muted mb-0">Ringkasan seluruh event UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.event.pdf') }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center py-3">
                <h3 class="fw-bold text-primary mb-0">{{ $events->count() }}</h3>
                <small class="text-muted">Total Event</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center py-3">
                <h3 class="fw-bold text-success mb-0">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h3>
                <small class="text-muted">Total Anggaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center py-3">
                <h3 class="fw-bold text-info mb-0">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</h3>
                <small class="text-muted">Total Realisasi</small>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>PIC</th>
                        <th>Status</th>
                        <th class="text-end">Anggaran</th>
                        <th class="text-end">Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $i => $event)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <strong>{{ $event->nama_event }}</strong>
                            @if($event->lokasi)<br><small class="text-muted">{{ $event->lokasi }}</small>@endif
                        </td>
                        <td>{{ $event->tanggal_mulai->format('d/m/Y') }}
                            @if($event->tanggal_selesai) - {{ $event->tanggal_selesai->format('d/m/Y') }}@endif
                        </td>
                        <td>{{ $event->pic->nama_lengkap ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ match($event->status) {
                                'selesai' => 'success', 'aktif' => 'primary', 'direncanakan' => 'warning',
                                'batal' => 'danger', default => 'secondary'
                            } }}">{{ ucfirst($event->status) }}</span>
                        </td>
                        <td class="text-end">Rp {{ number_format($event->anggaran_total, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($event->anggaranEvent->sum('jumlah_realisasi'), 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada event terdaftar</td></tr>
                    @endforelse
                </tbody>
                @if($events->count())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">TOTAL</td>
                        <td class="text-end">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
