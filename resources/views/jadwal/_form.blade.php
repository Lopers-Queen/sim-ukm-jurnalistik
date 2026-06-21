@php $isEdit = isset($jadwal); @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label for="anggota_id" class="form-label fw-semibold">Anggota <span class="text-danger">*</span></label>
        <select class="form-select @error('anggota_id') is-invalid @enderror" id="anggota_id" name="anggota_id" required>
            <option value="">-- Pilih Anggota --</option>
            @foreach(\App\Models\Anggota::where('status_keanggotaan','aktif')->where('jabatan_struktural','!=','admin')->orderBy('nama_lengkap')->get() as $a)
            <option value="{{ $a->id }}" {{ old('anggota_id', $isEdit ? $jadwal->anggota_id : '') == $a->id ? 'selected' : '' }}>{{ $a->nama_lengkap }}</option>
            @endforeach
        </select>
        @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Pilih dari seluruh anggota aktif UKM Jurnalistik</div>
    </div>
    <div class="col-md-6">
        <label for="hari" class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
        <select class="form-select @error('hari') is-invalid @enderror" id="hari" name="hari" required>
            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
            <option value="{{ $h }}" {{ old('hari', $isEdit ? $jadwal->hari : '') == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
            @endforeach
        </select>
        @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
