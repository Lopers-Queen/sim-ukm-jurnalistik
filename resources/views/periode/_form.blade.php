@php $isEdit = isset($periode); @endphp
<div class="row g-3">
    <div class="col-12">
        <label for="nama_periode" class="form-label fw-semibold">Nama Periode <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_periode') is-invalid @enderror" id="nama_periode" name="nama_periode"
               value="{{ old('nama_periode', $isEdit ? $periode->nama_periode : '') }}" placeholder="Contoh: 2025/2026" required>
        @error('nama_periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tahun_mulai" class="form-label fw-semibold">Tahun Mulai <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('tahun_mulai') is-invalid @enderror" id="tahun_mulai" name="tahun_mulai"
               value="{{ old('tahun_mulai', $isEdit ? $periode->tahun_mulai : now()->year) }}" min="2000" max="2099" required>
        @error('tahun_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tahun_selesai" class="form-label fw-semibold">Tahun Selesai <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('tahun_selesai') is-invalid @enderror" id="tahun_selesai" name="tahun_selesai"
               value="{{ old('tahun_selesai', $isEdit ? $periode->tahun_selesai : now()->year + 1) }}" min="2000" max="2099" required>
        @error('tahun_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach(['upcoming' => 'Upcoming', 'aktif' => 'Aktif', 'selesai' => 'Selesai'] as $v => $l)
            <option value="{{ $v }}" {{ old('status', $isEdit ? $periode->status : 'upcoming') == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
