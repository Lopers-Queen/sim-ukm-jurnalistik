@php $isEdit = isset($notulensi); @endphp
<div class="row g-3">
    <div class="col-md-8">
        <label for="judul" class="form-label fw-semibold">Judul Rapat <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
               value="{{ old('judul', $isEdit ? $notulensi->judul : '') }}" required>
        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="jenis_rapat" class="form-label fw-semibold">Jenis Rapat <span class="text-danger">*</span></label>
        <select class="form-select @error('jenis_rapat') is-invalid @enderror" id="jenis_rapat" name="jenis_rapat" required>
            @foreach(['rapat_rutin'=>'Rapat Rutin','rapat_khusus'=>'Rapat Khusus','rapat_evaluasi'=>'Rapat Evaluasi','rapat_kerja'=>'Rapat Kerja','rapat_pleno'=>'Rapat Pleno'] as $v=>$l)
            <option value="{{ $v }}" {{ old('jenis_rapat', $isEdit ? $notulensi->jenis_rapat : 'rapat_rutin') == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('jenis_rapat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="tanggal_rapat" class="form-label fw-semibold">Tanggal Rapat <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_rapat') is-invalid @enderror" id="tanggal_rapat" name="tanggal_rapat"
               value="{{ old('tanggal_rapat', $isEdit ? $notulensi->tanggal_rapat->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('tanggal_rapat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi"
               value="{{ old('lokasi', $isEdit ? $notulensi->lokasi : '') }}">
        @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="isi_notulensi" class="form-label fw-semibold">Isi Notulensi <span class="text-danger">*</span></label>
        <textarea class="form-control @error('isi_notulensi') is-invalid @enderror" id="isi_notulensi" name="isi_notulensi" rows="8" required>{{ old('isi_notulensi', $isEdit ? $notulensi->isi_notulensi : '') }}</textarea>
        @error('isi_notulensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
