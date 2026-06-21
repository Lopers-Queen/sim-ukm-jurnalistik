<x-guest-layout>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-key text-white fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Ganti Password</h5>
                <p class="text-muted small mb-0">Admin telah mereset password Anda.</p>
                <p class="text-muted small">Anda dapat mengganti password sekarang atau melewatkannya.</p>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    @foreach ($errors->all() as $error)
                        <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning py-2 small">
                    <i class="bi bi-exclamation-triangle me-1"></i>{{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info py-2 small">
                    <i class="bi bi-info-circle me-1"></i>{{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.first-change.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" autofocus>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation">
                </div>

                {{-- Password Requirements --}}
                <div class="p-3 bg-light rounded small mb-3">
                    <div class="fw-semibold mb-1">Syarat Password:</div>
                    <ul class="mb-0 ps-3 text-muted">
                        <li>Minimal 8 karakter</li>
                        <li>Minimal 1 huruf besar & 1 huruf kecil</li>
                        <li>Minimal 1 angka</li>
                        <li>Tidak boleh sama dengan NIM</li>
                        <li>Tidak boleh sama dengan tanggal lahir</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold mb-2">
                    <i class="bi bi-check-lg me-1"></i> Simpan Password Baru
                </button>
            </form>

            {{-- Skip Button --}}
            <form method="POST" action="{{ route('password.first-change.skip') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100 fw-semibold"
                        onclick="return confirm('Anda yakin ingin melewati ganti password? Password dapat diganti kapan saja melalui menu Profil.')">
                    <i class="bi bi-arrow-right-circle me-1"></i> Lewati — Gunakan Password Saat Ini
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
