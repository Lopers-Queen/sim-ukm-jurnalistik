<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Controller Ganti Password Pertama Kali (FR-01)
 * Wajib dijalankan saat is_first_login = true.
 * User dapat memilih mengganti password atau melewatkannya.
 */
class FirstPasswordChangeController extends Controller
{
    /**
     * Tampilkan form ganti password wajib.
     */
    public function show(): View
    {
        return view('auth.first-password-change');
    }

    /**
     * Proses ganti password.
     * Validasi:
     * - Min 8 karakter, 1 huruf besar, 1 huruf kecil, 1 angka
     * - Tidak sama dengan NIM
     * - Tidak sama dengan tanggal lahir (DDMMYYYY)
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\Anggota $anggota */
        $anggota = Auth::user();

        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
                // Tidak boleh sama dengan NIM
                function ($attribute, $value, $fail) use ($anggota) {
                    if ($value === $anggota->nim) {
                        $fail('Password tidak boleh sama dengan NIM Anda.');
                    }
                },
                // Tidak boleh sama dengan tanggal lahir (DDMMYYYY)
                function ($attribute, $value, $fail) use ($anggota) {
                    $tglLahir = $anggota->tanggal_lahir->format('dmY');
                    if ($value === $tglLahir) {
                        $fail('Password tidak boleh sama dengan tanggal lahir (DDMMYYYY).');
                    }
                },
            ],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $anggota->update([
            'password'       => Hash::make($request->input('password')),
            'is_first_login' => false,
        ]);

        // Hapus session skip jika ada
        session()->forget('password_change_postponed');

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diubah. Selamat datang di SIM UKM Jurnalistik!');
    }

    /**
     * Lewati ganti password — user memilih menggunakan password default.
     * Password tetap bisa diganti kapan saja melalui menu Profil.
     */
    public function skip(): RedirectResponse
    {
        // Simpan flag di session agar tidak di-redirect lagi pada sesi ini
        session(['password_change_postponed' => true]);

        return redirect()->route('dashboard')
            ->with('info', 'Anda dapat mengganti password kapan saja melalui menu Profil.');
    }
}
