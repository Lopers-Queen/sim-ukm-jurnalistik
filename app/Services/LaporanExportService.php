<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\AnggaranEvent;
use App\Models\AnggaranUkmDivisi;
use App\Models\Event;
use Illuminate\Support\Collection;

/**
 * Service: Laporan query and aggregation.
 * Centralizes shared filtering and aggregation logic for reports and exports.
 */
class LaporanExportService
{
    /**
     * Get filtered anggota collection for reports.
     */
    public function getFilteredAnggota(?string $divisi = null, ?string $status = null): Collection
    {
        return Anggota::nonAdmin()
            ->divisi($divisi)
            ->when($status, fn ($q) => $q->where('status_keanggotaan', $status))
            ->orderBy('nama_lengkap')
            ->get();
    }

    /**
     * Get filtered events with relations for reports.
     */
    public function getFilteredEvents(?string $status = null, ?int $periodeId = null, bool $withDivisi = false): Collection
    {
        $query = Event::with(['pic', 'periode', 'anggaranEvent']);

        if ($withDivisi) {
            $query->with('divisiPanitia');
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        return $query->orderByDesc('tanggal_mulai')->get();
    }

    /**
     * Calculate total anggaran and realisasi from a collection of events.
     *
     * @return array{totalAnggaran: float, totalRealisasi: float}
     */
    public function calculateEventTotals(Collection $events): array
    {
        return [
            'totalAnggaran'  => $events->sum('anggaran_total'),
            'totalRealisasi' => $events->sum(fn ($e) => $e->anggaranEvent->sum('jumlah_realisasi')),
        ];
    }

    /**
     * Get aggregated keuangan data for reports.
     *
     * @return array{
     *   anggaranDivisi: Collection,
     *   anggaranEvent: Collection,
     *   totalDivisiAnggaran: float,
     *   totalDivisiTerpakai: float,
     *   totalEventAnggaran: float,
     *   totalEventRealisasi: float
     * }
     */
    public function getKeuanganData(): array
    {
        $anggaranDivisi = AnggaranUkmDivisi::with('periode')->orderBy('divisi')->get();
        $anggaranEvent  = AnggaranEvent::with('event')->get();

        return [
            'anggaranDivisi'        => $anggaranDivisi,
            'anggaranEvent'         => $anggaranEvent,
            'totalDivisiAnggaran'   => $anggaranDivisi->sum('jumlah_anggaran'),
            'totalDivisiTerpakai'   => $anggaranDivisi->sum('jumlah_terpakai'),
            'totalEventAnggaran'    => $anggaranEvent->sum('jumlah_dianggarkan'),
            'totalEventRealisasi'   => $anggaranEvent->sum('jumlah_realisasi'),
        ];
    }
}
