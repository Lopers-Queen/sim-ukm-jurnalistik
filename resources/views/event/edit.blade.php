@extends('layouts.app')
@section('title', 'Edit Event — SIM UKM Jurnalistik')
@section('page-title', 'Edit Event')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('event.index') }}">Event</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card"><div class="card-header bg-transparent"><h5 class="mb-0 fw-semibold">Edit: {{ $event->nama_event }}</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('event.update', $event) }}">@csrf @method('PUT')
            @include('event._form', ['event' => $event])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
                <a href="{{ route('event.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
