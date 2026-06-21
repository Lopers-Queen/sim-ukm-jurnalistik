@extends('layouts.app')
@section('title', 'Edit Anggaran Event — SIM UKM Jurnalistik')
@section('page-title', 'Edit Item Anggaran Event')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('anggaran-event.index') }}">Anggaran Event</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Edit Item Anggaran</h5></div>
    <div class="card-body"><form method="POST" action="{{ route('anggaran-event.update', $anggaranEvent) }}">@csrf @method('PUT') @include('anggaran-event._form', ['anggaranEvent' => $anggaranEvent])
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
        <a href="{{ route('anggaran-event.index') }}" class="btn btn-outline-secondary">Batal</a></div></form></div></div>
</div></div>
@endsection
