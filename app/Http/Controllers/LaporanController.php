<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaExport;
use App\Services\LaporanExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controller Laporan & Ekspor (FR-12)
 */
class LaporanController extends Controller
{
    protected LaporanExportService $laporanService;

    public function __construct(LaporanExportService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    public function index(): View
    {
        $this->authorize('laporan.view');

        return view('laporan.index');
    }

    public function anggota(Request $request): View
    {
        $this->authorize('laporan.view');

        $anggotaList = $this->laporanService->getFilteredAnggota(
            $request->input('divisi'),
            $request->input('status')
        );

        return view('laporan.anggota', compact('anggotaList'));
    }

    public function exportAnggotaPdf(Request $request)
    {
        $this->authorize('laporan.export-pdf');

        $anggotaList = $this->laporanService->getFilteredAnggota(
            $request->input('divisi'),
            $request->input('status')
        );

        $pdf = Pdf::loadView('pdf.laporan-anggota', compact('anggotaList'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan-Anggota-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportAnggotaExcel(Request $request)
    {
        $this->authorize('laporan.export-excel');

        return Excel::download(
            new AnggotaExport($request->input('divisi'), $request->input('status')),
            'Laporan-Anggota-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ── Laporan Event ────────────────────────

    public function event(Request $request): View
    {
        $this->authorize('laporan.view');

        $events = $this->laporanService->getFilteredEvents(
            $request->input('status'),
            $request->input('periode_id') ? (int) $request->input('periode_id') : null,
            true
        );

        $totals = $this->laporanService->calculateEventTotals($events);

        return view('laporan.event', array_merge(compact('events'), $totals));
    }

    public function exportEventPdf(Request $request)
    {
        $this->authorize('laporan.export-pdf');

        $events = $this->laporanService->getFilteredEvents(
            $request->input('status'),
        );

        $totals = $this->laporanService->calculateEventTotals($events);

        $pdf = Pdf::loadView('pdf.laporan-event', array_merge(compact('events'), $totals));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan-Event-' . now()->format('Y-m-d') . '.pdf');
    }

    // ── Laporan Keuangan ─────────────────────

    public function keuangan(Request $request): View
    {
        $this->authorize('laporan.view');

        $data = $this->laporanService->getKeuanganData();

        return view('laporan.keuangan', $data);
    }

    public function exportKeuanganPdf(Request $request)
    {
        $this->authorize('laporan.export-pdf');

        $data = $this->laporanService->getKeuanganData();

        $pdf = Pdf::loadView('pdf.laporan-keuangan', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan-Keuangan-' . now()->format('Y-m-d') . '.pdf');
    }
}
