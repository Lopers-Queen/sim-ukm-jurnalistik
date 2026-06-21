@extends('layouts.app')

@section('title', 'Impersonate Role')
@section('page-title', 'Impersonate Role')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-eye-fill text-danger fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Impersonate Role</h5>
                            <p class="text-muted small mb-0">
                                Lihat sistem dari perspektif role tertentu. Permission bypass Super Admin akan
                                <strong>dinonaktifkan sementara</strong> — Anda melihat persis apa yang dilihat role tersebut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Role Grid --}}
            <div class="row g-3">
                @foreach($roles as $role)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ ucwords(str_replace('_', ' ', $role->name)) }}</h6>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary small">
                                            {{ $role->permission_count }} permissions
                                        </span>
                                    </div>
                                    @if(session('impersonating_role') === $role->name)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i>Aktif
                                        </span>
                                    @endif
                                </div>

                                <p class="text-muted small mb-3">
                                    @if($role->name === 'super_admin')
                                        Akses penuh ke semua fitur sistem
                                    @elseif(str_starts_with($role->name, 'ketua') || str_starts_with($role->name, 'wakil'))
                                        Badan Pengurus Inti (BPI)
                                    @elseif(str_starts_with($role->name, 'sekretaris'))
                                        Sekretaris — administrasi & notulensi
                                    @elseif(str_starts_with($role->name, 'bendahara'))
                                        Bendahara — keuangan & anggaran
                                    @elseif(str_starts_with($role->name, 'kadiv'))
                                        Kepala Divisi — operasional divisi
                                    @elseif(str_starts_with($role->name, 'kanit'))
                                        Kepala Unit — operasional unit
                                    @elseif($role->name === 'staf')
                                        Staf pelaksana kegiatan
                                    @elseif($role->name === 'anggota_aktif')
                                        Anggota aktif dengan akses dasar
                                    @elseif($role->name === 'anggota_pasif')
                                        Anggota pasif — akses terbatas
                                    @elseif($role->name === 'ketua_panitia')
                                        Ketua panitia event (dinamis)
                                    @else
                                        Role sistem
                                    @endif
                                </p>

                                <form method="POST" action="{{ route('admin.start-impersonate', $role->name) }}">
                                    @csrf
                                    @if(session('impersonating_role') === $role->name)
                                        <a href="{{ route('admin.stop-impersonate') }}" class="btn btn-sm btn-outline-warning w-100">
                                            <i class="bi bi-x-circle me-1"></i>Berhenti Impersonate
                                        </a>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-eye me-1"></i>Impersonate Role Ini
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Stop Impersonate --}}
            @if(session('impersonating_role'))
                <div class="card border-warning bg-warning bg-opacity-10 mt-4">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            <strong>Anda sedang impersonate:</strong> {{ ucwords(str_replace('_', ' ', session('impersonating_role'))) }}
                        </div>
                        <a href="{{ route('admin.stop-impersonate') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Kembali ke Super Admin
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
