@php $isEdit = isset($event); @endphp
<div class="row g-3">
    <div class="col-md-8">
        <label for="nama_event" class="form-label fw-semibold">Nama Event <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_event') is-invalid @enderror" id="nama_event" name="nama_event"
               value="{{ old('nama_event', $isEdit ? $event->nama_event : '') }}" required>
        @error('nama_event')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach(['draft','direncanakan','aktif','selesai','batal'] as $s)
            <option value="{{ $s }}" {{ old('status', $isEdit ? $event->status : 'draft') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $isEdit ? $event->deskripsi : '') }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai"
               value="{{ old('tanggal_mulai', $isEdit ? $event->tanggal_mulai->format('Y-m-d') : '') }}" required>
        @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai"
               value="{{ old('tanggal_selesai', $isEdit && $event->tanggal_selesai ? $event->tanggal_selesai->format('Y-m-d') : '') }}">
        @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi"
               value="{{ old('lokasi', $isEdit ? $event->lokasi : '') }}">
        @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="pic_id" class="form-label fw-semibold">PIC (Penanggung Jawab)</label>
        <select class="form-select select-search @error('pic_id') is-invalid @enderror" id="pic_id" name="pic_id">
            <option value="">-- Pilih PIC --</option>
            @foreach($anggotaList as $a)<option value="{{ $a->id }}" {{ old('pic_id', $isEdit ? $event->pic_id : '') == $a->id ? 'selected' : '' }}>{{ $a->nama_lengkap }}</option>@endforeach
        </select>
        @error('pic_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="periode_id" class="form-label fw-semibold">Periode</label>
        <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id" name="periode_id">
            <option value="">-- Pilih Periode --</option>
            @foreach($periodes as $p)<option value="{{ $p->id }}" {{ old('periode_id', $isEdit ? $event->periode_id : '') == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>@endforeach
        </select>
        @error('periode_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="anggaran_total" class="form-label fw-semibold">Anggaran Total (Rp)</label>
        <input type="number" class="form-control @error('anggaran_total') is-invalid @enderror" id="anggaran_total" name="anggaran_total"
               value="{{ old('anggaran_total', $isEdit ? $event->anggaran_total : '') }}" min="0" step="1000">
        @error('anggaran_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Template Kepanitiaan Selector --}}
@if(isset($templates) && $templates->count() > 0 && !$isEdit)
<div class="row mt-3">
    <div class="col-12">
        <div class="card border-dashed bg-light">
            <div class="card-body py-3">
                <label for="template_id" class="form-label fw-semibold">
                    <i class="bi bi-layout-text-sidebar-reverse me-1 text-primary"></i>
                    Gunakan Template Kepanitiaan <span class="text-muted fw-normal">(opsional)</span>
                </label>
                <p class="text-muted small mb-2">Pilih template untuk otomatis membuat struktur divisi panitia pada event ini.</p>
                <select class="form-select" id="template_id" name="template_id">
                    <option value="">— Tanpa Template —</option>
                    @foreach($templates as $tpl)
                        @php $jumlahDivisi = is_array($tpl->struktur) ? count($tpl->struktur) : count(json_decode($tpl->struktur, true) ?? []); @endphp
                        <option value="{{ $tpl->id }}" {{ old('template_id') == $tpl->id ? 'selected' : '' }}>
                            {{ $tpl->nama_template }} ({{ $jumlahDivisi }} divisi)
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
@endif
