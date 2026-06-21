@extends('layouts.app')
@section('title', 'Detail Periode — SIM UKM Jurnalistik')
@section('page-title', $periode->nama_periode)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('periode.index') }}">Periode</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="fw-bold">{{ $periode->nama_periode }}</h4>
                <p class="text-muted">{{ $periode->tahun_mulai }} — {{ $periode->tahun_selesai }}</p>
                @php $badge = match($periode->status) { 'aktif' => 'success', 'upcoming' => 'info', default => 'secondary' }; @endphp
                <span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($periode->status) }}</span>
                <hr>
                <div class="d-flex justify-content-around">
                    <div><div class="fs-3 fw-bold text-primary">{{ $periode->riwayatKepengurusan->count() }}</div><div class="small text-muted">Pengurus</div></div>
                    <div><div class="fs-3 fw-bold text-info">{{ $periode->events->count() }}</div><div class="small text-muted">Event</div></div>
                </div>
                <div class="d-flex gap-2 mt-3 justify-content-center">
                    @can('periode.edit')<a href="{{ route('periode.edit', $periode) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                    <a href="{{ route('periode.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Pengurus Periode Ini</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0"><thead class="table-light"><tr><th>Nama</th><th>Jabatan</th><th>Divisi</th></tr></thead>
                <tbody>
                @forelse($periode->riwayatKepengurusan as $r)
                <tr><td>{{ $r->anggota?->nama_lengkap }}</td><td>{{ $r->jabatan_label }}</td><td>{{ $r->divisi ?? '-' }}</td></tr>
                @empty<tr><td colspan="3" class="text-center text-muted py-3">Belum ada data pengurus</td></tr>@endforelse
                </tbody></table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Event di Periode Ini</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0"><thead class="table-light"><tr><th>Nama Event</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($periode->events as $e)
                <tr><td><a href="{{ route('event.show', $e) }}">{{ $e->nama_event }}</a></td><td>{{ $e->tanggal_mulai->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ $e->status_badge }}">{{ $e->status_label }}</span></td></tr>
                @empty<tr><td colspan="3" class="text-center text-muted py-3">Belum ada event</td></tr>@endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection
