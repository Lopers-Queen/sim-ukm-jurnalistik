<?php

namespace App\Services;

use App\Enums\Jabatan;
use App\Models\Anggota;

/**
 * Service Class: Validasi Eligibility Jabatan (FR-15)
 *
 * Aturan sesuai SRS:
 * - Staf: min 1 tahun jadi anggota divisi
 * - Kadiv: min 1 tahun di UKM
 * - Kanit: min 1 tahun jadi staf unit (= 2 tahun di UKM)
 * - Wakil/Sekum/Bendum: min 1 tahun di UKM
 * - Ketum: min 2 tahun di UKM
 */
class EligibilityValidator
{
    /**
     * Common eligibility check based on minimum years in UKM.
     */
    private function checkEligibility(Anggota $anggota, int $minYears, string $label): array
    {
        $yearsInUkm = $anggota->masaKeanggotaan();
        $eligible = $yearsInUkm >= $minYears;

        return [
            'eligible'     => $eligible,
            'reason'       => $eligible
                ? "Memenuhi syarat: {$yearsInUkm} tahun di UKM (min. {$minYears} tahun)."
                : "Belum memenuhi syarat: baru {$yearsInUkm} tahun di UKM (min. {$minYears} tahun{$label}).",
            'years_in_ukm' => $yearsInUkm,
            'current_role' => $anggota->jabatan_lengkap,
        ];
    }

    /**
     * Cek apakah anggota bisa menjadi Staf.
     * Syarat: min 1 tahun jadi anggota divisi.
     */
    public function canBeStaf(Anggota $anggota): array
    {
        return $this->checkEligibility($anggota, 1, ' sebagai anggota divisi');
    }

    /**
     * Cek apakah anggota bisa menjadi Kepala Divisi.
     * Syarat: min 1 tahun di UKM.
     */
    public function canBeKadiv(Anggota $anggota): array
    {
        return $this->checkEligibility($anggota, 1, '');
    }

    /**
     * Cek apakah anggota bisa menjadi Kepala Unit.
     * Syarat: min 1 tahun jadi staf unit (= 2 tahun di UKM).
     */
    public function canBeKanit(Anggota $anggota): array
    {
        $yearsInUkm = $anggota->masaKeanggotaan();

        // Cek apakah pernah menjadi staf
        $pernahJadiStaf = $anggota->riwayatKepengurusan()
            ->where('jabatan', 'staf')
            ->exists();

        $eligible = $yearsInUkm >= 2 && $pernahJadiStaf;

        $reason = match (true) {
            ! $pernahJadiStaf => 'Belum memenuhi syarat: belum pernah menjadi staf unit.',
            $yearsInUkm < 2  => "Belum memenuhi syarat: baru {$yearsInUkm} tahun di UKM (min. 2 tahun).",
            default          => "Memenuhi syarat: {$yearsInUkm} tahun di UKM dan pernah menjadi staf.",
        };

        return [
            'eligible'     => $eligible,
            'reason'       => $reason,
            'years_in_ukm' => $yearsInUkm,
            'current_role' => $anggota->jabatan_lengkap,
        ];
    }

    /**
     * Cek apakah anggota bisa menjadi Wakil Ketum / Sekum / Bendum.
     * Syarat: min 1 tahun di UKM.
     */
    public function canBeWakilSekumBendum(Anggota $anggota): array
    {
        return $this->checkEligibility($anggota, 1, '');
    }

    /**
     * Cek apakah anggota bisa menjadi Ketua Umum.
     * Syarat: min 2 tahun di UKM.
     */
    public function canBeKetum(Anggota $anggota): array
    {
        return $this->checkEligibility($anggota, 2, '');
    }

    /**
     * Validasi eligibility berdasarkan jabatan target.
     */
    public function validate(Anggota $anggota, string $jabatanTarget): array
    {
        return match ($jabatanTarget) {
            Jabatan::KETUA_UMUM                         => $this->canBeKetum($anggota),
            Jabatan::WAKIL_KETUA_UMUM,
            Jabatan::SEKRETARIS_UMUM_1, Jabatan::SEKRETARIS_UMUM_2,
            Jabatan::BENDAHARA_UMUM_1, Jabatan::BENDAHARA_UMUM_2 => $this->canBeWakilSekumBendum($anggota),
            Jabatan::KADIV_FOTOGRAFI,
            Jabatan::KADIV_PERS_PENYIARAN,
            Jabatan::KADIV_VIDEOGRAFI                   => $this->canBeKadiv($anggota),
            Jabatan::KANIT_KOMINFO,
            Jabatan::KANIT_REDAKSI,
            Jabatan::KANIT_INVENTORY                    => $this->canBeKanit($anggota),
            Jabatan::STAF                               => $this->canBeStaf($anggota),
            default                                     => [
                'eligible'     => true,
                'reason'       => 'Tidak ada syarat khusus untuk jabatan ini.',
                'years_in_ukm' => $anggota->masaKeanggotaan(),
                'current_role' => $anggota->jabatan_lengkap,
            ],
        };
    }
}
