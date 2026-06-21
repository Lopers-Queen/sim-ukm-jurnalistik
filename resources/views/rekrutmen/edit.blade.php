@extends('layouts.app')
@section('title', 'Edit Rekrutmen — SIM UKM Jurnalistik')
@section('page-title', 'Edit Rekrutmen')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('rekrutmen.index') }}">Rekrutmen</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Edit: {{ $rekrutmen->judul }}</h5></div>
    <div class="card-body"><form method="POST" action="{{ route('rekrutmen.update', $rekrutmen) }}">@csrf @method('PUT') @include('rekrutmen._form', ['rekrutmen' => $rekrutmen])
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
        <a href="{{ route('rekrutmen.index') }}" class="btn btn-outline-secondary">Batal</a></div></form></div></div>
</div></div>
@endsection
