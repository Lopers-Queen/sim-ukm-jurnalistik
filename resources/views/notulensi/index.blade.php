@extends('layouts.app')
@section('title', 'Notulensi — SIM UKM Jurnalistik')
@section('page-title', 'Notulensi Rapat')
@section('breadcrumb')
<li class="breadcrumb-item active">Notulensi</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Notulensi</h4><p class="text-muted mb-0">Catatan hasil rapat UKM</p></div>
    @can('notulensi.create')<a href="{{ route('notulensi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Notulensi</a>@endcan
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Judul</th><th>Tanggal</th><th>Jenis</th><th>Pencatat</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($notulensi as $i => $item)
        <tr>
            <td>{{ $notulensi->firstItem() + $i }}</td>
            <td class="fw-semibold">{{ $item->judul }}</td>
            <td>{{ $item->tanggal_rapat->format('d/m/Y') }}</td>
            <td><span class="badge bg-info">{{ $item->jenis_rapat_label }}</span></td>
            <td>{{ $item->pencatat?->nama_lengkap ?? '-' }}</td>
            <td><div class="btn-group btn-group-sm">
                <a href="{{ route('notulensi.show', $item) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                @can('notulensi.edit')<a href="{{ route('notulensi.edit', $item) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>@endcan
                @can('notulensi.delete')<form method="POST" action="{{ route('notulensi.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus notulensi ini?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button></form>@endcan
            </div></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-journal-text fs-1 d-block mb-2"></i>Belum ada notulensi</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($notulensi->hasPages())<div class="card-footer">{{ $notulensi->links() }}</div>@endif
</div>
@endsection
