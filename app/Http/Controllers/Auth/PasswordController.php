<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Controller: Profile Password Update (FR-01)
 * Allows authenticated users to change their password from the Profile page.
 */
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\Anggota $anggota */
        $anggota = Auth::user();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => [
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
                    if ($anggota->tanggal_lahir) {
                        $tglLahir = $anggota->tanggal_lahir->format('dmY');
                        if ($value === $tglLahir) {
                            $fail('Password tidak boleh sama dengan tanggal lahir (DDMMYYYY).');
                        }
                    }
                },
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'password.min'              => 'Password minimal 8 karakter.',
        ]);

        $anggota->update([
            'password'       => Hash::make($validated['password']),
            'is_first_login' => false,
        ]);

        // Log the password change
        activity()
            ->causedBy($anggota)
            ->withProperties(['action' => 'password_change'])
            ->log('Anggota mengubah password dari Profil');

        return redirect()->route('profile.edit')
            ->with('status', 'password-updated')
            ->with('success', 'Password berhasil diubah.');
    }
}
