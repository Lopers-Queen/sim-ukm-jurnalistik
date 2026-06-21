<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Admin Tools Controller
 * Menyediakan fitur Super Admin: impersonate role untuk testing.
 * Hanya aktif saat APP_DEBUG=true.
 */
class AdminToolsController extends Controller
{
    /**
     * Tampilkan daftar role yang bisa di-impersonate.
     */
    public function impersonateRoles(): View|RedirectResponse
    {
        // Gate: hanya super_admin + debug mode
        $user = Auth::user();
        if (!$user instanceof Anggota || !$user->hasRole('super_admin') || !config('app.debug')) {
            abort(403, 'Fitur ini hanya tersedia untuk Super Admin dalam mode debug.');
        }

        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->map(function ($role) {
                $role->permission_count = $role->permissions()->count();
                return $role;
            });

        return view('admin.impersonate-roles', compact('roles'));
    }

    /**
     * Mulai impersonate role tertentu.
     * Session-based: tidak mengubah role di database.
     */
    public function startImpersonate(Request $request, string $roleName): RedirectResponse
    {
        $user = Auth::user();
        if (!$user instanceof Anggota || !$user->hasRole('super_admin') || !config('app.debug')) {
            abort(403, 'Fitur ini hanya tersedia untuk Super Admin dalam mode debug.');
        }

        // Validasi role ada
        $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
        if (!$role) {
            return back()->with('error', "Role '{$roleName}' tidak ditemukan.");
        }

        // Simpan role yang sedang di-impersonate di session
        session(['impersonating_role' => $roleName]);

        return redirect()->route('dashboard')
            ->with('success', "Berhasil impersonate sebagai: {$roleName}. Semua permission bypass dinonaktifkan, Anda melihat sistem persis seperti role tersebut.");
    }

    /**
     * Berhenti impersonate, kembali ke super_admin penuh.
     */
    public function stopImpersonate(): RedirectResponse
    {
        session()->forget('impersonating_role');

        return redirect()->route('dashboard')
            ->with('success', 'Kembali ke mode Super Admin penuh.');
    }
}
