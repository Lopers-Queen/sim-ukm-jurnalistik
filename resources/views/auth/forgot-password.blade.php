<x-guest-layout>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-envelope text-white fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Lupa Password</h5>
                <p class="text-muted small">Masukkan email yang terdaftar. Kami akan mengirimkan link reset password.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success py-2 small">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               value="{{ old('email') }}" required autofocus placeholder="email@polnes.ac.id">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-send me-1"></i> Kirim Link Reset
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
