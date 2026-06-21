@extends('layouts.app')
@section('title', 'Detail Notulensi — SIM UKM Jurnalistik')
@section('page-title', 'Detail Notulensi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('notulensi.index') }}">Notulensi</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">{{ $notulensi->judul }}</h5>
            <span class="badge bg-info">{{ $notulensi->jenis_rapat_label }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal:</strong> {{ $notulensi->tanggal_rapat->translatedFormat('d F Y') }}</div>
                <div class="col-md-4"><strong>Lokasi:</strong> {{ $notulensi->lokasi ?? '-' }}</div>
                <div class="col-md-4"><strong>Pencatat:</strong> {{ $notulensi->pencatat?->nama_lengkap ?? '-' }}</div>
            </div>
            <h6 class="fw-semibold">Isi Notulensi</h6>
            <div class="bg-light rounded p-4 mb-4" style="white-space: pre-wrap;">{{ $notulensi->isi_notulensi }}</div>
            <div class="d-flex gap-2">
                @can('notulensi.edit')<a href="{{ route('notulensi.edit', $notulensi) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                <a href="{{ route('notulensi.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div></div>
@endsection
