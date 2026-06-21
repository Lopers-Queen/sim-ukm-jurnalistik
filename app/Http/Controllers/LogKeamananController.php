<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\LoginHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

/**
 * Controller Log Keamanan (FR-10) + Activity Log (FR-22)
 */
class LogKeamananController extends Controller
{
    public function loginHistory(Request $request): View
    {
        $this->authorize('keamanan.view-log');

        $query = LoginHistory::with('anggota');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->input('search')) {
            $query->whereHas('anggota', fn ($q) => $q->where('nim', 'like', "%{$search}%")
                ->orWhere('nama_lengkap', 'like', "%{$search}%"));
        }

        $histories = $query->latest('attempted_at')->paginate(20)->withQueryString();

        return view('keamanan.login-history', compact('histories'));
    }

    public function activityLog(Request $request): View
    {
        $this->authorize('activity-log.view');

        $query = Activity::with('causer');

        if ($search = $request->input('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        $activities = $query->latest()->paginate(20)->withQueryString();

        return view('keamanan.activity-log', compact('activities'));
    }

    /**
     * Unlock akun anggota yang terkunci.
     */
    public function unlockAccount(Request $request): RedirectResponse
    {
        $this->authorize('keamanan.manage-lockout');

        $request->validate(['anggota_id' => 'required|exists:anggota,id']);

        $anggota = Anggota::findOrFail($request->anggota_id);
        $anggota->resetFailedLogin();

        return redirect()->back()->with('success', "Akun {$anggota->nama_lengkap} berhasil di-unlock.");
    }
}
