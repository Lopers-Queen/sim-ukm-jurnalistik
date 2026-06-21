@extends('layouts.app')

@section('title', 'Profil Saya — SIM UKM Jurnalistik')
@section('page-title', 'Profil Saya')

@section('breadcrumb')
<li class="breadcrumb-item active">Profil Saya</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                {{-- Foto Profil --}}
                <div class="position-relative d-inline-block mb-3">
                    @if($anggota->foto_profil_url)
                        <img src="{{ $anggota->foto_profil_url }}" alt="Foto Profil"
                             class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="avatar avatar-lg mx-auto">
                            {{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                </div>

                {{-- Upload Form --}}
                <form method="POST" action="{{ route('profile.update-foto') }}" enctype="multipart/form-data" class="mb-2">
                    @csrf
                    <div class="mb-2">
                        <input type="file" class="form-control form-control-sm @error('foto_profil') is-invalid @enderror"
                               name="foto_profil" accept="image/jpeg,image/png,image/jpg" id="fotoInput">
                        @error('foto_profil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">JPG/PNG, maks 2MB</div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-upload me-1"></i>Upload Foto
                    </button>
                </form>

                @if($anggota->foto_profil_url)
                <form method="POST" action="{{ route('profile.delete-foto') }}" class="mb-3">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                            onclick="return confirm('Hapus foto profil?')">
                        <i class="bi bi-trash me-1"></i>Hapus Foto
                    </button>
                </form>
                @endif

                <h5 class="fw-bold mb-1">{{ $anggota->nama_lengkap }}</h5>
                <p class="text-muted mb-1">{{ $anggota->jabatan_lengkap }}</p>
                <span class="badge bg-{{ $anggota->status_keanggotaan == 'aktif' ? 'success' : 'warning' }}">
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
            </div>
        </div>
    </div>

    {{-- Tabbed Content --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent p-0">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-profil" type="button">
                            <i class="bi bi-person me-1"></i> Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-password" type="button">
                            <i class="bi bi-lock me-1"></i> Ubah Password
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-login-history" type="button">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keamanan" type="button">
                            <i class="bi bi-shield-lock me-1"></i> Keamanan
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Tab: Profil --}}
                    <div class="tab-pane fade show active" id="tab-profil">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf @method('PATCH')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                           id="nama_lengkap" name="nama_lengkap"
                                           value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}">
                                    @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email', $anggota->email) }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label fw-semibold">No. HP</label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                           id="no_hp" name="no_hp"
                                           value="{{ old('no_hp', $anggota->no_hp) }}">
                                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror"
                                              id="alamat" name="alamat" rows="2">{{ old('alamat', $anggota->alamat) }}</textarea>
                                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    {{-- Tab: Ubah Password --}}
                    <div class="tab-pane fade" id="tab-password">
                        <form method="POST" action="{{ route('password.update') }}" x-data="passwordStrength()">
                            @csrf @method('PUT')

                            @if(session('status') === 'password-updated')
                                <div class="alert alert-success small mb-3">
                                    <i class="bi bi-check-circle me-1"></i> Password berhasil diubah.
                                </div>
                            @endif

                            @if($errors->updatePassword->any())
                                <div class="alert alert-danger py-2 small mb-3">
                                    @foreach($errors->updatePassword->all() as $error)
                                        <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                       name="current_password">
                                @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                       name="password" x-model="password" x-on:input="checkStrength()">
                                @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                {{-- Password strength indicator --}}
                                <div class="mt-2" x-show="password.length > 0">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" :class="strengthClass" :style="'width: ' + strengthPercent + '%'"></div>
                                    </div>
                                    <small :class="strengthTextClass" class="mt-1" x-text="strengthLabel"></small>
                                    <div class="small text-muted mt-1">
                                        <span :class="checks.length ? 'text-success' : 'text-muted'"><i class="bi" :class="checks.length ? 'bi-check-circle' : 'bi-circle'"></i> Min 8 karakter</span>
                                        <span :class="checks.upper ? 'text-success' : 'text-muted'" class="ms-2"><i class="bi" :class="checks.upper ? 'bi-check-circle' : 'bi-circle'"></i> Huruf besar</span>
                                        <span :class="checks.lower ? 'text-success' : 'text-muted'" class="ms-2"><i class="bi" :class="checks.lower ? 'bi-check-circle' : 'bi-circle'"></i> Huruf kecil</span>
                                        <span :class="checks.number ? 'text-success' : 'text-muted'" class="ms-2"><i class="bi" :class="checks.number ? 'bi-check-circle' : 'bi-circle'"></i> Angka</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-lock me-1"></i> Ubah Password
                            </button>
                        </form>
                    </div>

                    {{-- Tab: Riwayat Login --}}
                    <div class="tab-pane fade" id="tab-login-history">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Waktu Login</th>
                                        <th>IP Address</th>
                                        <th>Browser / Device</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loginHistory ?? [] as $log)
                                    <tr>
                                        <td>{{ $log->attempted_at?->format('d/m/Y H:i') }}</td>
                                        <td><code>{{ $log->ip_address }}</code></td>
                                        <td class="small">{{ Str::limit($log->user_agent, 40) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $log->status === 'success' ? 'success' : 'danger' }}">
                                                {{ $log->status === 'success' ? 'Berhasil' : 'Gagal' }}
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

                    {{-- Tab: Keamanan --}}
                    <div class="tab-pane fade" id="tab-keamanan">
                        <div class="mb-4">
                            <h6 class="fw-semibold">Informasi Sesi</h6>
                            <div class="small">
                                <p><strong>Login terakhir:</strong> {{ $anggota->last_login_at?->diffForHumans() ?? 'Belum pernah' }}</p>
                                <p><strong>IP terakhir:</strong> <code>{{ $anggota->last_login_ip ?? '-' }}</code></p>
                                <p><strong>Percobaan login gagal:</strong> {{ $anggota->failed_login_attempts ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            Untuk keamanan, session akan berakhir otomatis setelah <strong>30 menit</strong> tidak aktif.
                            Jangan pernah membagikan password Anda kepada siapapun.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function passwordStrength() {
    return {
        password: '',
        strengthPercent: 0,
        strengthLabel: '',
        strengthClass: 'bg-danger',
        strengthTextClass: 'text-danger',
        checks: { length: false, upper: false, lower: false, number: false },
        checkStrength() {
            this.checks.length = this.password.length >= 8;
            this.checks.upper  = /[A-Z]/.test(this.password);
            this.checks.lower  = /[a-z]/.test(this.password);
            this.checks.number = /[0-9]/.test(this.password);

            const score = Object.values(this.checks).filter(Boolean).length;

            if (score <= 1) {
                this.strengthPercent = 25; this.strengthLabel = 'Lemah'; this.strengthClass = 'bg-danger'; this.strengthTextClass = 'text-danger';
            } else if (score === 2) {
                this.strengthPercent = 50; this.strengthLabel = 'Cukup'; this.strengthClass = 'bg-warning'; this.strengthTextClass = 'text-warning';
            } else if (score === 3) {
                this.strengthPercent = 75; this.strengthLabel = 'Baik'; this.strengthClass = 'bg-info'; this.strengthTextClass = 'text-info';
            } else {
                this.strengthPercent = 100; this.strengthLabel = 'Kuat'; this.strengthClass = 'bg-success'; this.strengthTextClass = 'text-success';
            }
        },
    };
}
</script>
@endpush
