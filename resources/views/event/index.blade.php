@extends('layouts.app')
@section('title', 'Manajemen Event — SIM UKM Jurnalistik')
@section('page-title', 'Manajemen Event')
@section('breadcrumb')
<li class="breadcrumb-item active">Event</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Event</h4><p class="text-muted mb-0">Kelola event dan kegiatan UKM</p></div>
    @can('event.create')
    <a href="{{ route('event.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Event</a>
    @endcan
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" action="{{ route('event.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari nama event..."></div></div>
        <div class="col-md-3"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            @foreach(['draft','direncanakan','aktif','selesai','batal'] as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        @if(request('search') || request('status'))
        <div class="col-md-3"><a href="{{ route('event.index') }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        @endif
    </form>
</div></div>
@push('scripts')
<script>
let t;document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(t);t=setTimeout(()=>this.form.submit(),500)});
</script>
@endpush
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Nama Event</th><th>Tanggal</th><th>Lokasi</th><th>PIC</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($events as $i => $event)
        <tr>
            <td>{{ $events->firstItem() + $i }}</td>
            <td><div class="fw-semibold">{{ $event->nama_event }}</div><div class="small text-muted">{{ Str::limit($event->deskripsi, 50) }}</div></td>
            <td class="small">{{ $event->tanggal_mulai->format('d/m/Y') }}{{ $event->tanggal_selesai ? ' - '.$event->tanggal_selesai->format('d/m/Y') : '' }}</td>
            <td>{{ $event->lokasi ?? '-' }}</td>
            <td>{{ $event->pic?->nama_lengkap ?? '-' }}</td>
            <td><span class="badge bg-{{ $event->status_badge }}">{{ $event->status_label }}</span></td>
            <td><div class="btn-group btn-group-sm">
                <a href="{{ route('event.show', $event) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                @can('event.edit')<a href="{{ route('event.edit', $event) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>@endcan
                @can('event.delete')<form method="POST" action="{{ route('event.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>@endcan
            </div></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-1 d-block mb-2"></i>Belum ada event</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($events->hasPages())<div class="card-footer">{{ $events->links() }}</div>@endif
</div>
@endsection
