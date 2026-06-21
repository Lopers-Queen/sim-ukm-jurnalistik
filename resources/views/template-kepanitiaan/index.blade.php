@extends('layouts.app')

@section('title', 'Template Kepanitiaan — SIM UKM Jurnalistik')
@section('page-title', 'Template Kepanitiaan')

@section('breadcrumb')
<li class="breadcrumb-item active">Template Panitia</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">Template Kepanitiaan</h4>
        <p class="text-muted mb-0">Kelola template struktur kepanitiaan event (FR-19)</p>
    </div>
    @can('template-panitia.create')
    <a href="{{ route('template-kepanitiaan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Buat Template
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Template</th>
                        <th>Jumlah Divisi</th>
                        <th>Penggunaan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $i => $template)
                    <tr>
                        <td>{{ $templates->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold">{{ $template->nama_template }}</div>
                            <small class="text-muted">{{ Str::limit($template->deskripsi, 50) }}</small>
                        </td>
                        <td>
                            @php $divisi = is_array($template->struktur) ? $template->struktur : (json_decode($template->struktur, true) ?? []); @endphp
                            <span class="badge bg-primary">{{ count($divisi) }} divisi</span>
                        </td>
                        <td>—</td>
                        <td>
                            <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}">
                                {{ $template->is_active ? 'Aktif' : 'Arsip' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('template-kepanitiaan.show', $template) }}" class="btn btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                                @can('template-panitia.edit')
                                <a href="{{ route('template-kepanitiaan.edit', $template) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('template-panitia.create')
                                <form method="POST" action="{{ route('template-kepanitiaan.duplicate', $template) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-info" title="Duplikasi"><i class="bi bi-copy"></i></button>
                                </form>
                                @endcan
                                @can('template-panitia.delete')
                                <form method="POST" action="{{ route('template-kepanitiaan.destroy', $template) }}" class="d-inline" onsubmit="return confirm('Hapus template ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-clipboard-data fs-1 d-block mb-2"></i>
                            Belum ada template kepanitiaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $templates->links() }}
    </div>
</div>
@endsection
