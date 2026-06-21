@extends('layouts.app')
@section('title', 'Laporan — ' . $event->nama_event)
@section('page-title', 'Detail Laporan Pasca Event')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('event.show', $event) }}">{{ $event->nama_event }}</a></li>
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Laporan: {{ $event->nama_event }}</h4>
        <p class="text-muted mb-0">Disusun oleh {{ $laporan->pelapor->nama_lengkap ?? '-' }} — {{ $laporan->created_at?->format('d M Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-{{ $laporan->status === 'final' ? 'success' : ($laporan->status === 'draft' ? 'warning' : 'primary') }} fs-6">
            {{ ucfirst($laporan->status) }}
        </span>
        @if($laporan->status === 'draft')
            @can('event.edit')
            <form method="POST" action="{{ route('laporan-event.finalize', [$event, $laporan]) }}">
                @csrf
                <button class="btn btn-success" onclick="return confirm('Finalisasi laporan ini?')"><i class="bi bi-check-circle me-1"></i>Finalisasi</button>
            </form>
            @endcan
        @endif
        <a href="{{ route('laporan-event.export-pdf', [$event, $laporan]) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('event.show', $event) }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-semibold">Notulensi</h6></div>
            <div class="card-body">{!! nl2br(e($laporan->notulensi)) !!}</div>
        </div>

        @if($laporan->daftar_peserta)
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-semibold">Daftar Peserta</h6></div>
            <div class="card-body">{!! nl2br(e($laporan->daftar_peserta)) !!}</div>
        </div>
        @endif

        <div class="card mb-4">
            <div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-semibold">Evaluasi</h6></div>
            <div class="card-body">
                <h6 class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Kendala</h6>
                <p>{!! nl2br(e($laporan->kendala)) !!}</p>
                <h6 class="text-success"><i class="bi bi-trophy me-1"></i>Pencapaian</h6>
                <p>{!! nl2br(e($laporan->pencapaian)) !!}</p>
                <h6 class="text-info"><i class="bi bi-lightbulb me-1"></i>Saran Perbaikan</h6>
                <p>{!! nl2br(e($laporan->saran_perbaikan)) !!}</p>
                <div class="d-flex align-items-center gap-1 mt-3">
                    <strong>Rating:</strong>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $laporan->rating_internal ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                    @endfor
                    <span class="ms-1">({{ $laporan->rating_internal }}/5)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-semibold">Laporan Keuangan</h6></div>
            <div class="card-body">
                <div class="mb-2"><small class="text-muted">Total Pemasukan</small><div class="fw-bold text-success">Rp {{ number_format($laporan->total_pemasukan, 0, ',', '.') }}</div></div>
                <div class="mb-2"><small class="text-muted">Total Pengeluaran</small><div class="fw-bold text-danger">Rp {{ number_format($laporan->total_pengeluaran, 0, ',', '.') }}</div></div>
                <hr>
                <div><small class="text-muted">Sisa Anggaran</small><div class="fs-5 fw-bold {{ $laporan->sisa_anggaran >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($laporan->sisa_anggaran, 0, ',', '.') }}</div></div>
            </div>
        </div>

        @if($laporan->dokumentasi_video_link)
        <div class="card mb-4">
            <div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-semibold">Video</h6></div>
            <div class="card-body">
                <a href="{{ $laporan->dokumentasi_video_link }}" target="_blank" class="btn btn-outline-primary w-100">
                    <i class="bi bi-play-circle me-1"></i>Tonton Video
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
