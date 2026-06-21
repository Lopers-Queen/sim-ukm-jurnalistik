<x-guest-layout>
    <div class="d-flex gap-3 align-items-start justify-content-center flex-wrap">
        {{-- Login Card --}}
        <div class="card shadow-sm border-0 login-card">
            <div class="card-body p-4">
                {{-- Logo & Title --}}
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo UKM Jurnalistik" class="mb-3" style="width: 80px; height: 80px; object-fit: contain;">
                    <h5 class="fw-bold mb-1">SIM UKM Jurnalistik</h5>
                    <p class="text-muted small mb-0">Politeknik Negeri Samarinda</p>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        @foreach ($errors->all() as $error)
                            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nim" class="form-label fw-semibold small">NIM (Nomor Induk Mahasiswa)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="nim" name="nim"
                                   value="{{ old('nim') }}" required autofocus
                                   placeholder="Masukkan NIM Anda">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                   required placeholder="Masukkan password">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </form>

                {{-- Credential Info --}}
                <div class="mt-4 p-3 rounded small" style="background: var(--surface-2); border: 1px solid var(--surface-3);">
                    <div class="fw-semibold mb-1" style="color: var(--heading);"><i class="bi bi-info-circle me-1"></i>Informasi Kredensial Default</div>
                    <ul class="mb-0 ps-3" style="color: var(--body);">
                        <li><strong>Username:</strong> NIM Anda</li>
                        <li><strong>Password:</strong> Tanggal lahir (format DDMMYYYY)</li>
                        <li style="color: var(--muted);">Contoh: lahir 15 Mei 2001 → 15052001</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Quick Login Panel (DEV/DEBUG ONLY) --}}
        @if(app()->environment('local', 'development') || config('app.debug'))
        <div class="card shadow-sm border-0 quick-login-card">
            <div class="card-header bg-dark text-white py-2 px-3">
                <div class="fw-semibold small"><i class="bi bi-lightning-charge me-1"></i>Quick Login (Dev)</div>
            </div>
            <div class="card-body p-2">
                {{-- Super Admin Quick Access --}}
                <div class="d-grid mb-2">
                    <button type="button" class="btn btn-sm btn-danger text-start quick-login-auto"
                            data-nim="admin" data-pw="admin123">
                        <i class="bi bi-shield-lock me-1"></i><strong>Login as Super Admin</strong>
                    </button>
                </div>
                <hr class="my-2">
                <div class="d-grid gap-1">
                    {{-- Admin --}}
                    <div class="fw-semibold small text-muted mt-1 px-1">🔑 Super Admin</div>
                    <button type="button" class="btn btn-sm btn-outline-danger text-start quick-login"
                            data-nim="admin" data-pw="admin123">
                        <i class="bi bi-shield-lock me-1"></i>Administrator
                    </button>

                    {{-- BPI --}}
                    <div class="fw-semibold small text-muted mt-2 px-1">👑 Badan Pengurus Inti</div>
                    <button type="button" class="btn btn-sm btn-outline-primary text-start quick-login"
                            data-nim="236651093" data-pw="12345678">
                        <i class="bi bi-star me-1"></i>Ketua Umum
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary text-start quick-login"
                            data-nim="236511039" data-pw="12032004">
                        <i class="bi bi-star-half me-1"></i>Wakil Ketua
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info text-start quick-login"
                            data-nim="246522033" data-pw="20062005">
                        <i class="bi bi-journal me-1"></i>Sekretaris 1
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info text-start quick-login"
                            data-nim="236611052" data-pw="05112004">
                        <i class="bi bi-journal me-1"></i>Sekretaris 2
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success text-start quick-login"
                            data-nim="246221022" data-pw="18042005">
                        <i class="bi bi-wallet2 me-1"></i>Bendahara 1
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success text-start quick-login"
                            data-nim="246221025" data-pw="25122005">
                        <i class="bi bi-wallet2 me-1"></i>Bendahara 2
                    </button>

                    {{-- BPH Kadiv --}}
                    <div class="fw-semibold small text-muted mt-2 px-1">📋 Kepala Divisi</div>
                    <button type="button" class="btn btn-sm btn-outline-warning text-start quick-login"
                            data-nim="246521041" data-pw="05072005">
                        <i class="bi bi-broadcast me-1"></i>Kadiv Pers
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning text-start quick-login"
                            data-nim="246651001" data-pw="10012005">
                        <i class="bi bi-camera me-1"></i>Kadiv Fotografi
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning text-start quick-login"
                            data-nim="246652010" data-pw="22092005">
                        <i class="bi bi-camera-video me-1"></i>Kadiv Videografi
                    </button>

                    {{-- BPH Kanit --}}
                    <div class="fw-semibold small text-muted mt-2 px-1">🔧 Kepala Unit</div>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start quick-login"
                            data-nim="236151093" data-pw="28022004">
                        <i class="bi bi-box me-1"></i>Kanit Inventory
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start quick-login"
                            data-nim="236201022" data-pw="17052004">
                        <i class="bi bi-megaphone me-1"></i>Kanit Kominfo
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-start quick-login"
                            data-nim="236201036" data-pw="03102004">
                        <i class="bi bi-pencil me-1"></i>Kanit Redaksi
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if(app()->environment('local', 'development') || config('app.debug'))
    <script>
        // Quick fill — isi form saja, user klik Masuk sendiri
        document.querySelectorAll('.quick-login').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('nim').value = btn.dataset.nim;
                document.getElementById('password').value = btn.dataset.pw;
                document.getElementById('nim').focus();
            });
        });

        // Quick auto-login — langsung submit form
        document.querySelectorAll('.quick-login-auto').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('nim').value = btn.dataset.nim;
                document.getElementById('password').value = btn.dataset.pw;
                btn.closest('form') || document.querySelector('form').submit();
            });
        });
    </script>
    @endif
</x-guest-layout>

