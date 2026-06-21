@extends('layouts.app')
@section('title', 'Naskah Redaksi — SIM UKM Jurnalistik')
@section('page-title', 'Naskah Redaksi')
@section('breadcrumb')
<li class="breadcrumb-item active">Naskah Redaksi</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Daftar Naskah</h4><p class="text-muted mb-0">Kelola naskah redaksi UKM</p></div>
    @can('naskah-redaksi.create')<a href="{{ route('naskah.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tulis Naskah</a>@endcan
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" action="{{ route('naskah.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari judul naskah..."></div></div>
        <div class="col-md-3"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            @foreach(['draft','review','revisi','disetujui','ditolak','published'] as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        @if(request('search') || request('status'))
        <div class="col-md-3"><a href="{{ route('naskah.index') }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
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
        <thead class="table-light"><tr><th>#</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($naskah as $i => $item)
        <tr>
            <td>{{ $naskah->firstItem() + $i }}</td>
            <td class="fw-semibold">{{ $item->judul }}</td>
            <td>{{ $item->penulis?->nama_lengkap ?? '-' }}</td>
            <td>{{ $item->kategori ?? '-' }}</td>
            <td><span class="badge bg-{{ $item->status_badge }}">{{ $item->status_label }}</span></td>
            <td><div class="btn-group btn-group-sm">
                <a href="{{ route('naskah.show', $item) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                @can('naskah-redaksi.edit')<a href="{{ route('naskah.edit', $item) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>@endcan
                @can('naskah-redaksi.delete')<form method="POST" action="{{ route('naskah.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus naskah &quot;{{ $item->judul }}&quot;?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button></form>@endcan
            </div></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>Belum ada naskah</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($naskah->hasPages())<div class="card-footer">{{ $naskah->links() }}</div>@endif
</div>
@endsection
