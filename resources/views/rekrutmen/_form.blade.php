@php $isEdit = isset($rekrutmen); @endphp
<div class="row g-3">
    <div class="col-md-8">
        <label for="nama_rekrutmen" class="form-label fw-semibold">Nama Rekrutmen <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_rekrutmen') is-invalid @enderror" id="nama_rekrutmen" name="nama_rekrutmen"
               value="{{ old('nama_rekrutmen', $isEdit ? $rekrutmen->nama_rekrutmen : '') }}" required>
        @error('nama_rekrutmen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach(['draft'=>'Draft','dibuka'=>'Dibuka','ditutup'=>'Ditutup','selesai'=>'Selesai'] as $v=>$l)
            <option value="{{ $v }}" {{ old('status', $isEdit ? $rekrutmen->status : 'draft') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
        </select>@error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="periode_id" class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
        <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id" name="periode_id" required>
            <option value="">-- Pilih Periode --</option>
            @foreach($periodes as $p)<option value="{{ $p->id }}" {{ old('periode_id', $isEdit ? $rekrutmen->periode_id : '') == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>@endforeach
        </select>@error('periode_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tanggal_buka" class="form-label fw-semibold">Tanggal Buka <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_buka') is-invalid @enderror" id="tanggal_buka" name="tanggal_buka"
               value="{{ old('tanggal_buka', $isEdit ? $rekrutmen->tanggal_buka->format('Y-m-d') : '') }}" required>
        @error('tanggal_buka')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tanggal_tutup" class="form-label fw-semibold">Tanggal Tutup <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_tutup') is-invalid @enderror" id="tanggal_tutup" name="tanggal_tutup"
               value="{{ old('tanggal_tutup', $isEdit ? $rekrutmen->tanggal_tutup->format('Y-m-d') : '') }}" required>
        @error('tanggal_tutup')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="kuota" class="form-label fw-semibold">Kuota</label>
        <input type="number" class="form-control @error('kuota') is-invalid @enderror" id="kuota" name="kuota"
               value="{{ old('kuota', $isEdit ? $rekrutmen->kuota : '') }}" min="1" placeholder="Tidak dibatasi">
        @error('kuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $isEdit ? $rekrutmen->deskripsi : '') }}</textarea>
    </div>
    <div class="col-12">
        <label for="persyaratan" class="form-label fw-semibold">Persyaratan</label>
        <textarea class="form-control" id="persyaratan" name="persyaratan" rows="3" placeholder="Syarat pendaftaran...">{{ old('persyaratan', $isEdit ? $rekrutmen->persyaratan : '') }}</textarea>
    </div>
</div>
