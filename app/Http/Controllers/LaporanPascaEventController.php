<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\LaporanPascaEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller Laporan Pasca Event (FR-23)
 * Notulensi, dokumentasi, evaluasi, dan laporan keuangan event.
 */
class LaporanPascaEventController extends Controller
{
    public function create(Event $event): View
    {
        $this->authorize('laporan-event.create');

        $event->load(['divisiPanitia.anggotaPanitia.anggota', 'anggaranEvent']);

        // Hitung otomatis dari FR-22
        $totalPemasukan  = $event->anggaranEvent->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $event->anggaranEvent->where('jenis', 'pengeluaran')->sum('nominal');
        $sisaAnggaran    = $totalPemasukan - $totalPengeluaran;

        return view('laporan-event.create', compact('event', 'totalPemasukan', 'totalPengeluaran', 'sisaAnggaran'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('laporan-event.create');

        $data = $request->validate([
            'notulensi'        => 'required|string',
            'daftar_peserta'   => 'nullable|string',
            'kendala'          => 'required|string',
            'pencapaian'       => 'required|string',
            'saran_perbaikan'  => 'required|string',
            'rating_internal'  => 'required|numeric|min:1|max:5',
            'dokumentasi_video_link' => 'nullable|url',
            'dokumentasi_foto' => 'nullable|array',
            'dokumentasi_foto.*' => 'image|mimes:jpg,jpeg,png|max:5120',
            'materi'           => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        // Hitung otomatis keuangan dari anggaran event
        $totalPemasukan   = $event->anggaranEvent->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $event->anggaranEvent->where('jenis', 'pengeluaran')->sum('nominal');

        // Upload file materi
        $materiPath = null;
        if ($request->hasFile('materi')) {
            $materiPath = $request->file('materi')->store('laporan-event/materi', 'public');
        }

        // Upload foto dokumentasi
        $fotoPaths = [];
        if ($request->hasFile('dokumentasi_foto')) {
            foreach ($request->file('dokumentasi_foto') as $foto) {
                $fotoPaths[] = $foto->store('laporan-event/foto', 'public');
            }
        }

        $laporan = LaporanPascaEvent::create([
            'event_id'               => $event->id,
            'notulensi'              => $data['notulensi'],
            'daftar_peserta'         => $data['daftar_peserta'],
            'kendala'                => $data['kendala'],
            'pencapaian'             => $data['pencapaian'],
            'saran_perbaikan'        => $data['saran_perbaikan'],
            'rating_internal'        => $data['rating_internal'],
            'total_pemasukan'        => $totalPemasukan,
            'total_pengeluaran'      => $totalPengeluaran,
            'sisa_anggaran'          => $totalPemasukan - $totalPengeluaran,
            'dokumentasi_foto'       => json_encode($fotoPaths),
            'dokumentasi_video_link' => $data['dokumentasi_video_link'] ?? null,
            'materi'                 => $materiPath,
            'pelapor_id'             => auth()->id(),
            'status'                 => 'draft',
        ]);

        return redirect()->route('event.show', $event)
            ->with('success', 'Laporan pasca event berhasil dibuat.');
    }

    public function show(Event $event, LaporanPascaEvent $laporan): View
    {
        $this->authorize('laporan-event.view');

        $laporan->load(['pelapor']);
        $event->load(['divisiPanitia.anggotaPanitia.anggota', 'anggaranEvent']);

        return view('laporan-event.show', compact('event', 'laporan'));
    }

    /**
     * Finalisasi laporan (ubah status dari draft ke final).
     */
    public function finalize(Event $event, LaporanPascaEvent $laporan): RedirectResponse
    {
        $this->authorize('laporan-event.edit');

        $laporan->update(['status' => 'final']);

        return redirect()->route('event.show', $event)
            ->with('success', 'Laporan pasca event telah difinalisasi.');
    }

    /**
     * Export laporan ke PDF (DomPDF via Queue).
     */
    public function exportPdf(Event $event, LaporanPascaEvent $laporan)
    {
        $this->authorize('laporan-event.view');

        $event->load(['divisiPanitia.anggotaPanitia.anggota', 'anggaranEvent', 'pic']);

        $pdf = Pdf::loadView('pdf.laporan-pasca-event', compact('event', 'laporan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Laporan-{$event->nama_event}.pdf");
    }
}
