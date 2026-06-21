<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\PeriodeKepengurusan;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Periode & Pergantian Kepengurusan (Blackbox)
 */
class PeriodePergantianTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $ketuaUmum;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->ketuaUmum = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'ketua_umum',
        ]);
        $this->ketuaUmum->assignRole('ketua_umum');
    }

    // ====================================================================
    // PERIODE CRUD
    // ====================================================================

    public function test_periode_index_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('periode.index'));
        $response->assertStatus(200);
    }

    public function test_create_periode_page_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('periode.create'));
        $response->assertStatus(200);
    }

    public function test_store_periode_with_valid_data(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->post(route('periode.store'), [
            'nama_periode'    => 'Periode 2026/2027',
            'tahun_mulai'     => 2026,
            'tahun_selesai'   => 2027,
            'tanggal_mulai'   => '2026-01-01',
            'tanggal_selesai' => '2027-12-31',
            'deskripsi'       => 'Periode baru',
            'status'          => 'selesai',
        ]);

        $response->assertRedirect(route('periode.index'));
        $this->assertDatabaseHas('periode_kepengurusan', [
            'nama_periode' => 'Periode 2026/2027',
        ]);
    }

    public function test_store_periode_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('periode.create'))
            ->post(route('periode.store'), []);

        $response->assertSessionHasErrors();
    }

    public function test_store_periode_aktif_deactivates_others(): void
    {
        PeriodeKepengurusan::create([
            'nama_periode'    => 'Old Active',
            'tahun_mulai'     => 2024,
            'tahun_selesai'   => 2025,
            'tanggal_mulai'   => '2024-01-01',
            'tanggal_selesai' => '2025-12-31',
            'status'          => 'aktif',
        ]);

        $this->actingAs($this->ketuaUmum)->post(route('periode.store'), [
            'nama_periode'    => 'New Active',
            'tahun_mulai'     => 2026,
            'tahun_selesai'   => 2027,
            'tanggal_mulai'   => '2026-01-01',
            'tanggal_selesai' => '2027-12-31',
            'status'          => 'aktif',
        ]);

        $old = PeriodeKepengurusan::where('nama_periode', 'Old Active')->first();
        $this->assertEquals('selesai', $old->status);
    }

    public function test_show_periode(): void
    {
        $periode = PeriodeKepengurusan::create([
            'nama_periode'    => 'Test',
            'tahun_mulai'     => 2025,
            'tahun_selesai'   => 2026,
            'tanggal_mulai'   => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'          => 'aktif',
        ]);

        $response = $this->actingAs($this->ketuaUmum)->get(route('periode.show', $periode));
        $response->assertStatus(200);
    }

    public function test_update_periode(): void
    {
        $periode = PeriodeKepengurusan::create([
            'nama_periode'    => 'Test',
            'tahun_mulai'     => 2025,
            'tahun_selesai'   => 2026,
            'tanggal_mulai'   => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'          => 'selesai',
        ]);

        $response = $this->actingAs($this->ketuaUmum)->put(route('periode.update', $periode), [
            'nama_periode'    => 'Updated Periode',
            'tahun_mulai'     => 2025,
            'tahun_selesai'   => 2026,
            'tanggal_mulai'   => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'          => 'selesai',
        ]);

        $response->assertRedirect(route('periode.index'));
        $this->assertEquals('Updated Periode', $periode->fresh()->nama_periode);
    }

    public function test_delete_periode(): void
    {
        $periode = PeriodeKepengurusan::create([
            'nama_periode'    => 'To Delete',
            'tahun_mulai'     => 2025,
            'tahun_selesai'   => 2026,
            'tanggal_mulai'   => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'          => 'selesai',
        ]);

        $response = $this->actingAs($this->ketuaUmum)->delete(route('periode.destroy', $periode));

        $response->assertRedirect(route('periode.index'));
        $this->assertDatabaseMissing('periode_kepengurusan', ['id' => $periode->id]);
    }

    // ====================================================================
    // PERGANTIAN KEPENGURUSAN
    // ====================================================================

    public function test_pergantian_index_accessible(): void
    {
        PeriodeKepengurusan::create([
            'nama_periode'    => 'Active',
            'tahun_mulai'     => 2025,
            'tahun_selesai'   => 2026,
            'tanggal_mulai'   => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'          => 'aktif',
        ]);

        $response = $this->actingAs($this->ketuaUmum)->get(route('pergantian.index'));
        $response->assertStatus(200);
    }

    public function test_validate_susunan_eligibility(): void
    {
        $anggota1 = Anggota::factory()->sudahLogin()->create([
            'tanggal_bergabung' => now()->subYears(3),
        ]);
        $anggota2 = Anggota::factory()->sudahLogin()->create([
            'tanggal_bergabung' => now()->subYears(2),
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->postJson(route('pergantian.validate'), [
                'susunan' => [
                    'ketua_umum'     => $anggota1->id,
                    'wakil_ketua_umum' => $anggota2->id,
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['results']);
    }

    // ====================================================================
    // UNAUTHORIZED
    // ====================================================================

    public function test_anggota_aktif_cannot_access_periode(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'anggota']);
        $anggota->assignRole('anggota_aktif');

        $response = $this->actingAs($anggota)->get(route('periode.index'));
        $response->assertStatus(403);
    }

    public function test_staf_cannot_access_pergantian(): void
    {
        $staf = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'staf']);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->get(route('pergantian.index'));
        $response->assertStatus(403);
    }
}
