<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Controller Profil Anggota (FR-03)
 */
class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Login history untuk tab Riwayat Login
        $loginHistory = \App\Models\LoginHistory::where('anggota_id', $user->id)
            ->orderByDesc('attempted_at')
            ->limit(20)
            ->get();

        return view('profile.edit', [
            'anggota'      => $user,
            'loginHistory' => $loginHistory,
        ]);
    }

    /**
     * Update data profil anggota.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Hapus akun anggota (soft delete).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Upload foto profil.
     */
    public function updateFoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $request->user()->uploadFotoProfil($request->file('foto_profil'));

        activity()->causedBy($request->user())->log('Update foto profil');

        return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Hapus foto profil.
     */
    public function deleteFoto(Request $request): RedirectResponse
    {
        $request->user()->deleteFotoProfil();

        activity()->causedBy($request->user())->log('Hapus foto profil');

        return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }
}
