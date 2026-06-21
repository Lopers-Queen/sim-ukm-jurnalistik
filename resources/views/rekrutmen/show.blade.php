@extends('layouts.app')
@section('title', 'Detail Rekrutmen — SIM UKM Jurnalistik')
@section('page-title', 'Detail Rekrutmen')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('rekrutmen.index') }}">Rekrutmen</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">{{ $rekrutmen->nama_rekrutmen }}</h5>
            @php $badge = match($rekrutmen->status) { 'dibuka' => 'success', 'draft' => 'secondary', 'ditutup' => 'warning', default => 'info' }; @endphp
            <span class="badge bg-{{ $badge }}">{{ ucfirst($rekrutmen->status) }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Periode:</strong> {{ $rekrutmen->periode?->nama_periode ?? '-' }}</div>
                <div class="col-md-4"><strong>Buka:</strong> {{ $rekrutmen->tanggal_buka->translatedFormat('d F Y') }}</div>
                <div class="col-md-4"><strong>Tutup:</strong> {{ $rekrutmen->tanggal_tutup->translatedFormat('d F Y') }}</div>
            </div>
            @if($rekrutmen->deskripsi)<div class="mb-3"><h6 class="fw-semibold">Deskripsi</h6><div class="bg-light rounded p-3">{!! nl2br(e($rekrutmen->deskripsi)) !!}</div></div>@endif
            @if($rekrutmen->persyaratan)<div class="mb-3"><h6 class="fw-semibold">Persyaratan</h6><div class="bg-light rounded p-3">{!! nl2br(e($rekrutmen->persyaratan)) !!}</div></div>@endif
            <div class="d-flex gap-2">
                @can('rekrutmen.edit')<a href="{{ route('rekrutmen.edit', $rekrutmen) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                <a href="{{ route('rekrutmen.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div></div>
@endsection
