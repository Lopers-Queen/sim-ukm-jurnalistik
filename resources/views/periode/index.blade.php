@extends('layouts.app')
@section('title', 'Periode Kepengurusan — SIM UKM Jurnalistik')
@section('page-title', 'Periode Kepengurusan')
@section('breadcrumb')
<li class="breadcrumb-item active">Periode</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Periode</h4><p class="text-muted mb-0">Kelola periode kepengurusan UKM</p></div>
    @can('periode.create')<a href="{{ route('periode.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Periode</a>@endcan
</div>
<div class="row g-4">
@forelse($periodes as $periode)
<div class="col-md-6 col-xl-4">
    <div class="card h-100 {{ $periode->status === 'aktif' ? 'border-success' : '' }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold mb-0">{{ $periode->nama_periode }}</h5>
                @php $badge = match($periode->status) { 'aktif' => 'success', 'upcoming' => 'info', default => 'secondary' }; @endphp
                <span class="badge bg-{{ $badge }}">{{ ucfirst($periode->status) }}</span>
            </div>
            <p class="text-muted mb-3">{{ $periode->tahun_mulai }} — {{ $periode->tahun_selesai }}</p>
            <div class="d-flex gap-4 mb-3">
                <div class="text-center"><div class="fs-4 fw-bold text-primary">{{ $periode->riwayat_kepengurusan_count }}</div><div class="small text-muted">Pengurus</div></div>
                <div class="text-center"><div class="fs-4 fw-bold text-info">{{ $periode->events_count }}</div><div class="small text-muted">Event</div></div>
            </div>
            <div class="btn-group btn-group-sm w-100">
                <a href="{{ route('periode.show', $periode) }}" class="btn btn-outline-info"><i class="bi bi-eye me-1"></i>Detail</a>
                @can('periode.edit')<a href="{{ route('periode.edit', $periode) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                @can('periode.delete')<form method="POST" action="{{ route('periode.destroy', $periode) }}" class="d-inline" onsubmit="return confirm('Hapus periode &quot;{{ $periode->nama_periode }}&quot;?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button></form>@endcan
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12"><div class="text-center text-muted py-5"><i class="bi bi-calendar-range fs-1 d-block mb-2"></i>Belum ada periode kepengurusan</div></div>
@endforelse
</div>
@if($periodes->hasPages())<div class="mt-4">{{ $periodes->links() }}</div>@endif
@endsection
