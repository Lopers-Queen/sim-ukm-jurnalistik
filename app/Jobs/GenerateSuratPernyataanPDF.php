<?php

namespace App\Jobs;

use App\Models\SuratPernyataan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

/**
 * Job: Generate Surat Pernyataan PDF via DomPDF (FR-21)
 */
class GenerateSuratPernyataanPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public SuratPernyataan $suratPernyataan,
    ) {}

    public function handle(): void
    {
        $surat = $this->suratPernyataan->load(['anggota', 'event', 'anggotaPanitia']);

        $pdf = Pdf::loadView('pdf.surat-pernyataan', [
            'surat'   => $surat,
            'anggota' => $surat->anggota,
            'event'   => $surat->event,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'surat-pernyataan/SP-' . $surat->id . '-' . time() . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        $surat->update([
            'file_pdf' => $filename,
        ]);
    }
}
