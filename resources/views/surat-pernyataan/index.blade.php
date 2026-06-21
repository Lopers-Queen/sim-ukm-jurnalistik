@extends('layouts.app')
@section('title', 'Surat Pernyataan — SIM UKM Jurnalistik')
@section('page-title', 'Surat Pernyataan')
@section('breadcrumb')
<li class="breadcrumb-item active">Surat Pernyataan</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1">Surat Pernyataan</h4><p class="text-muted mb-0">Kelola surat pernyataan anggota</p></div>
    @can('surat-pernyataan.generate')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal"><i class="bi bi-plus-lg me-1"></i> Generate Surat</button>
    @endcan
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>#</th><th>Anggota</th><th>Event</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($suratList as $i => $surat)
        <tr>
            <td>{{ $suratList->firstItem() + $i }}</td>
            <td class="fw-semibold">{{ $surat->anggota?->nama_lengkap ?? '-' }}</td>
            <td>{{ $surat->event?->nama_event ?? '-' }}</td>
            <td>
                @php $badge = match($surat->status) {
                    'pending_ttd' => 'warning', 'menunggu_konfirmasi' => 'info',
                    'disetujui' => 'success', 'ditolak' => 'danger', default => 'secondary'
                }; @endphp
                <span class="badge bg-{{ $badge }}">{{ ucwords(str_replace('_',' ',$surat->status)) }}</span>
            </td>
            <td class="small">{{ $surat->created_at->format('d/m/Y H:i') }}</td>
            <td><div class="btn-group btn-group-sm">
                <a href="{{ route('surat-pernyataan.show', $surat) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                @if($surat->file_pdf)<a href="{{ route('surat-pernyataan.download', $surat) }}" class="btn btn-outline-danger"><i class="bi bi-filetype-pdf"></i></a>@endif
            </div></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-file-earmark-check fs-1 d-block mb-2"></i>Belum ada surat pernyataan</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($suratList->hasPages())<div class="card-footer">{{ $suratList->links() }}</div>@endif
</div>

{{-- Generate Modal --}}
<div class="modal fade" id="generateModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="{{ route('surat-pernyataan.generate') }}">@csrf
    <div class="modal-header"><h5 class="modal-title">Generate Surat Pernyataan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-semibold">Anggota <span class="text-danger">*</span></label>
            <select class="form-select select-search" name="anggota_id" required>
                <option value="">-- Pilih Anggota --</option>
                @foreach(\App\Models\Anggota::where('status_keanggotaan','aktif')->where('jabatan_struktural','!=','admin')->orderBy('nama_lengkap')->get() as $a)
                <option value="{{ $a->id }}">{{ $a->nama_lengkap }} ({{ $a->nim }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label class="form-label fw-semibold">Event <span class="text-danger">*</span></label>
            <select class="form-select" name="event_id" required>
                <option value="">-- Pilih Event --</option>
                @foreach(\App\Models\Event::latest()->get() as $e)
                <option value="{{ $e->id }}">{{ $e->nama_event }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Generate</button></div>
    </form>
</div></div></div>
@endsection
