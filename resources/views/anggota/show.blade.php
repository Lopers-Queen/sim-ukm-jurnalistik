@extends('layouts.app')

@section('title', 'Detail Anggota — SIM UKM Jurnalistik')
@section('page-title', 'Detail Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-3">
                    {{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}
                </div>
                <h5 class="fw-bold">{{ $anggota->nama_lengkap }}</h5>
                <p class="text-muted mb-1">{{ $anggota->jabatan_lengkap }}</p>
                <span class="badge bg-{{ $anggota->status_keanggotaan == 'aktif' ? 'success' : ($anggota->status_keanggotaan == 'pasif' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($anggota->status_keanggotaan) }}
                </span>
                <hr>
                <div class="text-start small">
                    <p><i class="bi bi-credit-card me-2"></i><strong>NIM:</strong> {{ $anggota->nim }}</p>
                    <p><i class="bi bi-envelope me-2"></i><strong>Email:</strong> {{ $anggota->email }}</p>
                    <p><i class="bi bi-building me-2"></i><strong>Divisi:</strong> {{ $anggota->divisi_label }}</p>
                    <p><i class="bi bi-telephone me-2"></i><strong>No HP:</strong> {{ $anggota->no_hp ?? '-' }}</p>
                    <p><i class="bi bi-calendar me-2"></i><strong>Bergabung:</strong> {{ $anggota->tanggal_bergabung?->translatedFormat('d F Y') ?? '-' }}</p>
                    <p><i class="bi bi-clock me-2"></i><strong>Masa Keanggotaan:</strong> {{ $anggota->masaKeanggotaan() }} tahun</p>
                </div>

                @can('organisasi.reset-password-anggota')
                <form method="POST" action="{{ route('anggota.reset-password', $anggota) }}"
                      onsubmit="return confirm('Reset password {{ $anggota->nama_lengkap }} ke default?')">
                    @csrf
                    <button class="btn btn-warning btn-sm w-100 mt-2">
                        <i class="bi bi-key me-1"></i> Reset Password
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    {{-- Detail Tabs --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent p-0">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-riwayat-jabatan" type="button">
                            <i class="bi bi-person-badge me-1"></i> Riwayat Jabatan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-riwayat-panitia" type="button">
                            <i class="bi bi-people me-1"></i> Riwayat Kepanitiaan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-login-log" type="button">
                            <i class="bi bi-clock-history me-1"></i> Log Login
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    {{-- Tab: Riwayat Kepengurusan (FR-16) --}}
                    <div class="tab-pane fade show active" id="tab-riwayat-jabatan">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Periode</th>
                                        <th>Jabatan</th>
                                        <th>Divisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota->riwayatKepengurusan as $riwayat)
                                    <tr>
                                        <td>{{ $riwayat->periode->nama_periode ?? '-' }}</td>
                                        <td><span class="fw-semibold">{{ $riwayat->jabatan_label }}</span></td>
                                        <td>{{ $riwayat->divisi ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $riwayat->status === 'aktif' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($riwayat->status ?? 'selesai') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat kepengurusan</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab: Riwayat Kepanitiaan (FR-20) --}}
                    <div class="tab-pane fade" id="tab-riwayat-panitia">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Divisi Panitia</th>
                                        <th>Jabatan</th>
                                        <th>Tanggal</th>
                                        <th>Status Event</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota->kepanitiaan as $panitia)
                                    <tr>
                                        <td>
                                            <a href="{{ route('event.show', $panitia->event_id) }}" class="text-decoration-none fw-semibold">
                                                {{ $panitia->event->nama_event ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $panitia->divisiPanitia->nama_divisi ?? '-' }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $panitia->jabatan_panitia }}</span></td>
                                        <td class="small">{{ $panitia->event->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                        <td>
                                            @php $eventStatus = $panitia->event->status ?? 'draft'; @endphp
                                            <span class="badge bg-{{
                                                match($eventStatus) {
                                                    'selesai' => 'success',
                                                    'aktif' => 'primary',
                                                    'direncanakan' => 'warning',
                                                    'batal' => 'danger',
                                                    default => 'secondary'
                                                }
                                            }}">{{ ucfirst($eventStatus) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">Belum pernah menjadi panitia</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab: Login Log --}}
                    <div class="tab-pane fade" id="tab-login-log">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota->loginHistories as $log)
                                    <tr>
                                        <td>{{ $log->attempted_at?->format('d/m/Y H:i') }}</td>
                                        <td><code>{{ $log->ip_address }}</code></td>
                                        <td class="small">{{ Str::limit($log->user_agent, 35) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $log->status === 'success' ? 'success' : 'danger' }}">
                                                {{ $log->status === 'success' ? 'Berhasil' : ucfirst($log->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat login</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            @can('organisasi.edit')
            <a href="{{ route('anggota.edit', $anggota) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            @endcan
            <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
