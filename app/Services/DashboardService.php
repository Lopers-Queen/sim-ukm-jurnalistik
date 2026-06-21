<?php

namespace App\Services;

use App\Enums\Jabatan;
use App\Models\Anggota;
use App\Models\AnggaranEvent;
use App\Models\AnggaranUkmDivisi;
use App\Models\Event;
use App\Models\JadwalShift;
use App\Models\NaskahRedaksi;
use App\Models\Notulensi;
use App\Models\SuratPernyataan;
use Spatie\Activitylog\Models\Activity;

/**
 * Service: Dashboard data preparation.
 * Each method returns a compact-ready array of data for a specific role dashboard.
 */
class DashboardService
{
    /**
     * Data for Ketua Umum / Wakil Ketua Umum dashboard.
     */
    public function getKetuaData(): array
    {
        $baseQuery = Anggota::nonAdmin();
        $totalAnggota = (clone $baseQuery)->count();
        $anggotaAktif = (clone $baseQuery)->aktif()->count();
        $anggotaPasif = (clone $baseQuery)->where('status_keanggotaan', 'pasif')->count();
        $anggotaAlumni = (clone $baseQuery)->where('status_keanggotaan', 'alumni')->count();
        $eventAktif = Event::aktif()->count();

        $suratPending = SuratPernyataan::where('status', 'menunggu_konfirmasi')->count();

        // Chart: distribution per divisi
        $divisiLabels = Jabatan::DIVISI_CHART_LABELS;
        $divisiData = [];
        foreach (Jabatan::DIVISI_CHART_KEYS as $divisi) {
            $divisiData[] = Anggota::nonAdmin()->divisi($divisi)->aktif()->count();
        }
        $statusData = [$anggotaAktif, $anggotaPasif, $anggotaAlumni];

        $eventMendatang = Event::mendatang()->orderBy('tanggal_mulai')->take(5)->get();
        $recentActivities = Activity::with('causer')->latest()->take(10)->get();

        return compact(
            'totalAnggota', 'anggotaAktif', 'anggotaPasif', 'eventAktif',
            'suratPending', 'eventMendatang', 'recentActivities',
            'divisiData', 'divisiLabels', 'statusData',
        );
    }

    /**
     * Data for Sekretaris dashboard.
     */
    public function getSekretarisData(): array
    {
        $totalNotulensi = Notulensi::count();
        $notulensiTerbaru = Notulensi::with('pencatat')->latest('tanggal_rapat')->take(5)->get();
        $eventMendatang = Event::mendatang()->orderBy('tanggal_mulai')->take(5)->get();
        $totalAnggota = Anggota::nonAdmin()->count();
        $anggotaAktif = Anggota::nonAdmin()->aktif()->count();
        $suratPending = SuratPernyataan::where('status', 'menunggu_konfirmasi')->count();

        return compact(
            'totalNotulensi', 'notulensiTerbaru', 'eventMendatang',
            'totalAnggota', 'anggotaAktif', 'suratPending',
        );
    }

    /**
     * Data for Bendahara dashboard.
     */
    public function getBendaharaData(): array
    {
        $anggaranDivisi = AnggaranUkmDivisi::selectRaw('SUM(jumlah_anggaran) as total_anggaran, SUM(jumlah_terpakai) as total_terpakai')->first();
        $anggaranEvent = AnggaranEvent::selectRaw('SUM(jumlah_dianggarkan) as total_dianggarkan, SUM(jumlah_realisasi) as total_realisasi')->first();

        $totalAnggaran = ($anggaranDivisi->total_anggaran ?? 0) + ($anggaranEvent->total_dianggarkan ?? 0);
        $totalTerpakai = ($anggaranDivisi->total_terpakai ?? 0) + ($anggaranEvent->total_realisasi ?? 0);
        $sisaAnggaran = $totalAnggaran - $totalTerpakai;

        // Per-divisi chart data
        $divisiLabels = ['Fotografi', 'Pers & Penyiaran', 'Videografi', 'Kominfo', 'Redaksi', 'Inventory', 'BPI'];
        $divisiKeys = ['fotografi', 'pers_penyiaran', 'videografi', 'kominfo', 'redaksi', 'inventory', 'bpi'];
        $anggaranPerDivisi = [];
        $terpakaiPerDivisi = [];
        foreach ($divisiKeys as $divisi) {
            $data = AnggaranUkmDivisi::where('divisi', $divisi)
                ->selectRaw('COALESCE(SUM(jumlah_anggaran),0) as anggaran, COALESCE(SUM(jumlah_terpakai),0) as terpakai')
                ->first();
            $anggaranPerDivisi[] = $data->anggaran;
            $terpakaiPerDivisi[] = $data->terpakai;
        }

        $eventAktif = Event::aktif()->count();

        return compact(
            'totalAnggaran', 'totalTerpakai', 'sisaAnggaran', 'eventAktif',
            'divisiLabels', 'anggaranPerDivisi', 'terpakaiPerDivisi',
        );
    }

