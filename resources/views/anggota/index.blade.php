@extends('layouts.app')

@section('title', 'Daftar Anggota — SIM UKM Jurnalistik')
@section('page-title', 'Manajemen Anggota')

@section('breadcrumb')
<li class="breadcrumb-item active">Anggota</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Anggota</h4>
        <p class="text-muted mb-0">Kelola data anggota UKM Jurnalistik</p>
    </div>
    <div class="d-flex gap-2">
        @hasrole('super_admin')
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetAllModal">
            <i class="bi bi-arrow-repeat me-1"></i> Reset Semua Password
        </button>
        @endhasrole
        @can('organisasi.create')
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
        <a href="{{ route('anggota.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
        </a>
        @endcan
    </div>
</div>

{{-- Import Success/Error Messages --}}
@if(session('import_errors') && count(session('import_errors')) > 0)
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <h6 class="alert-heading fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>Detail Import</h6>
    <div class="small" style="max-height: 200px; overflow-y: auto;">
        <ul class="mb-0 ps-3">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Import Modal --}}
@can('organisasi.create')
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-file-earmark-excel me-2 text-success"></i>Import Anggota dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('anggota.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Aturan --}}
                    <div class="alert alert-info small mb-3">
                        <div class="fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i>Aturan Import</div>
                        <ul class="mb-0 ps-3">
                            <li>Download template terlebih dahulu</li>
                            <li>Kolom <strong>wajib</strong>: NIM, Nama Lengkap, Email, Tanggal Lahir, Jenis Kelamin</li>
                            <li>Format tanggal lahir: <code>DD/MM/YYYY</code> (contoh: 15/05/2004)</li>
                            <li>Jenis kelamin: <code>L</code> (Laki-laki) atau <code>P</code> (Perempuan)</li>
                            <li>Password otomatis = tanggal lahir format <code>DDMMYYYY</code></li>
                            <li>NIM & email yang sudah ada akan dilewati</li>
                            <li>Format: <code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code> (maks 5MB)</li>
                        </ul>
                    </div>

                    {{-- Download Template --}}
                    <div class="mb-3">
                        <a href="{{ route('anggota.template') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-download me-1"></i>Download Template Excel
                        </a>
                    </div>

                    {{-- File Upload --}}
                    <div class="mb-3">
                        <label for="file_import" class="form-label fw-semibold">Pilih File</label>
                        <input type="file" class="form-control @error('file_import') is-invalid @enderror"
                               name="file_import" id="file_import"
                               accept=".xlsx,.xls,.csv" required>
                        @error('file_import')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: .xlsx, .xls, .csv — Maks 5MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i>Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('anggota.index') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" id="searchInput"
                           value="{{ request('search') }}" placeholder="Cari NIM, nama, email...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="divisi" onchange="this.form.submit()">
                    <option value="">Semua Divisi</option>
                    <option value="fotografi" {{ request('divisi') == 'fotografi' ? 'selected' : '' }}>Fotografi</option>
                    <option value="pers_penyiaran" {{ request('divisi') == 'pers_penyiaran' ? 'selected' : '' }}>Pers & Penyiaran</option>
                    <option value="videografi" {{ request('divisi') == 'videografi' ? 'selected' : '' }}>Videografi</option>
                    <option value="kominfo" {{ request('divisi') == 'kominfo' ? 'selected' : '' }}>Kominfo</option>
                    <option value="redaksi" {{ request('divisi') == 'redaksi' ? 'selected' : '' }}>Redaksi</option>
                    <option value="inventory" {{ request('divisi') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="pasif" {{ request('status') == 'pasif' ? 'selected' : '' }}>Pasif</option>
                    <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>
            </div>
            @if(request('search') || request('divisi') || request('status'))
            <div class="col-md-2">
                <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                    <i class="bi bi-x-lg me-1"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit search setelah user berhenti mengetik 500ms
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => this.form.submit(), 500);
    });
</script>
@endpush

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive table-mobile-cards">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $index => $item)
                    <tr>
                        <td data-label="#" class="text-muted">{{ $anggota->firstItem() + $index }}</td>
                        <td data-label="NIM"><code>{{ $item->nim }}</code></td>
                        <td data-label="Nama">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    {{ strtoupper(substr($item->nama_lengkap, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $item->nama_lengkap }}</div>
                                    <div class="small text-muted">{{ $item->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Divisi">{{ $item->divisi_label }}</td>
                        <td data-label="Jabatan"><span class="small">{{ $item->jabatan_lengkap }}</span></td>
                        <td data-label="Status">
                            @php
                                $statusBadge = match($item->status_keanggotaan) {
                                    'aktif' => 'success',
                                    'pasif' => 'warning',
                                    'alumni' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusBadge }}">{{ ucfirst($item->status_keanggotaan) }}</span>
                        </td>
                        <td data-label="Aksi">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('anggota.show', $item) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('organisasi.edit')
                                <a href="{{ route('anggota.edit', $item) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @hasrole('super_admin')
                                <button type="button" class="btn btn-outline-warning" title="Reset Password"
                                        data-bs-toggle="modal" data-bs-target="#resetSingleModal-{{ $item->id }}">
                                    <i class="bi bi-key"></i>
                                </button>
                                @endhasrole
                                @can('organisasi.delete')
                                <form method="POST" action="{{ route('anggota.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus anggota {{ $item->nama_lengkap }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Belum ada data anggota
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($anggota->hasPages())
    <div class="card-footer">
        {{ $anggota->links() }}
    </div>
    @endif
</div>

{{-- Reset All Passwords Modal (Super Admin Only) --}}
@hasrole('super_admin')
<div class="modal fade" id="resetAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-arrow-repeat me-2"></i>Reset Semua Password Anggota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('anggota.reset-all-passwords') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Perhatian:</strong> Semua password anggota (kecuali admin) akan direset.
                        Anggota akan diminta mengganti password saat login berikutnya.
                    </div>
                    <div class="mb-3">
                        <label for="bulk_password" class="form-label fw-semibold small">Password Baru</label>
                        <input type="text" class="form-control" id="bulk_password" name="password"
                               value="12345678" placeholder="Kosongkan untuk default: 12345678">
                        <div class="form-text">Kosongkan untuk menggunakan password default: <code>12345678</code></div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmBulk" required>
                        <label class="form-check-label small" for="confirmBulk">
                            Saya yakin ingin mereset <strong>semua password anggota</strong>.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset Semua Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Per-User Reset Password Modals --}}
@foreach($anggota as $item)
<div class="modal fade" id="resetSingleModal-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-key me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('anggota.reset-password', $item) }}">
                @csrf
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Reset password untuk: <strong>{{ $item->nama_lengkap }}</strong><br>
                        <code class="small">{{ $item->nim }}</code>
                    </p>
                    <div class="mb-3">
                        <label for="single_password_{{ $item->id }}" class="form-label fw-semibold small">Password Baru</label>
                        <input type="text" class="form-control" id="single_password_{{ $item->id }}" name="password"
                               value="12345678" placeholder="Kosongkan untuk default: 12345678">
                        <div class="form-text">Kosongkan untuk default: <code>12345678</code></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold btn-sm">
                        <i class="bi bi-key me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endhasrole
@endsection
