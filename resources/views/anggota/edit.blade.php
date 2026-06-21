@extends('layouts.app')

@section('title', 'Edit Anggota — SIM UKM Jurnalistik')
@section('page-title', 'Edit Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-semibold">Edit: {{ $anggota->nama_lengkap }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('anggota.update', $anggota) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('anggota._form', ['anggota' => $anggota])

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Perbarui
                        </button>
                        <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
