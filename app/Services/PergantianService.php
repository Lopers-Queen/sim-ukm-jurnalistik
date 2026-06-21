<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\LogOverride;
use App\Models\PeriodeKepengurusan;
use App\Models\RiwayatKepengurusan;
use Illuminate\Support\Facades\DB;

/**
 * Service: Pergantian Kepengurusan business logic.
 * Handles the complex transaction of finalizing a new organizational period.
 */
class PergantianService
{
    protected EligibilityValidator $validator;

    public function __construct(EligibilityValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate eligibility for all positions and return errors (empty if all pass).
     *
     * @param  array  $jabatanMap   [jabatan_key => anggota_id]
     * @param  array  $overrideReasons  [jabatan_key => reason_string]
     * @return string|null  Error message or null if all valid.
     */
    public function validateAllPositions(array $jabatanMap, array $overrideReasons): ?string
    {
        foreach ($jabatanMap as $jabatan => $anggotaId) {
            $anggota = Anggota::find($anggotaId);
            $validation = $this->validator->validate($anggota, $jabatan);

            if (! $validation['eligible'] && empty($overrideReasons[$jabatan])) {
                return "Anggota {$anggota->nama_lengkap} tidak memenuhi syarat untuk jabatan " .
                    ($anggota->getJabatanLengkapFromKey($jabatan) ?? $jabatan) .
                    '. Berikan alasan override atau pilih anggota lain.';
            }
        }

        return null;
    }

    /**
     * Finalize the kepengurusan transition in a single transaction.
     *
     * @param  array  $requestData  The validated request data.
     * @param  array  $jabatanMap   [jabatan_key => anggota_id]
     * @param  array  $overrideReasons  [jabatan_key => reason_string]
     */
    public function finalizeTransition(array $requestData, array $jabatanMap, array $overrideReasons): void
    {
        DB::transaction(function () use ($requestData, $jabatanMap, $overrideReasons) {
            // 1. Archive current active period
            PeriodeKepengurusan::where('status', 'aktif')->update(['status' => 'selesai']);

            // 2. Create new period
            $periodeBaru = PeriodeKepengurusan::create([
                'nama_periode'    => $requestData['nama_periode'],
                'tahun_mulai'     => date('Y', strtotime($requestData['tanggal_mulai'])),
                'tahun_selesai'   => date('Y', strtotime($requestData['tanggal_selesai'])),
                'tanggal_mulai'   => $requestData['tanggal_mulai'],
                'tanggal_selesai' => $requestData['tanggal_selesai'],
                'deskripsi'       => $requestData['deskripsi'] ?? null,
                'is_active'       => true,
                'status'          => 'aktif',
            ]);

            // 3. Assign positions and record history
            foreach ($jabatanMap as $jabatan => $anggotaId) {
                $anggota = Anggota::find($anggotaId);

                $anggota->update(['jabatan_struktural' => $jabatan]);
                $anggota->syncRoles([$jabatan]);

                RiwayatKepengurusan::create([
                    'anggota_id'    => $anggota->id,
                    'periode_id'    => $periodeBaru->id,
                    'jabatan'       => $jabatan,
                    'divisi'        => $anggota->divisi,
                    'tanggal_mulai' => $requestData['tanggal_mulai'],
                    'tanggal_selesai' => $requestData['tanggal_selesai'],
                    'status'        => 'aktif',
                ]);

                // Log override if member was ineligible but override reason was provided
                $validation = $this->validator->validate($anggota, $jabatan);
                if (! $validation['eligible'] && ! empty($overrideReasons[$jabatan])) {
                    LogOverride::create([
                        'pelaku_id'             => auth()->id(),
                        'anggota_id'            => $anggota->id,
                        'jabatan_diberikan'     => $jabatan,
                        'syarat_tidak_terpenuhi' => $validation['reason'],
                        'alasan_override'       => $overrideReasons[$jabatan],
                    ]);
                }
            }

            // 4. Reset jabatan for members not in new BPI
            $activeIds = array_values($jabatanMap);
            Anggota::whereNotIn('id', $activeIds)
                ->whereNotIn('jabatan_struktural', ['anggota', 'staf', 'admin'])
                ->update(['jabatan_struktural' => 'anggota']);
        });
    }
}
