@extends('layouts.app')
@section('title', 'Anggaran Event — SIM UKM Jurnalistik')
@section('page-title', 'Anggaran Event')
@section('breadcrumb')
<li class="breadcrumb-item active">Anggaran Event</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Anggaran Event</h4><p class="text-muted mb-0">Kelola anggaran per event</p></div>
    @can('anggaran-event.create')<a href="{{ route('anggaran-event.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Item</a>@endcan
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-8"><select class="form-select" name="event_id" onchange="this.form.submit()"><option value="">Semua Event</option>
            @foreach($events as $e)<option value="{{ $e->id }}" {{ request('event_id')==$e->id?'selected':'' }}>{{ $e->nama_event }}</option>@endforeach</select></div>
        @if(request('event_id'))
        <div class="col-md-4"><a href="{{ route('anggaran-event.index') }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        @endif
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Event</th><th>Item</th><th>Qty</th><th>Harga Satuan</th><th>Dianggarkan</th><th>Realisasi</th><th>Selisih</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($anggaran as $i => $item)
        <tr>
            <td>{{ $anggaran->firstItem() + $i }}</td>
            <td class="small">{{ $item->event?->nama_event ?? '-' }}</td>
            <td class="fw-semibold">{{ $item->item }}</td>
            <td>{{ $item->qty }}</td>
            <td>Rp {{ number_format($item->harga_satuan,0,',','.') }}</td>
            <td>Rp {{ number_format($item->jumlah_dianggarkan,0,',','.') }}</td>
            <td>Rp {{ number_format($item->jumlah_realisasi ?? 0,0,',','.') }}</td>
            <td class="{{ ($item->selisih ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($item->selisih ?? $item->jumlah_dianggarkan,0,',','.') }}</td>
            <td><div class="btn-group btn-group-sm">
                @can('anggaran-event.edit')<a href="{{ route('anggaran-event.edit', $item) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>@endcan
                @can('anggaran-event.delete')<form method="POST" action="{{ route('anggaran-event.destroy', $item) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>@endcan
            </div></td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-cash-stack fs-1 d-block mb-2"></i>Belum ada data anggaran event</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($anggaran->hasPages())<div class="card-footer">{{ $anggaran->links() }}</div>@endif
</div>
@endsection
