@extends('layouts.app')
@section('title', 'Detail Event — SIM UKM Jurnalistik')
@section('page-title', $event->nama_event)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('event.index') }}">Event</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Event --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="fw-bold">{{ $event->nama_event }}</h5>
                <span class="badge bg-{{ $event->status_badge }} mb-3">{{ $event->status_label }}</span>
                <div class="small">
                    <p><i class="bi bi-calendar me-2"></i><strong>Mulai:</strong> {{ $event->tanggal_mulai->translatedFormat('d F Y') }}</p>
                    @if($event->tanggal_selesai)<p><i class="bi bi-calendar-check me-2"></i><strong>Selesai:</strong> {{ $event->tanggal_selesai->translatedFormat('d F Y') }}</p>@endif
                    <p><i class="bi bi-geo-alt me-2"></i><strong>Lokasi:</strong> {{ $event->lokasi ?? '-' }}</p>
                    <p><i class="bi bi-person me-2"></i><strong>PIC:</strong> {{ $event->pic?->nama_lengkap ?? '-' }}</p>
                    <p><i class="bi bi-calendar-range me-2"></i><strong>Periode:</strong> {{ $event->periode?->nama_periode ?? '-' }}</p>
                    <p><i class="bi bi-cash me-2"></i><strong>Anggaran:</strong> Rp {{ number_format($event->anggaran_total ?? 0, 0, ',', '.') }}</p>
                </div>
                @if($event->deskripsi)<hr><p class="small text-muted">{{ $event->deskripsi }}</p>@endif
                <div class="d-flex gap-2 mt-3">
                    @can('event.edit')<a href="{{ route('event.edit', $event) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                    <a href="{{ route('event.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>

        {{-- Form Tambah Divisi Panitia --}}
        @can('kepanitiaan.create')
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold">Tambah Divisi Panitia</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('event.add-divisi', $event) }}">@csrf
                    <div class="mb-2"><input type="text" class="form-control form-control-sm" name="nama_divisi" placeholder="Nama divisi" required></div>
                    <div class="mb-2"><input type="text" class="form-control form-control-sm" name="deskripsi" placeholder="Deskripsi (opsional)"></div>
                    <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-plus me-1"></i>Tambah Divisi</button>
                </form>
            </div>
        </div>
        @endcan
    </div>

    {{-- Kepanitiaan --}}
    <div class="col-lg-8">
        @foreach($event->divisiPanitia as $divisi)
        <div class="card mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-diagram-3 me-1"></i>{{ $divisi->nama_divisi }}</h6>
                <span class="badge bg-primary">{{ $divisi->anggotaPanitia->count() }} anggota</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>Jabatan Panitia</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($divisi->anggotaPanitia as $ap)
                    <tr>
                        <td>{{ $ap->anggota?->nama_lengkap }}</td>
                        <td><span class="badge bg-secondary">{{ $ap->jabatan_panitia }}</span></td>
                        <td>@can('kepanitiaan.delete')
                            <form method="POST" action="{{ route('event.remove-panitia', [$event, $ap]) }}" onsubmit="return confirm('Hapus dari panitia?')">
                                @csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm"><i class="bi bi-x"></i></button>
                            </form>@endcan</td>
                    </tr>
                    @empty<tr><td colspan="3" class="text-center text-muted py-3">Belum ada anggota</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        {{-- Form Assign Panitia --}}
        @can('kepanitiaan.create')
        @if($event->divisiPanitia->isNotEmpty())
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold">Assign Anggota ke Panitia</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('event.assign-panitia', $event) }}">@csrf
                    <div class="row g-2">
                        <div class="col-md-4"><select class="form-select form-select-sm select-search" name="anggota_id" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach(\App\Models\Anggota::where('status_keanggotaan','aktif')->where('jabatan_struktural','!=','admin')->orderBy('nama_lengkap')->get() as $a)
                            <option value="{{ $a->id }}">{{ $a->nama_lengkap }}</option>@endforeach
                        </select></div>
                        <div class="col-md-3"><select class="form-select form-select-sm" name="divisi_panitia_id" required>
                            <option value="">-- Divisi --</option>
                            @foreach($event->divisiPanitia as $d)<option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>@endforeach
                        </select></div>
                        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="jabatan_panitia" placeholder="Jabatan" required></div>
                        <div class="col-md-2"><button class="btn btn-primary btn-sm w-100"><i class="bi bi-plus me-1"></i>Assign</button></div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endcan

        @if($event->divisiPanitia->isEmpty())
        <div class="text-center text-muted py-5"><i class="bi bi-diagram-3 fs-1 d-block mb-2"></i>Belum ada divisi panitia. Tambahkan divisi terlebih dahulu.</div>
        @endif
    </div>
</div>
@endsection
