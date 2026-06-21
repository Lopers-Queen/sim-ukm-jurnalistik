<?php

namespace Tests\Unit;

use App\Enums\Jabatan;
use App\Models\Anggota;
use App\Models\RiwayatKepengurusan;
use App\Models\PeriodeKepengurusan;
use App\Services\EligibilityValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit Tests: EligibilityValidator
 * Menguji validasi eligibility untuk berbagai jabatan organisasi.
 */
class EligibilityValidatorTest extends TestCase
{
    use RefreshDatabase;

    private EligibilityValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new EligibilityValidator();
    }

    private function createPeriode(): PeriodeKepengurusan
    {
        return PeriodeKepengurusan::create([
            'nama_periode'  => 'Test Periode',
            'tahun_mulai'   => 2023,
            'tahun_selesai' => 2024,
            'tanggal_mulai' => '2023-01-01',
            'tanggal_selesai' => '2024-12-31',
            'status'        => 'aktif',
        ]);
    }

    // ====================================================================
    // canBeStaf()
    // ====================================================================

    public function test_can_be_staf_eligible_with_1_year(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(2),
        ]);

        $result = $this->validator->canBeStaf($anggota);

        $this->assertTrue($result['eligible']);
    }

    public function test_can_be_staf_ineligible_with_less_than_1_year(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subMonths(6),
        ]);

        $result = $this->validator->canBeStaf($anggota);

        $this->assertFalse($result['eligible']);
    }

    // ====================================================================
    // canBeKadiv()
    // ====================================================================

    public function test_can_be_kadiv_eligible_with_1_year(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(2),
        ]);

        $result = $this->validator->canBeKadiv($anggota);

        $this->assertTrue($result['eligible']);
    }

    public function test_can_be_kadiv_ineligible_with_less_than_1_year(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subMonths(3),
        ]);

        $result = $this->validator->canBeKadiv($anggota);

        $this->assertFalse($result['eligible']);
    }

    // ====================================================================
    // canBeKanit()
    // ====================================================================

    public function test_can_be_kanit_eligible_with_2_years_and_staf_history(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(3),
        ]);

        $periode = $this->createPeriode();
        RiwayatKepengurusan::create([
            'anggota_id'    => $anggota->id,
            'periode_id'    => $periode->id,
            'jabatan'       => 'staf',
            'divisi'        => 'kominfo',
            'tanggal_mulai' => '2023-01-01',
            'tanggal_selesai' => '2024-12-31',
            'status'        => 'selesai',
        ]);

        $result = $this->validator->canBeKanit($anggota);

        $this->assertTrue($result['eligible']);
    }

    public function test_can_be_kanit_ineligible_without_staf_history(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(5),
        ]);

        $result = $this->validator->canBeKanit($anggota);

        $this->assertFalse($result['eligible']);
        $this->assertStringContainsString('belum pernah menjadi staf', $result['reason']);
    }

    public function test_can_be_kanit_ineligible_with_less_than_2_years(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYear(),
        ]);

        $periode = $this->createPeriode();
        RiwayatKepengurusan::create([
            'anggota_id'    => $anggota->id,
            'periode_id'    => $periode->id,
            'jabatan'       => 'staf',
            'divisi'        => 'kominfo',
            'tanggal_mulai' => '2023-01-01',
            'tanggal_selesai' => '2024-12-31',
            'status'        => 'selesai',
        ]);

        $result = $this->validator->canBeKanit($anggota);

        $this->assertFalse($result['eligible']);
    }

    // ====================================================================
    // canBeKetum()
    // ====================================================================

    public function test_can_be_ketum_eligible_with_2_years(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(3),
        ]);

        $result = $this->validator->canBeKetum($anggota);

        $this->assertTrue($result['eligible']);
    }

    public function test_can_be_ketum_ineligible_with_less_than_2_years(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYear(),
        ]);

        $result = $this->validator->canBeKetum($anggota);

        $this->assertFalse($result['eligible']);
    }

    // ====================================================================
    // validate() — dispatch by jabatan
    // ====================================================================

    public function test_validate_dispatches_to_correct_method(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(3),
        ]);

        // Ketua umum
        $result = $this->validator->validate($anggota, Jabatan::KETUA_UMUM);
        $this->assertArrayHasKey('eligible', $result);

        // Kadiv
        $result = $this->validator->validate($anggota, Jabatan::KADIV_FOTOGRAFI);
        $this->assertArrayHasKey('eligible', $result);

        // Staf
        $result = $this->validator->validate($anggota, Jabatan::STAF);
        $this->assertArrayHasKey('eligible', $result);

        // Unknown jabatan (should be eligible by default)
        $result = $this->validator->validate($anggota, 'unknown_jabatan');
        $this->assertTrue($result['eligible']);
    }

    // ====================================================================
    // canBeWakilSekumBendum()
    // ====================================================================

    public function test_can_be_wakil_eligible_with_1_year(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(2),
        ]);

        $result = $this->validator->canBeWakilSekumBendum($anggota);

        $this->assertTrue($result['eligible']);
    }

    public function test_can_be_sekretaris_ineligible_with_less_than_1_year(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subMonths(6),
        ]);

        $result = $this->validator->validate($anggota, Jabatan::SEKRETARIS_UMUM_1);

        $this->assertFalse($result['eligible']);
    }
}
