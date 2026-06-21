@php $isEdit = isset($anggaranDivisi); @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label for="periode_id" class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
        <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id" name="periode_id" required>
            <option value="">-- Pilih --</option>
            @foreach($periodes as $p)<option value="{{ $p->id }}" {{ old('periode_id', $isEdit ? $anggaranDivisi->periode_id : '') == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>@endforeach
        </select>@error('periode_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="divisi" class="form-label fw-semibold">Divisi <span class="text-danger">*</span></label>
        <select class="form-select @error('divisi') is-invalid @enderror" id="divisi" name="divisi" required>
            @foreach(['fotografi'=>'Fotografi','pers_penyiaran'=>'Pers & Penyiaran','videografi'=>'Videografi','kominfo'=>'Kominfo','redaksi'=>'Redaksi','inventory'=>'Inventory','bpi'=>'BPI'] as $v=>$l)
            <option value="{{ $v }}" {{ old('divisi', $isEdit ? $anggaranDivisi->divisi : '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
        </select>@error('divisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="bulan" class="form-label fw-semibold">Bulan <span class="text-danger">*</span></label>
        <select class="form-select @error('bulan') is-invalid @enderror" id="bulan" name="bulan" required>
            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $v=>$l)
            <option value="{{ $v }}" {{ old('bulan', $isEdit ? $anggaranDivisi->bulan : now()->month) == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
        </select>@error('bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun"
               value="{{ old('tahun', $isEdit ? $anggaranDivisi->tahun : now()->year) }}" min="2020" max="2099" required>
        @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="jumlah_anggaran" class="form-label fw-semibold">Jumlah Anggaran (Rp) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('jumlah_anggaran') is-invalid @enderror" id="jumlah_anggaran" name="jumlah_anggaran"
               value="{{ old('jumlah_anggaran', $isEdit ? $anggaranDivisi->jumlah_anggaran : '') }}" min="0" step="1000" required>
        @error('jumlah_anggaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="jumlah_terpakai" class="form-label fw-semibold">Jumlah Terpakai (Rp)</label>
        <input type="number" class="form-control @error('jumlah_terpakai') is-invalid @enderror" id="jumlah_terpakai" name="jumlah_terpakai"
               value="{{ old('jumlah_terpakai', $isEdit ? $anggaranDivisi->jumlah_terpakai : 0) }}" min="0" step="1000">
        @error('jumlah_terpakai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
        <textarea class="form-control" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $isEdit ? $anggaranDivisi->keterangan : '') }}</textarea>
    </div>
</div>
