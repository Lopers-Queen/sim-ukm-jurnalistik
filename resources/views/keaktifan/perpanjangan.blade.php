@extends('layouts.app')
@section('title', 'Perpanjangan Keaktifan — SIM UKM Jurnalistik')
@section('page-title', 'Perpanjangan Keaktifan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Perpanjangan Keaktifan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-arrow-repeat me-2"></i>Perpanjangan Keaktifan</h5>
            </div>
            <div class="card-body">
                @if($periodeAktif)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Periode aktif: <strong>{{ $periodeAktif->nama_periode }}</strong>
                </div>
                @endif

                <div class="mb-4">
                    <h6 class="fw-semibold">Informasi Anda</h6>
                    <table class="table table-sm table-borderless">
                        <tr><td class="text-muted" style="width:140px">Nama</td><td>{{ $user->nama_lengkap }}</td></tr>
                        <tr><td class="text-muted">NIM</td><td>{{ $user->nim }}</td></tr>
                        <tr><td class="text-muted">Divisi</td><td>{{ $user->divisi_label }}</td></tr>
                        <tr>
                            <td class="text-muted">Status Saat Ini</td>
                            <td>
                                <span class="badge bg-{{ $user->status_keanggotaan === 'aktif' ? 'success' : ($user->status_keanggotaan === 'pasif' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($user->status_keanggotaan) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                @if($user->status_keanggotaan === 'aktif')
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    Status Anda sudah <strong>Aktif</strong>. Tidak perlu perpanjangan.
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Status Anda saat ini <strong>{{ ucfirst($user->status_keanggotaan) }}</strong>.
                    Klik tombol di bawah untuk mengajukan perpanjangan keaktifan.
                </div>

                <form method="POST" action="{{ route('keaktifan.submit-perpanjangan') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100"
                            onclick="return confirm('Apakah Anda yakin ingin mengajukan perpanjangan keaktifan?')">
                        <i class="bi bi-check-lg me-2"></i>Ajukan Perpanjangan Keaktifan
                    </button>
                </form>
                @endif

                <div class="mt-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
