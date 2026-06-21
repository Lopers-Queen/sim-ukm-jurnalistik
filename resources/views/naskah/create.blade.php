@extends('layouts.app')
@section('title', 'Tulis Naskah — SIM UKM Jurnalistik')
@section('page-title', 'Tulis Naskah Baru')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('naskah.index') }}">Naskah Redaksi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Form Naskah Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('naskah.store') }}">@csrf
            @include('naskah._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Draft</button>
                <a href="{{ route('naskah.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
