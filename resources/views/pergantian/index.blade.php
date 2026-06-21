@extends('layouts.app')

@section('title', 'Pergantian Kepengurusan — SIM UKM Jurnalistik')
@section('page-title', 'Pergantian Kepengurusan')

@section('breadcrumb')
<li class="breadcrumb-item active">Pergantian</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Pergantian Kepengurusan</h4>
        <p class="text-muted mb-0">Proses transisi periode kepengurusan tahunan (FR-17)</p>
    </div>
</div>

@if($periodeAktif)
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    Periode aktif saat ini: <strong>{{ $periodeAktif->nama_periode }}</strong>
    ({{ $periodeAktif->tanggal_mulai?->format('d/m/Y') }} — {{ $periodeAktif->tanggal_selesai?->format('d/m/Y') }})
</div>
@endif

<form method="POST" action="{{ route('pergantian.store') }}" x-data="pergantianForm()">
    @csrf

    {{-- Step 1: Periode Baru --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-1-circle me-2"></i>Data Periode Baru</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Periode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_periode" value="{{ old('nama_periode') }}" placeholder="Contoh: 2026/2027" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Deskripsi / Catatan</label>
                    <input type="text" class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" placeholder="Opsional">
                </div>
            </div>
        </div>
    </div>

    {{-- Step 2: Susunan BPI --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-2-circle me-2"></i>Susunan Badan Pengurus Inti (BPI)</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([
                    'ketua_umum' => 'Ketua Umum (Hasil MUBES)',
                    'wakil_ketua_umum' => 'Wakil Ketua Umum (Hasil MUBES)',
                    'sekretaris_umum_1' => 'Sekretaris Umum 1',
                    'sekretaris_umum_2' => 'Sekretaris Umum 2',
                    'bendahara_umum_1' => 'Bendahara Umum 1',
                    'bendahara_umum_2' => 'Bendahara Umum 2',
                ] as $key => $label)
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ $label }} <span class="text-danger">*</span></label>
                    <select class="form-select select-search" name="{{ $key }}" x-on:change="checkEligibility('{{ $key }}', $event.target.value)" required>
                        <option value="">— Pilih Anggota —</option>
                        @foreach($anggotaList as $anggota)
                            <option value="{{ $anggota->id }}" {{ old($key) == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama_lengkap }} ({{ $anggota->nim }}) — {{ $anggota->masaKeanggotaan() }} thn
                            </option>
                        @endforeach
                    </select>
                    {{-- Eligibility indicator --}}
                    <div x-show="validationResults['{{ $key }}']" class="mt-1">
                        <template x-if="validationResults['{{ $key }}']?.eligible">
                            <small class="text-success"><i class="bi bi-check-circle"></i> <span x-text="validationResults['{{ $key }}']?.reason"></span></small>
                        </template>
                        <template x-if="validationResults['{{ $key }}'] && !validationResults['{{ $key }}']?.eligible">
                            <div>
                                <small class="text-danger"><i class="bi bi-x-circle"></i> <span x-text="validationResults['{{ $key }}']?.reason"></span></small>
                                <div class="mt-1">
                                    <label class="form-label small text-warning fw-semibold">Alasan Override (min. 50 karakter):</label>
                                    <textarea class="form-control form-control-sm" name="override_reasons[{{ $key }}]" rows="2" minlength="50" placeholder="Wajib diisi jika ingin melanjutkan pengangkatan..."></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Step 3: Kepala Divisi --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-3-circle me-2"></i>Kepala Divisi</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([
                    'kadiv_fotografi' => 'Kepala Divisi Fotografi',
                    'kadiv_pers_penyiaran' => 'Kepala Divisi Pers & Penyiaran',
                    'kadiv_videografi' => 'Kepala Divisi Videografi',
                ] as $key => $label)
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ $label }} <span class="text-danger">*</span></label>
                    <select class="form-select select-search" name="{{ $key }}" x-on:change="checkEligibility('{{ $key }}', $event.target.value)" required>
                        <option value="">— Pilih Anggota —</option>
                        @foreach($anggotaList as $anggota)
                            <option value="{{ $anggota->id }}" {{ old($key) == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama_lengkap }} ({{ $anggota->nim }})
                            </option>
                        @endforeach
                    </select>
                    <div x-show="validationResults['{{ $key }}']" class="mt-1">
                        <template x-if="validationResults['{{ $key }}']?.eligible">
                            <small class="text-success"><i class="bi bi-check-circle"></i> Layak</small>
                        </template>
                        <template x-if="validationResults['{{ $key }}'] && !validationResults['{{ $key }}']?.eligible">
                            <div>
                                <small class="text-danger"><i class="bi bi-x-circle"></i> <span x-text="validationResults['{{ $key }}']?.reason"></span></small>
                                <textarea class="form-control form-control-sm mt-1" name="override_reasons[{{ $key }}]" rows="2" minlength="50" placeholder="Alasan override (min. 50 karakter)..."></textarea>
                            </div>
                        </template>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Step 4: Kepala Unit --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-4-circle me-2"></i>Kepala Unit</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([
                    'kanit_kominfo' => 'Kepala Unit Kominfo',
                    'kanit_redaksi' => 'Kepala Unit Redaksi',
                    'kanit_inventory' => 'Kepala Unit Inventory',
                ] as $key => $label)
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ $label }} <span class="text-danger">*</span></label>
                    <select class="form-select select-search" name="{{ $key }}" x-on:change="checkEligibility('{{ $key }}', $event.target.value)" required>
                        <option value="">— Pilih Anggota —</option>
                        @foreach($anggotaList as $anggota)
                            <option value="{{ $anggota->id }}" {{ old($key) == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama_lengkap }} ({{ $anggota->nim }})
                            </option>
                        @endforeach
                    </select>
                    <div x-show="validationResults['{{ $key }}']" class="mt-1">
                        <template x-if="validationResults['{{ $key }}']?.eligible">
                            <small class="text-success"><i class="bi bi-check-circle"></i> Layak</small>
                        </template>
                        <template x-if="validationResults['{{ $key }}'] && !validationResults['{{ $key }}']?.eligible">
                            <div>
                                <small class="text-danger"><i class="bi bi-x-circle"></i> <span x-text="validationResults['{{ $key }}']?.reason"></span></small>
                                <textarea class="form-control form-control-sm mt-1" name="override_reasons[{{ $key }}]" rows="2" minlength="50" placeholder="Alasan override (min. 50 karakter)..."></textarea>
                            </div>
                        </template>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('periode.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Apakah Anda yakin ingin memfinalisasi pergantian kepengurusan? Tindakan ini tidak dapat dibatalkan.')">
            <i class="bi bi-check-circle me-1"></i> Finalisasi Pergantian Kepengurusan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function pergantianForm() {
    return {
        validationResults: {},
        async checkEligibility(jabatan, anggotaId) {
            if (!anggotaId) {
                delete this.validationResults[jabatan];
                return;
            }
            try {
                const response = await fetch('{{ route("pergantian.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ susunan: { [jabatan]: anggotaId } }),
                });
                const data = await response.json();
                if (data.results && data.results[jabatan]) {
                    this.validationResults[jabatan] = data.results[jabatan];
                }
            } catch (e) {
                console.error('Validation error:', e);
            }
        },
    };
}
</script>
@endpush
