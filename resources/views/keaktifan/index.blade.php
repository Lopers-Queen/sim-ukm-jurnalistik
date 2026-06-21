@extends('layouts.app')
@section('title', 'Manajemen Keaktifan — SIM UKM Jurnalistik')
@section('page-title', 'Manajemen Keaktifan')

@section('breadcrumb')
<li class="breadcrumb-item active">Keaktifan</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Keaktifan Anggota</h4>
        <p class="text-muted mb-0">Perpanjangan keaktifan anggota per periode (FR-13)</p>
    </div>
    @if($periodeAktif)
    <span class="badge bg-primary fs-6">Periode: {{ $periodeAktif->nama_periode }}</span>
    @endif
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari NIM, nama...">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="pasif" {{ request('status') === 'pasif' ? 'selected' : '' }}>Pasif</option>
                    <option value="alumni" {{ request('status') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>
            </div>
            @if(request('search') || request('status'))
            <div class="col-md-2">
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a>
            </div>
            @endif
        </form>
    </div>
</div>
@push('scripts')
<script>
let t;document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(t);t=setTimeout(()=>this.form.submit(),500)});
</script>
@endpush

{{-- Batch actions --}}
@can('organisasi.edit')
<form method="POST" action="{{ route('keaktifan.batch-update') }}" x-data="{ selectedIds: [], selectAll: false }" id="batchForm">
    @csrf
    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">Daftar Anggota</h6>
            <div class="d-flex gap-2" x-show="selectedIds.length > 0">
                <span class="badge bg-primary" x-text="selectedIds.length + ' dipilih'"></span>
                <select class="form-select form-select-sm" name="status" style="width: 150px;" required>
                    <option value="aktif">Set Aktif</option>
                    <option value="pasif">Set Pasif</option>
                    <option value="alumni">Set Alumni</option>
                </select>
                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Update status anggota yang dipilih?')">
                    <i class="bi bi-check-lg"></i> Update Batch
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" x-model="selectAll" x-on:change="selectedIds = selectAll ? [{{ $anggota->pluck('id')->implode(',') }}] : []"></th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $a)
                        <tr>
                            <td>
                                <input type="checkbox" name="anggota_ids[]" value="{{ $a->id }}" x-model="selectedIds" :value="{{ $a->id }}">
                            </td>
                            <td class="fw-semibold text-primary">{{ $a->nim }}</td>
                            <td>{{ $a->nama_lengkap }}</td>
                            <td>{{ $a->divisi_label }}</td>
                            <td>{{ $a->jabatan_lengkap }}</td>
                            <td>
                                <span class="badge bg-{{ $a->status_keanggotaan === 'aktif' ? 'success' : ($a->status_keanggotaan === 'pasif' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($a->status_keanggotaan) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Status</button>
                                    <ul class="dropdown-menu">
                                        @foreach(['aktif', 'pasif', 'alumni'] as $s)
                                        <li>
                                            <form method="POST" action="{{ route('keaktifan.toggle', $a) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ $s }}">
                                                <button class="dropdown-item {{ $a->status_keanggotaan === $s ? 'active' : '' }}">
                                                    {{ ucfirst($s) }}
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada anggota ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $anggota->links() }}
        </div>
    </div>
</form>
@else
{{-- Read-only view for users without edit permission --}}
<div class="card">
    <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 fw-semibold">Daftar Anggota</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $a)
                    <tr>
                        <td class="fw-semibold text-primary">{{ $a->nim }}</td>
                        <td>{{ $a->nama_lengkap }}</td>
                        <td>{{ $a->divisi_label }}</td>
                        <td>{{ $a->jabatan_lengkap }}</td>
                        <td>
                            <span class="badge bg-{{ $a->status_keanggotaan === 'aktif' ? 'success' : ($a->status_keanggotaan === 'pasif' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($a->status_keanggotaan) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada anggota ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $anggota->links() }}
    </div>
</div>
@endcan
@endsection
