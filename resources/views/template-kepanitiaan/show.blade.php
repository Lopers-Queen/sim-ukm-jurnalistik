@extends('layouts.app')
@section('title', $template->nama_template . ' — Template Kepanitiaan')
@section('page-title', 'Detail Template')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('template-kepanitiaan.index') }}">Template Panitia</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">{{ $template->nama_template }}</h4>
        <p class="text-muted mb-0">{{ $template->deskripsi ?? 'Tidak ada deskripsi' }}</p>
    </div>
    <div class="d-flex gap-2">
        @can('template-panitia.edit')
        <a href="{{ route('template-kepanitiaan.edit', $template) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Edit</a>
        @endcan
        <a href="{{ route('template-kepanitiaan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted text-uppercase fw-semibold">Status</small>
                    <div><span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }} mt-1">{{ $template->is_active ? 'Aktif' : 'Arsip' }}</span></div>
                </div>
                <div class="mb-3">
                    <small class="text-muted text-uppercase fw-semibold">Dibuat</small>
                    <div>{{ $template->created_at?->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold">Struktur Divisi Panitia</h6>
            </div>
            <div class="card-body">
                @php $divisiList = is_array($template->struktur) ? $template->struktur : (json_decode($template->struktur, true) ?? []); @endphp
                @forelse($divisiList as $i => $divisi)
                <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3 text-center" style="min-width: 40px;">
                        <span class="fw-bold text-primary">{{ $i + 1 }}</span>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $divisi['nama'] ?? '-' }}</div>
                        <small class="text-muted">{{ $divisi['deskripsi'] ?? 'Tidak ada deskripsi tugas' }}</small>
                        @if(!empty($divisi['estimasi_anggota']))
                            <span class="badge bg-light text-dark ms-2">Est. {{ $divisi['estimasi_anggota'] }} orang</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada divisi dalam template ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
