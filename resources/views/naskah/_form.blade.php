@php $isEdit = isset($naskah); @endphp
<div class="row g-3">
    <div class="col-md-8">
        <label for="judul" class="form-label fw-semibold">Judul Naskah <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
               value="{{ old('judul', $isEdit ? $naskah->judul : '') }}" required>
        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="kategori" class="form-label fw-semibold">Kategori</label>
        <input type="text" class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori"
               value="{{ old('kategori', $isEdit ? $naskah->kategori : '') }}" placeholder="Contoh: Berita, Opini">
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="konten" class="form-label fw-semibold">Konten Naskah <span class="text-danger">*</span></label>
        <textarea class="form-control @error('konten') is-invalid @enderror" id="konten" name="konten" rows="12" required>{{ old('konten', $isEdit ? $naskah->konten : '') }}</textarea>
        @error('konten')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
