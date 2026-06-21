@extends('layouts.app')
@section('title', 'Buat Laporan — ' . $event->nama_event)
@section('page-title', 'Laporan Pasca Event')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('event.show', $event) }}">{{ $event->nama_event }}</a></li>
<li class="breadcrumb-item active">Buat Laporan</li>
@endsection

@section('content')
<div class="page-header">
    <h4 class="fw-bold mb-1">Buat Laporan Pasca Event</h4>
    <p class="text-muted mb-0">{{ $event->nama_event }} — {{ $event->tanggal_mulai?->format('d M Y') }}</p>
</div>

<form method="POST" action="{{ route('laporan-event.store', $event) }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- Left column --}}
        <div class="col-lg-8">
            {{-- Notulensi --}}
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-journal-text me-2"></i>Notulensi Event</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ringkasan Jalannya Acara <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="notulensi" rows="5" required placeholder="Jelaskan bagaimana acara berlangsung...">{{ old('notulensi') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Daftar Peserta</label>
                        <textarea class="form-control" name="daftar_peserta" rows="3" placeholder="Opsional — daftar peserta yang hadir">{{ old('daftar_peserta') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Evaluasi --}}
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard-check me-2"></i>Evaluasi Event</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kendala yang Dihadapi <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="kendala" rows="3" required>{{ old('kendala') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pencapaian <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="pencapaian" rows="3" required>{{ old('pencapaian') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Saran Perbaikan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="saran_perbaikan" rows="3" required>{{ old('saran_perbaikan') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rating Internal (1-5) <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2" x-data="{ rating: {{ old('rating_internal', 3) }} }">
                            @for($r = 1; $r <= 5; $r++)
                            <button type="button" class="btn btn-lg p-0 border-0" x-on:click="rating = {{ $r }}">
                                <i class="bi" :class="rating >= {{ $r }} ? 'bi-star-fill text-warning' : 'bi-star text-muted'"></i>
                            </button>
                            @endfor
                            <input type="hidden" name="rating_internal" x-model="rating">
                            <span class="ms-2 fw-semibold" x-text="rating + '/5'"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dokumentasi --}}
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-camera me-2"></i>Dokumentasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Kegiatan</label>
                        <input type="file" class="form-control" name="dokumentasi_foto[]" multiple accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Maks 5MB per file.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Video (YouTube/Drive)</label>
                        <input type="url" class="form-control" name="dokumentasi_video_link" value="{{ old('dokumentasi_video_link') }}" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Materi (File)</label>
                        <input type="file" class="form-control" name="materi" accept=".pdf,.doc,.docx,.ppt,.pptx">
                        <small class="text-muted">Format: PDF, DOC, PPT. Maks 10MB.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column — Keuangan --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-cash-coin me-2"></i>Laporan Keuangan (Auto)</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-semibold">Total Pemasukan</small>
                        <div class="fs-5 fw-bold text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-semibold">Total Pengeluaran</small>
                        <div class="fs-5 fw-bold text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-semibold">Sisa Anggaran</small>
                        <div class="fs-4 fw-bold {{ $sisaAnggaran >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="alert alert-info small py-2">
                        <i class="bi bi-info-circle me-1"></i> Data keuangan diambil otomatis dari Anggaran Event (FR-22)
                    </div>
                </div>
            </div>

            {{-- Daftar Panitia --}}
            <div class="card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-people me-2"></i>Panitia (Auto)</h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @foreach($event->divisiPanitia as $div)
                        <div class="fw-semibold small text-primary mb-1">{{ $div->nama_divisi }}</div>
                        @foreach($div->anggotaPanitia as $ap)
                            <div class="small ms-3 mb-1">• {{ $ap->anggota->nama_lengkap ?? '-' }} <span class="text-muted">({{ $ap->jabatan_panitia }})</span></div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-lg me-1"></i> Simpan Laporan
                </button>
                <a href="{{ route('event.show', $event) }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection
