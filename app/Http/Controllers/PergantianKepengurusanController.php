<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePergantianRequest;
use App\Models\Anggota;
use App\Models\PeriodeKepengurusan;
use App\Services\EligibilityValidator;
use App\Services\PergantianService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller Pergantian Kepengurusan (FR-17)
 * Mengelola transisi periode kepengurusan tahunan.
 * Terintegrasi dengan FR-15 (Validasi Eligibility) dan FR-18 (Override).
 */
class PergantianKepengurusanController extends Controller
{
    protected EligibilityValidator $validator;
    protected PergantianService $pergantianService;

    public function __construct(EligibilityValidator $validator, PergantianService $pergantianService)
    {
        $this->validator = $validator;
        $this->pergantianService = $pergantianService;
    }

    /**
     * Halaman utama pergantian kepengurusan.
     */
    public function index(): View
    {
        $this->authorize('pergantian.view');

        $periodeAktif = PeriodeKepengurusan::where('status', 'aktif')->first();
        $anggotaList = Anggota::nonAdmin()->aktif()->orderBy('nama_lengkap')->get();

        return view('pergantian.index', compact('periodeAktif', 'anggotaList'));
    }

    /**
     * Validasi eligibility untuk seluruh susunan yang diusulkan.
     * Dipanggil via AJAX atau form submission.
     */
    public function validateSusunan(Request $request): JsonResponse
    {
        $this->authorize('eligibility.validate');

        $request->validate([
            'susunan'   => 'required|array',
            'susunan.*' => 'required|exists:anggota,id',
        ]);

        $results = [];
        foreach ($request->susunan as $jabatan => $anggotaId) {
            $anggota = Anggota::find($anggotaId);
            if ($anggota) {
                $validation = $this->validator->validate($anggota, $jabatan);
                $results[$jabatan] = array_merge($validation, [
                    'anggota_id'   => $anggota->id,
                    'nama_lengkap' => $anggota->nama_lengkap,
                    'nim'          => $anggota->nim,
                ]);
            }
        }

        return response()->json(['results' => $results]);
    }

    /**
     * Proses finalisasi pergantian kepengurusan.
     */
    public function store(StorePergantianRequest $request): RedirectResponse
    {
        $jabatanMap = $request->jabatanMap();
        $overrideReasons = $request->override_reasons ?? [];

        // Check for duplicate assignments
        $uniqueIds = array_unique(array_values($jabatanMap));
        if (count($uniqueIds) !== count($jabatanMap)) {
            return back()->withInput()->with('error', 'Satu anggota tidak boleh menjabat di lebih dari satu posisi.');
        }

        // Validate eligibility for all positions
        $error = $this->pergantianService->validateAllPositions($jabatanMap, $overrideReasons);
        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        // Finalize the transition
        $this->pergantianService->finalizeTransition($request->validated(), $jabatanMap, $overrideReasons);

        return redirect()->route('periode.index')
            ->with('success', "Pergantian kepengurusan periode {$request->nama_periode} berhasil difinalisasi.");
    }
}
