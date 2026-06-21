@php $isEdit = isset($anggaranEvent); @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label for="event_id" class="form-label fw-semibold">Event <span class="text-danger">*</span></label>
        <select class="form-select @error('event_id') is-invalid @enderror" id="event_id" name="event_id" required>
            <option value="">-- Pilih Event --</option>
            @foreach($events as $e)<option value="{{ $e->id }}" {{ old('event_id', $isEdit ? $anggaranEvent->event_id : '') == $e->id ? 'selected' : '' }}>{{ $e->nama_event }}</option>@endforeach
        </select>@error('event_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="kategori" class="form-label fw-semibold">Kategori</label>
        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ old('kategori', $isEdit ? $anggaranEvent->kategori : '') }}" placeholder="Contoh: Konsumsi, Perlengkapan">
    </div>
    <div class="col-12">
        <label for="item" class="form-label fw-semibold">Item <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('item') is-invalid @enderror" id="item" name="item"
               value="{{ old('item', $isEdit ? $anggaranEvent->item : '') }}" required>
        @error('item')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="qty" class="form-label fw-semibold">Qty <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty" name="qty"
               value="{{ old('qty', $isEdit ? $anggaranEvent->qty : 1) }}" min="1" required>
        @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="harga_satuan" class="form-label fw-semibold">Harga Satuan (Rp) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan" name="harga_satuan"
               value="{{ old('harga_satuan', $isEdit ? $anggaranEvent->harga_satuan : '') }}" min="0" step="100" required>
        @error('harga_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="jumlah_dianggarkan" class="form-label fw-semibold">Jumlah Dianggarkan (Rp) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('jumlah_dianggarkan') is-invalid @enderror" id="jumlah_dianggarkan" name="jumlah_dianggarkan"
               value="{{ old('jumlah_dianggarkan', $isEdit ? $anggaranEvent->jumlah_dianggarkan : '') }}" min="0" step="100" required>
        @error('jumlah_dianggarkan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="jumlah_realisasi" class="form-label fw-semibold">Jumlah Realisasi (Rp)</label>
        <input type="number" class="form-control" id="jumlah_realisasi" name="jumlah_realisasi"
               value="{{ old('jumlah_realisasi', $isEdit ? $anggaranEvent->jumlah_realisasi : 0) }}" min="0" step="100">
    </div>
    <div class="col-12">
        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
        <textarea class="form-control" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $isEdit ? $anggaranEvent->keterangan : '') }}</textarea>
    </div>
</div>
