<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateSuratPernyataanPDF;
use App\Models\SuratPernyataan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller Surat Pernyataan (FR-21)
 * Workflow: Pending TTD → Menunggu Konfirmasi → Disetujui/Ditolak
 */
class SuratPernyataanController extends Controller
{
    public function index(): View
    {
        $this->authorize('surat-pernyataan.view');

        $suratList = SuratPernyataan::with(['anggota', 'event', 'approver'])
            ->latest()
            ->paginate(15);

        return view('surat-pernyataan.index', compact('suratList'));
    }

    public function show(SuratPernyataan $suratPernyataan): View
    {
        $this->authorize('surat-pernyataan.view');

        $suratPernyataan->load(['anggota', 'event', 'anggotaPanitia', 'approver']);

        return view('surat-pernyataan.show', compact('suratPernyataan'));
    }

    /**
     * Generate PDF dan buat record surat pernyataan.
     */
    public function generate(Request $request): RedirectResponse
    {
        $this->authorize('surat-pernyataan.create');

        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'event_id'   => 'required|exists:event,id',
        ]);

        $surat = SuratPernyataan::create([
            'anggota_id' => $request->anggota_id,
            'event_id'   => $request->event_id,
            'anggota_panitia_id' => $request->anggota_panitia_id,
            'status'     => 'pending_ttd',
        ]);

        // Generate PDF via queue
        GenerateSuratPernyataanPDF::dispatch($surat);

        return redirect()->back()
            ->with('success', 'Surat pernyataan sedang digenerate.');
    }

    /**
     * Upload tanda tangan (scan/digital).
     */
    public function uploadTtd(Request $request, SuratPernyataan $suratPernyataan): RedirectResponse
    {
        $this->authorize('surat-pernyataan.upload-ttd');

        $request->validate([
            'file_ttd' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('file_ttd')->store('ttd-surat', 'public');

        $suratPernyataan->update([
            'file_ttd' => $path,
            'status'   => 'menunggu_konfirmasi',
        ]);

        return redirect()->back()
            ->with('success', 'Tanda tangan berhasil diupload.');
    }

    /**
     * Approve surat pernyataan (oleh Ketum/Waketum).
     */
    public function approve(SuratPernyataan $suratPernyataan): RedirectResponse
    {
        $this->authorize('surat-pernyataan.approve');

        $suratPernyataan->update([
            'status'            => 'disetujui',
            'approver_id'       => Auth::id(),
            'tanggal_approval'  => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Surat pernyataan disetujui.');
    }

    /**
     * Reject surat pernyataan (oleh Ketum/Waketum).
     */
    public function reject(Request $request, SuratPernyataan $suratPernyataan): RedirectResponse
    {
        $this->authorize('surat-pernyataan.reject');

        $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        $suratPernyataan->update([
            'status'            => 'ditolak',
            'alasan_penolakan'  => $request->alasan_penolakan,
            'approver_id'       => Auth::id(),
            'tanggal_approval'  => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Surat pernyataan ditolak.');
    }

    /**
     * Download PDF surat pernyataan.
     */
    public function download(SuratPernyataan $suratPernyataan)
    {
        $this->authorize('surat-pernyataan.view');

        if (! $suratPernyataan->file_pdf || ! Storage::disk('public')->exists($suratPernyataan->file_pdf)) {
            return redirect()->back()->with('error', 'File PDF belum tersedia.');
        }

        return Storage::disk('public')->download($suratPernyataan->file_pdf, 'Surat-Pernyataan-' . $suratPernyataan->id . '.pdf');
    }
}
