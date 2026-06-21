@extends('layouts.app')

@section('title', isset($template) ? 'Edit Template' : 'Buat Template')
@section('page-title', isset($template) ? 'Edit Template' : 'Buat Template Kepanitiaan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('template-kepanitiaan.index') }}">Template Panitia</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h4 class="fw-bold mb-1">{{ isset($template) ? 'Edit Template' : 'Buat Template Kepanitiaan Baru' }}</h4>
    <p class="text-muted mb-0">Definisikan struktur divisi panitia yang dapat digunakan ulang</p>
</div>

<form method="POST" action="{{ isset($template) ? route('template-kepanitiaan.update', $template) : route('template-kepanitiaan.store') }}" x-data="templateForm()" class="row g-4">
    @csrf
    @if(isset($template)) @method('PUT') @endif

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold">Informasi Template</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Template <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_template" value="{{ old('nama_template', $template->nama_template ?? '') }}" placeholder="Contoh: Template Workshop" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Opsional">{{ old('deskripsi', $template->deskripsi ?? '') }}</textarea>
                </div>
                @if(isset($template))
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" name="is_active">
                        <option value="1" {{ ($template->is_active ?? true) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !($template->is_active ?? true) ? 'selected' : '' }}>Arsip</option>
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Divisi Panitia</h6>
                <button type="button" class="btn btn-sm btn-primary" x-on:click="addDivisi()">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Divisi
                </button>
            </div>
            <div class="card-body">
                <template x-for="(divisi, index) in divisiList" :key="index">
                    <div class="border rounded p-3 mb-3 position-relative">
                        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2"
                                x-on:click="removeDivisi(index)" x-show="divisiList.length > 1">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold">Nama Divisi</label>
                                <input type="text" class="form-control form-control-sm"
                                       x-bind:name="'divisi_panitia[' + index + '][nama]'"
                                       x-model="divisi.nama" placeholder="Contoh: Acara" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold">Deskripsi Tugas</label>
                                <input type="text" class="form-control form-control-sm"
                                       x-bind:name="'divisi_panitia[' + index + '][deskripsi]'"
                                       x-model="divisi.deskripsi" placeholder="Opsional">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Est. Anggota</label>
                                <input type="number" class="form-control form-control-sm"
                                       x-bind:name="'divisi_panitia[' + index + '][estimasi_anggota]'"
                                       x-model="divisi.estimasi_anggota" min="1" placeholder="0">
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="divisiList.length === 0" class="text-center text-muted py-3">
                    Klik "Tambah Divisi" untuk memulai
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('template-kepanitiaan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> {{ isset($template) ? 'Simpan Perubahan' : 'Buat Template' }}
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function templateForm() {
    @php
        $existingDivisi = [];
        if (isset($template) && $template->struktur) {
            $existingDivisi = is_array($template->struktur) ? $template->struktur : json_decode($template->struktur, true);
            if (!is_array($existingDivisi)) { $existingDivisi = []; }
        }
    @endphp
    return {
        divisiList: {!! json_encode(count($existingDivisi) > 0 ? $existingDivisi : [
            ['nama' => 'Acara', 'deskripsi' => 'Mengurus jalannya acara', 'estimasi_anggota' => 5],
            ['nama' => 'Konsumsi', 'deskripsi' => 'Mengurus konsumsi peserta & panitia', 'estimasi_anggota' => 3],
            ['nama' => 'Perlengkapan', 'deskripsi' => 'Mengurus perlengkapan acara', 'estimasi_anggota' => 3],
        ]) !!},
        addDivisi() {
            this.divisiList.push({ nama: '', deskripsi: '', estimasi_anggota: 3 });
        },
        removeDivisi(index) {
            this.divisiList.splice(index, 1);
        },
    };
}
</script>
@endpush
