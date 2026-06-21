<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\LoginHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller Login Custom (FR-01)
 * Login menggunakan NIM sebagai username.
 * Menggantikan AuthenticatedSessionController bawaan Breeze.
 */
class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     * - Validasi NIM + password
     * - Cek account lockout (5x gagal = lock 15 menit)
     * - Catat login history
     * - Redirect ke first-password-change jika is_first_login
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting: 5x per menit per IP
        $throttleKey = Str::transliterate(
            Str::lower($request->input('nim')) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'nim' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('nim');
        }

        // Cari anggota berdasarkan NIM
        $anggota = Anggota::where('nim', $request->input('nim'))->first();

        if (! $anggota) {
            RateLimiter::hit($throttleKey);

            return back()->withErrors([
                'nim' => 'NIM atau password salah.',
            ])->onlyInput('nim');
        }

        // Cek apakah akun terkunci
        if ($anggota->isLocked()) {
            // Catat attempt saat terkunci
            LoginHistory::create([
                'anggota_id'  => $anggota->id,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'locked',
                'keterangan'  => 'Akun terkunci hingga ' . $anggota->locked_until->format('d/m/Y H:i'),
                'attempted_at' => now(),
            ]);

            $menit = $anggota->locked_until->diffInMinutes(now());

            return back()->withErrors([
                'nim' => "Akun Anda terkunci. Silakan coba lagi dalam {$menit} menit.",
            ])->onlyInput('nim');
        }

        // Verifikasi password
        if (! Hash::check($request->input('password'), $anggota->password)) {
            // Increment gagal login
            $anggota->incrementFailedLogin();

            RateLimiter::hit($throttleKey);

            // Catat gagal login
            LoginHistory::create([
                'anggota_id'  => $anggota->id,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
                'keterangan'  => "Gagal login (percobaan ke-{$anggota->failed_login_attempts})",
                'attempted_at' => now(),
            ]);

            $sisaPercobaan = 5 - $anggota->failed_login_attempts;

            if ($sisaPercobaan <= 0) {
                return back()->withErrors([
                    'nim' => 'Akun Anda telah dikunci selama 15 menit karena terlalu banyak percobaan login yang gagal.',
                ])->onlyInput('nim');
            }

            return back()->withErrors([
                'nim' => "NIM atau password salah. Sisa percobaan: {$sisaPercobaan}",
            ])->onlyInput('nim');
        }

        // Login berhasil
        $anggota->resetFailedLogin();
        RateLimiter::clear($throttleKey);

        // Catat login berhasil
        LoginHistory::create([
            'anggota_id'  => $anggota->id,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'status'      => 'success',
            'keterangan'  => 'Login berhasil',
            'attempted_at' => now(),
        ]);

        Auth::login($anggota, $request->boolean('remember'));

        $request->session()->regenerate();

        // Redirect ke ganti password jika first login
        if ($anggota->isFirstLogin()) {
            return redirect()->route('password.first-change');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
