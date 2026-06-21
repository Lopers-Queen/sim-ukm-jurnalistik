<x-guest-layout>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-key text-white fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Reset Password</h5>
                <p class="text-muted small">Masukkan password baru untuk akun Anda.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email', $request->email) }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Reset Password
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
