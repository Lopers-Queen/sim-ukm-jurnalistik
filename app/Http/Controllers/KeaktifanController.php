<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\PeriodeKepengurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller Manajemen Keaktifan (FR-13)
 * Form perpanjangan keaktifan anggota per periode.
 */
class KeaktifanController extends Controller
{
    /**
     * Daftar status keaktifan anggota.
     */
    public function index(Request $request): View
    {
        $this->authorize('organisasi.view');

        $query = Anggota::nonAdmin()
            ->search($request->input('search'))
            ->when($request->input('status'), fn ($q, $status) => $q->where('status_keanggotaan', $status))
            ->orderBy('nama_lengkap');

        $anggota = $query->paginate(20)->withQueryString();
        $periodeAktif = PeriodeKepengurusan::where('status', 'aktif')->first();

        return view('keaktifan.index', compact('anggota', 'periodeAktif'));
    }

    /**
     * Form perpanjangan keaktifan anggota (self-service).
     */
    public function perpanjangan(): View
    {
        $user = auth()->user();
        $periodeAktif = PeriodeKepengurusan::where('status', 'aktif')->first();

        return view('keaktifan.perpanjangan', compact('user', 'periodeAktif'));
    }

    /**
     * Submit perpanjangan keaktifan.
     */
    public function submitPerpanjangan(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'status_keanggotaan' => 'aktif',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Perpanjangan keaktifan berhasil disubmit.');
    }

    /**
     * Admin: Toggle status keaktifan anggota.
     */
    public function toggleStatus(Request $request, Anggota $anggota): RedirectResponse
    {
        $this->authorize('organisasi.edit');

        $request->validate([
            'status' => 'required|in:aktif,pasif,alumni',
        ]);

        $anggota->update([
            'status_keanggotaan' => $request->status,
        ]);

        $statusLabel = ucfirst($request->status);

        return redirect()->back()
            ->with('success', "Status {$anggota->nama_lengkap} berhasil diubah menjadi {$statusLabel}.");
    }

    /**
     * Admin: Batch update status keaktifan.
     */
    public function batchUpdate(Request $request): RedirectResponse
    {
        $this->authorize('organisasi.edit');

        $request->validate([
            'anggota_ids' => 'required|array',
            'anggota_ids.*' => 'exists:anggota,id',
            'status'      => 'required|in:aktif,pasif,alumni',
        ]);

        Anggota::whereIn('id', $request->anggota_ids)
            ->update(['status_keanggotaan' => $request->status]);

        $count = count($request->anggota_ids);

        return redirect()->back()
            ->with('success', "{$count} anggota berhasil diperbarui statusnya.");
    }
}
