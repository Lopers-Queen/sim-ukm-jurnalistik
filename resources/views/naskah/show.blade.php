@extends('layouts.app')
@section('title', 'Detail Naskah — SIM UKM Jurnalistik')
@section('page-title', 'Detail Naskah')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('naskah.index') }}">Naskah Redaksi</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">{{ $naskah->judul }}</h5>
            <span class="badge bg-{{ $naskah->status_badge }}">{{ $naskah->status_label }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-3 small text-muted">
                <div class="col-md-4"><i class="bi bi-person me-1"></i>Penulis: {{ $naskah->penulis?->nama_lengkap ?? '-' }}</div>
                <div class="col-md-4"><i class="bi bi-tag me-1"></i>Kategori: {{ $naskah->kategori ?? '-' }}</div>
                <div class="col-md-4"><i class="bi bi-calendar me-1"></i>Dibuat: {{ $naskah->created_at->translatedFormat('d F Y') }}</div>
            </div>
            @if($naskah->editor)
            <div class="alert alert-info py-2 small"><strong>Editor:</strong> {{ $naskah->editor->nama_lengkap }}
                @if($naskah->catatan_editor)<br><strong>Catatan:</strong> {{ $naskah->catatan_editor }}@endif
            </div>
            @endif
            <div class="bg-light rounded p-4 mb-4" style="white-space: pre-wrap;">{{ $naskah->konten }}</div>
            <div class="d-flex gap-2 flex-wrap">
                @if($naskah->status === 'draft')
                    @can('naskah-redaksi.edit')<a href="{{ route('naskah.edit', $naskah) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                    @can('naskah-redaksi.edit')
                    <form method="POST" action="{{ route('naskah.submit-review', $naskah) }}">@csrf<button class="btn btn-info btn-sm"><i class="bi bi-send me-1"></i>Submit Review</button></form>
                    @endcan
                @endif
                @if($naskah->status === 'review')
                    @can('naskah-redaksi.approve')
                    <form method="POST" action="{{ route('naskah.approve', $naskah) }}">@csrf<button class="btn btn-success btn-sm"><i class="bi bi-check me-1"></i>Setujui</button></form>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#revisiModal"><i class="bi bi-arrow-return-left me-1"></i>Revisi</button>
                    @endcan
                @endif
                @if($naskah->status === 'disetujui')
                    @can('naskah-redaksi.approve')
                    <form method="POST" action="{{ route('naskah.publish', $naskah) }}">@csrf<button class="btn btn-success btn-sm"><i class="bi bi-globe me-1"></i>Publish</button></form>
                    @endcan
                @endif
                <a href="{{ route('naskah.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div></div>
{{-- Revisi Modal --}}
<div class="modal fade" id="revisiModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="{{ route('naskah.revisi', $naskah) }}">@csrf
    <div class="modal-header"><h5 class="modal-title">Revisi Naskah</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><textarea class="form-control" name="catatan_editor" rows="4" placeholder="Tulis catatan revisi (min 10 karakter)..." required minlength="10"></textarea></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Kirim Revisi</button></div>
    </form>
</div></div></div>
@endsection
