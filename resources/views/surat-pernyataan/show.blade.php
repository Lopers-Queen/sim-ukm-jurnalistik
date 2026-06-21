@extends('layouts.app')
@section('title', 'Detail Surat — SIM UKM Jurnalistik')
@section('page-title', 'Detail Surat Pernyataan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('surat-pernyataan.index') }}">Surat Pernyataan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Surat Pernyataan #{{ $suratPernyataan->id }}</h5>
            @php $badge = match($suratPernyataan->status) {
                'pending_ttd' => 'warning', 'menunggu_konfirmasi' => 'info',
                'disetujui' => 'success', 'ditolak' => 'danger', default => 'secondary'
            }; @endphp
            <span class="badge bg-{{ $badge }} fs-6">{{ ucwords(str_replace('_',' ',$suratPernyataan->status)) }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-2"><i class="bi bi-person me-1"></i>Informasi Anggota</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" style="width:120px">Nama</td><td>{{ $suratPernyataan->anggota?->nama_lengkap }}</td></tr>
                        <tr><td class="text-muted">NIM</td><td>{{ $suratPernyataan->anggota?->nim }}</td></tr>
                        <tr><td class="text-muted">Divisi</td><td>{{ $suratPernyataan->anggota?->divisi_label }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-2"><i class="bi bi-calendar-event me-1"></i>Informasi Event</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" style="width:120px">Event</td><td>{{ $suratPernyataan->event?->nama_event }}</td></tr>
                        <tr><td class="text-muted">Tanggal</td><td>{{ $suratPernyataan->event?->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Lokasi</td><td>{{ $suratPernyataan->event?->lokasi ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            @if($suratPernyataan->approver)
            <div class="alert alert-{{ $suratPernyataan->status === 'disetujui' ? 'success' : 'danger' }} py-2 small">
                <strong>{{ $suratPernyataan->status === 'disetujui' ? 'Disetujui' : 'Ditolak' }} oleh:</strong>
                {{ $suratPernyataan->approver->nama_lengkap }} pada {{ $suratPernyataan->tanggal_approval?->translatedFormat('d F Y, H:i') }}
                @if($suratPernyataan->alasan_penolakan)<br><strong>Alasan:</strong> {{ $suratPernyataan->alasan_penolakan }}@endif
            </div>
            @endif

            {{-- Upload TTD --}}
            @if($suratPernyataan->status === 'pending_ttd')
            <div class="card bg-light mb-3"><div class="card-body">
                <h6 class="fw-semibold">Upload Tanda Tangan</h6>
                <form method="POST" action="{{ route('surat-pernyataan.upload-ttd', $suratPernyataan) }}" enctype="multipart/form-data">@csrf
                    <div class="input-group"><input type="file" class="form-control" name="file_ttd" accept="image/*" required>
                    <button class="btn btn-primary"><i class="bi bi-upload me-1"></i>Upload</button></div>
                </form>
            </div></div>
            @endif

            {{-- TTD Preview --}}
            @if($suratPernyataan->file_ttd)
            <div class="mb-3">
                <h6 class="fw-semibold">Tanda Tangan</h6>
                <img src="{{ asset('storage/' . $suratPernyataan->file_ttd) }}" class="border rounded" style="max-height:120px" alt="TTD">
            </div>
            @endif

            {{-- Approval Actions --}}
            @if($suratPernyataan->status === 'menunggu_konfirmasi')
            @can('surat-pernyataan.approve')
            <div class="d-flex gap-2 mb-3">
                <form method="POST" action="{{ route('surat-pernyataan.approve', $suratPernyataan) }}">@csrf
                    <button class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Setujui</button></form>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-lg me-1"></i>Tolak</button>
            </div>
            @endcan
            @endif

            <div class="d-flex gap-2 mt-3">
                @if($suratPernyataan->file_pdf)<a href="{{ route('surat-pernyataan.download', $suratPernyataan) }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Download PDF</a>@endif
                <a href="{{ route('surat-pernyataan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div></div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="{{ route('surat-pernyataan.reject', $suratPernyataan) }}">@csrf
    <div class="modal-header"><h5 class="modal-title">Tolak Surat Pernyataan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><textarea class="form-control" name="alasan_penolakan" rows="3" placeholder="Alasan penolakan (min 10 karakter)..." required minlength="10"></textarea></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Tolak</button></div>
    </form>
</div></div></div>
@endsection