    /**
     * Data for Kadiv dashboard.
     */
    public function getKadivData(string $divisi): array
    {
        $anggotaDivisi = Anggota::nonAdmin()->divisi($divisi)->aktif()->count();
        $anggaranDivisi = AnggaranUkmDivisi::where('divisi', $divisi)
            ->selectRaw('COALESCE(SUM(jumlah_anggaran),0) as total, COALESCE(SUM(jumlah_terpakai),0) as terpakai')
            ->first();
        $eventMendatang = Event::mendatang()->orderBy('tanggal_mulai')->take(5)->get();

        return compact('divisi', 'anggotaDivisi', 'anggaranDivisi', 'eventMendatang');
    }

    /**
     * Data for Kanit dashboard.
     */
    public function getKanitData(string $jabatanStruktural, string $divisi): array
    {
        $unitMap = [
            Jabatan::KANIT_KOMINFO   => 'kominfo',
            Jabatan::KANIT_REDAKSI   => 'redaksi',
            Jabatan::KANIT_INVENTORY => 'inventory',
        ];
        $unit = $unitMap[$jabatanStruktural] ?? $divisi;

        $stafUnit = Anggota::nonAdmin()->divisi($unit)->aktif()->count();
        $eventMendatang = Event::mendatang()->orderBy('tanggal_mulai')->take(3)->get();

        // Naskah data only for Kanit Redaksi
        $naskahData = null;
        if ($jabatanStruktural === Jabatan::KANIT_REDAKSI) {
            $naskahData = [
                'draft'  => NaskahRedaksi::where('status', 'draft')->count(),
                'review' => NaskahRedaksi::where('status', 'review')->count(),
                'total'  => NaskahRedaksi::count(),
            ];
        }

        // Jadwal piket for this unit's members
        $jadwalPiket = JadwalShift::with('anggota')
            ->whereHas('anggota', fn ($q) => $q->where('divisi', $unit))
            ->orderBy('hari')
            ->get()
            ->groupBy('hari');

        return compact('unit', 'stafUnit', 'eventMendatang', 'naskahData', 'jadwalPiket');
    }

    /**
     * Data for Staf dashboard.
     */
    public function getStafData(int $userId): array
    {
        $eventMendatang = Event::mendatang()->orderBy('tanggal_mulai')->take(3)->get();
        $naskahSaya = NaskahRedaksi::where('penulis_id', $userId)->latest()->take(5)->get();

        // Jadwal piket for this user
        $jadwalPiket = JadwalShift::where('anggota_id', $userId)
            ->orderBy('hari')
            ->get();

        return compact('eventMendatang', 'naskahSaya', 'jadwalPiket');
    }

    /**
     * Data for Anggota Aktif dashboard.
     */
    public function getAnggotaAktifData(): array
    {
        $eventMendatang = Event::mendatang()->orderBy('tanggal_mulai')->take(5)->get();

        return compact('eventMendatang');
    }

    /**
     * Data for Anggota Pasif dashboard.
     */
    public function getAnggotaPasifData(int $userId): array
    {
        $suratSaya = SuratPernyataan::where('anggota_id', $userId)->latest()->take(3)->get();

        return compact('suratSaya');
    }
}
