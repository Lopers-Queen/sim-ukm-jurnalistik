@extends('layouts.app')
@section('title', 'Edit Periode — SIM UKM Jurnalistik')
@section('page-title', 'Edit Periode')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('periode.index') }}">Periode</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Edit: {{ $periode->nama_periode }}</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('periode.update', $periode) }}">@csrf @method('PUT')
            @include('periode._form', ['periode' => $periode])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
                <a href="{{ route('periode.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
