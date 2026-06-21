@extends('layouts.app')
@section('title', 'Laporan Anggota — SIM UKM Jurnalistik')
@section('page-title', 'Laporan Data Anggota')
@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Laporan Anggota</h4><p class="text-muted mb-0">Preview data anggota sebelum export</p></div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.anggota.pdf', request()->query()) }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Download PDF</a>
        <a href="{{ route('laporan.anggota.excel', request()->query()) }}" class="btn btn-success btn-sm"><i class="bi bi-filetype-xlsx me-1"></i>Download Excel</a>
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-4"><select class="form-select" name="divisi" onchange="this.form.submit()"><option value="">Semua Divisi</option>
            @foreach(['fotografi','pers_penyiaran','videografi','kominfo','redaksi','inventory'] as $d)<option value="{{ $d }}" {{ request('divisi')==$d?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$d)) }}</option>@endforeach</select></div>
        <div class="col-md-4"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            @foreach(['aktif','pasif','alumni'] as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        @if(request('divisi') || request('status'))
        <div class="col-md-4"><a href="{{ route('laporan.anggota') }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        @endif
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-striped table-sm align-middle mb-0">
        <thead class="table-dark"><tr><th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Email</th><th>Divisi</th><th>Jabatan</th><th>Status</th><th>Tgl Bergabung</th></tr></thead>
        <tbody>
        @forelse($anggotaList as $i => $a)
        <tr>
            <td>{{ $i + 1 }}</td><td>{{ $a->nim }}</td><td>{{ $a->nama_lengkap }}</td><td>{{ $a->email }}</td>
            <td>{{ $a->divisi_label }}</td><td>{{ $a->jabatan_lengkap }}</td>
            <td><span class="badge bg-{{ $a->status_keanggotaan == 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($a->status_keanggotaan) }}</span></td>
            <td>{{ $a->tanggal_bergabung?->format('d/m/Y') ?? '-' }}</td>
        </tr>
        @empty<tr><td colspan="8" class="text-center py-3 text-muted">Tidak ada data</td></tr>@endforelse
        </tbody>
    </table>
</div></div></div>
<div class="text-muted small mt-2"><i class="bi bi-info-circle me-1"></i>Total: {{ $anggotaList->count() }} anggota</div>
@endsection
