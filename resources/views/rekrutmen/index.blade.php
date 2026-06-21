@extends('layouts.app')
@section('title', 'Rekrutmen — SIM UKM Jurnalistik')
@section('page-title', 'Rekrutmen Anggota')
@section('breadcrumb')
<li class="breadcrumb-item active">Rekrutmen</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Rekrutmen</h4><p class="text-muted mb-0">Kelola rekrutmen anggota baru</p></div>
    @can('rekrutmen.create')<a href="{{ route('rekrutmen.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Rekrutmen</a>@endcan
</div>
<div class="row g-4">
@forelse($rekrutmen as $item)
<div class="col-md-6">
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold mb-0">{{ $item->nama_rekrutmen }}</h5>
                @php $badge = match($item->status) { 'dibuka' => 'success', 'draft' => 'secondary', 'ditutup' => 'warning', default => 'info' }; @endphp
                <span class="badge bg-{{ $badge }}">{{ ucfirst($item->status) }}</span>
            </div>
            <p class="text-muted small mb-2">Periode: {{ $item->periode?->nama_periode ?? '-' }}</p>
            <p class="small"><i class="bi bi-calendar me-1"></i>{{ $item->tanggal_buka->format('d/m/Y') }} - {{ $item->tanggal_tutup->format('d/m/Y') }}</p>
            <p class="small text-muted">{{ Str::limit($item->deskripsi, 100) }}</p>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('rekrutmen.show', $item) }}" class="btn btn-outline-info"><i class="bi bi-eye me-1"></i>Detail</a>
                @can('rekrutmen.edit')<a href="{{ route('rekrutmen.edit', $item) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                @can('rekrutmen.delete')<form method="POST" action="{{ route('rekrutmen.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus rekrutmen &quot;{{ $item->nama_rekrutmen }}&quot;?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button></form>@endcan
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12"><div class="text-center text-muted py-5"><i class="bi bi-person-plus fs-1 d-block mb-2"></i>Belum ada rekrutmen</div></div>
@endforelse
</div>
@if($rekrutmen->hasPages())<div class="mt-4">{{ $rekrutmen->links() }}</div>@endif
@endsection
