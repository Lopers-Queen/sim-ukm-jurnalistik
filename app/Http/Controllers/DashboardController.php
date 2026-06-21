<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

/**
 * Controller Dashboard (FR-11)
 * Menampilkan dashboard berbeda berdasarkan role pengguna.
 */
class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        $user = auth()->user();

        // Routing berdasarkan role
        if ($user->hasAnyRole(['ketua_umum', 'wakil_ketua_umum'])) {
            return view('dashboard.ketua', $this->dashboardService->getKetuaData());
        }

        if ($user->hasAnyRole(['sekretaris_umum_1', 'sekretaris_umum_2'])) {
            return view('dashboard.sekretaris', $this->dashboardService->getSekretarisData());
        }

        if ($user->hasAnyRole(['bendahara_umum_1', 'bendahara_umum_2'])) {
            return view('dashboard.bendahara', $this->dashboardService->getBendaharaData());
        }

        if ($user->hasAnyRole(['kadiv_fotografi', 'kadiv_pers_penyiaran', 'kadiv_videografi'])) {
            return view('dashboard.kadiv', $this->dashboardService->getKadivData($user->divisi));
        }

        if ($user->hasAnyRole(['kanit_kominfo', 'kanit_redaksi', 'kanit_inventory'])) {
            return view('dashboard.kanit', $this->dashboardService->getKanitData($user->jabatan_struktural, $user->divisi));
        }

        if ($user->hasRole('staf')) {
            return view('dashboard.staf', $this->dashboardService->getStafData($user->id));
        }

        if ($user->hasRole('anggota_pasif')) {
            return view('dashboard.anggota-pasif', array_merge(
                ['user' => $user],
                $this->dashboardService->getAnggotaPasifData($user->id)
            ));
        }

        // Default: anggota_aktif, ketua_panitia, dll
        return view('dashboard.anggota-aktif', array_merge(
            ['user' => $user],
            $this->dashboardService->getAnggotaAktifData()
        ));
    }
}
