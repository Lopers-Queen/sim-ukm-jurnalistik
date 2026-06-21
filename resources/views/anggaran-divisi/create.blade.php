@extends('layouts.app')
@section('title', 'Tambah Anggaran Divisi — SIM UKM Jurnalistik')
@section('page-title', 'Tambah Anggaran Divisi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('anggaran-divisi.index') }}">Anggaran Divisi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Form Anggaran Baru</h5></div>
    <div class="card-body"><form method="POST" action="{{ route('anggaran-divisi.store') }}">@csrf @include('anggaran-divisi._form')
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
        <a href="{{ route('anggaran-divisi.index') }}" class="btn btn-outline-secondary">Batal</a></div></form></div></div>
</div></div>
@endsection
