<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Admin Tools Controller
 * Menyediakan fitur Super Admin: impersonate role untuk testing.
 *
 * SECURITY:
 * - Hanya aktif saat APP_DEBUG=true
 * - Hanya bisa diakses oleh user dengan role super_admin
 * - Session-based impersonation (tidak mengubah database)
 * - Semua aksi di-log ke activity_log via spatie/activitylog
 * - Impersonation session expires setelah 2 jam
 */
class AdminToolsController extends Controller
{
    /**
     * Maximum impersonation duration in minutes.
     */
    private const MAX_IMPERSONATION_MINUTES = 120;

    /**
     * Tampilkan daftar role yang bisa di-impersonate.
     */
    public function impersonateRoles(): View
    {
        $this->authorizeAdminTools();

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
        $this->authorizeAdminTools();

        // Validasi role ada di database
        $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
        if (!$role) {
            return back()->with('error', "Role '{$roleName}' tidak ditemukan.");
        }

        /** @var Anggota $user */
        $user = Auth::user();

        // Simpan role yang sedang di-impersonate + timestamp di session
        session([
            'impersonating_role' => $roleName,
            'impersonation_started_at' => now()->timestamp,
        ]);

        // Audit log: impersonation started
        Log::channel('daily')->info('IMPERSONATE START', [
            'admin_id' => $user->id,
            'admin_nim' => $user->nim,
            'admin_role' => 'super_admin',
            'impersonating_role' => $roleName,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        activity()
            ->causedBy($user)
            ->withProperties([
                'action' => 'impersonate_start',
                'impersonating_role' => $roleName,
                'ip' => $request->ip(),
            ])
            ->log("Super Admin memulai impersonate sebagai: {$roleName}");

        return redirect()->route('dashboard')
            ->with('success', "Berhasil impersonate sebagai: {$roleName}. Permission bypass dinonaktifkan — Anda melihat sistem persis seperti role tersebut.");
    }

    /**
     * Berhenti impersonate, kembali ke super_admin penuh.
     */
    public function stopImpersonate(Request $request): RedirectResponse
    {
        $this->authorizeAdminTools();

        $previousRole = session('impersonating_role');

        // Bersihkan session impersonation
        session()->forget(['impersonating_role', 'impersonation_started_at']);

        /** @var Anggota $user */
        $user = Auth::user();

        // Audit log: impersonation stopped
        if ($previousRole) {
            Log::channel('daily')->info('IMPERSONATE STOP', [
                'admin_id' => $user->id,
                'admin_nim' => $user->nim,
                'stopped_impersonating' => $previousRole,
                'ip' => $request->ip(),
            ]);

            activity()
                ->causedBy($user)
                ->withProperties([
                    'action' => 'impersonate_stop',
                    'stopped_role' => $previousRole,
                ])
                ->log("Super Admin menghentikan impersonate (sebelumnya: {$previousRole})");
        }

        return redirect()->route('dashboard')
            ->with('success', 'Kembali ke mode Super Admin penuh.');
    }

    /**
     * Authorize access to admin tools.
     * Requires: super_admin role + APP_DEBUG=true.
     */
    private function authorizeAdminTools(): void
    {
        // Hard gate: debug mode must be enabled
        if (!config('app.debug')) {
            abort(403, 'Admin tools hanya tersedia saat APP_DEBUG=true.');
        }

        $user = Auth::user();
        if (!$user instanceof Anggota || !$user->hasRole('super_admin')) {
            abort(403, 'Hanya Super Admin yang dapat mengakses fitur ini.');
        }
    }
}
