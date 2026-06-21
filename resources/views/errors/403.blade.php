@extends('layouts.app')
@section('title', 'Akses Ditolak — SIM UKM Jurnalistik')
@section('page-title', 'Akses Ditolak')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="text-center">
        <div class="mb-4">
            <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
        </div>
        <h2 class="fw-bold text-danger mb-2">403 — Akses Ditolak</h2>
        <p class="text-muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.<br>Hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-speedometer2 me-1"></i>Kembali ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection
