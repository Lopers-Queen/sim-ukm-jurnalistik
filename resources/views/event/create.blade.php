@extends('layouts.app')
@section('title', 'Buat Event — SIM UKM Jurnalistik')
@section('page-title', 'Buat Event Baru')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('event.index') }}">Event</a></li>
<li class="breadcrumb-item active">Buat</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Form Event Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('event.store') }}">@csrf
            @include('event._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Event</button>
                <a href="{{ route('event.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
