@extends('layouts.app')

@section('title', 'Tambah Anggota — SIM UKM Jurnalistik')
@section('page-title', 'Tambah Anggota Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-semibold">Form Tambah Anggota</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('anggota.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('anggota._form')

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Anggota
                        </button>
                        <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="card mt-3 border-info">
            <div class="card-body small">
                <div class="fw-semibold mb-1"><i class="bi bi-info-circle text-info me-1"></i>Informasi</div>
                <ul class="mb-0 ps-3">
                    <li>Password default akan di-generate otomatis dari tanggal lahir (format DDMMYYYY).</li>
                    <li>Anggota wajib mengganti password saat login pertama kali.</li>
                    <li>Role Spatie akan otomatis di-assign berdasarkan jabatan yang dipilih.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
