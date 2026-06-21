@extends('layouts.app')
@section('title', 'Buat Notulensi — SIM UKM Jurnalistik')
@section('page-title', 'Buat Notulensi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('notulensi.index') }}">Notulensi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Form Notulensi Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('notulensi.store') }}">@csrf
            @include('notulensi._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                <a href="{{ route('notulensi.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
