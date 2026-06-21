@extends('layouts.app')
@section('title', 'Anggaran Divisi — SIM UKM Jurnalistik')
@section('page-title', 'Anggaran Divisi')
@section('breadcrumb')
<li class="breadcrumb-item active">Anggaran Divisi</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Anggaran Divisi</h4><p class="text-muted mb-0">Kelola anggaran per divisi/bulan</p></div>
    @can('anggaran-divisi.create')<a href="{{ route('anggaran-divisi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Anggaran</a>@endcan
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-4"><select class="form-select" name="divisi" onchange="this.form.submit()"><option value="">Semua Divisi</option>
            @foreach(['fotografi','pers_penyiaran','videografi','kominfo','redaksi','inventory','bpi'] as $d)<option value="{{ $d }}" {{ request('divisi')==$d?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$d)) }}</option>@endforeach</select></div>
        <div class="col-md-4"><select class="form-select" name="periode_id" onchange="this.form.submit()"><option value="">Semua Periode</option>
            @foreach($periodes as $p)<option value="{{ $p->id }}" {{ request('periode_id')==$p->id?'selected':'' }}>{{ $p->nama_periode }}</option>@endforeach</select></div>
        @if(request('divisi') || request('periode_id'))
        <div class="col-md-2"><a href="{{ route('anggaran-divisi.index') }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        @endif
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Divisi</th><th>Bulan</th><th>Anggaran</th><th>Terpakai</th><th>Sisa</th><th>%</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($anggaran as $i => $item)
        <tr>
            <td>{{ $anggaran->firstItem() + $i }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$item->divisi)) }}</td>
            <td class="fw-semibold">{{ $item->nama_bulan }} {{ $item->tahun }}</td>
            <td>Rp {{ number_format($item->jumlah_anggaran,0,',','.') }}</td>
            <td>Rp {{ number_format($item->jumlah_terpakai,0,',','.') }}</td>
            <td class="{{ $item->sisa_anggaran < 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($item->sisa_anggaran,0,',','.') }}</td>
            <td><div class="progress" style="width:60px;height:6px"><div class="progress-bar {{ $item->persentase_terpakai > 90 ? 'bg-danger' : ($item->persentase_terpakai > 70 ? 'bg-warning' : 'bg-success') }}" style="width:{{ min($item->persentase_terpakai, 100) }}%"></div></div>
                <small class="text-muted">{{ $item->persentase_terpakai }}%</small></td>
            <td><div class="btn-group btn-group-sm">
                @can('anggaran-divisi.edit')<a href="{{ route('anggaran-divisi.edit', $item) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>@endcan
                @can('anggaran-divisi.delete')<form method="POST" action="{{ route('anggaran-divisi.destroy', $item) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>@endcan
            </div></td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-wallet2 fs-1 d-block mb-2"></i>Belum ada data anggaran</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($anggaran->hasPages())<div class="card-footer">{{ $anggaran->links() }}</div>@endif
</div>
@endsection
