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
 * Feature Tests: Keaktifan (Blackbox)
 */
class KeaktifanTest extends TestCase
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

    public function test_keaktifan_index_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('keaktifan.index'));
        $response->assertStatus(200);
    }

    public function test_toggle_status_to_pasif(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'aktif']);

        $response = $this->actingAs($this->ketuaUmum)
            ->post(route('keaktifan.toggle', $anggota), ['status' => 'pasif']);

        $response->assertRedirect();
        $this->assertEquals('pasif', $anggota->fresh()->status_keanggotaan);
    }

    public function test_toggle_status_to_aktif(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'pasif']);

        $response = $this->actingAs($this->ketuaUmum)
            ->post(route('keaktifan.toggle', $anggota), ['status' => 'aktif']);

        $response->assertRedirect();
        $this->assertEquals('aktif', $anggota->fresh()->status_keanggotaan);
    }

    public function test_toggle_status_validates_input(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('keaktifan.index'))
            ->post(route('keaktifan.toggle', $anggota), ['status' => 'invalid']);

        $response->assertSessionHasErrors('status');
    }

    public function test_batch_update_status(): void
    {
        $anggotas = Anggota::factory()->sudahLogin()->count(3)->create(['status_keanggotaan' => 'aktif']);

        $response = $this->actingAs($this->ketuaUmum)
            ->post(route('keaktifan.batch-update'), [
                'anggota_ids' => $anggotas->pluck('id')->toArray(),
                'status'      => 'pasif',
            ]);

        $response->assertRedirect();

        foreach ($anggotas as $anggota) {
            $this->assertEquals('pasif', $anggota->fresh()->status_keanggotaan);
        }
    }

    public function test_batch_update_validates_anggota_ids(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('keaktifan.index'))
            ->post(route('keaktifan.batch-update'), [
                'anggota_ids' => [],
                'status'      => 'pasif',
            ]);

        $response->assertSessionHasErrors('anggota_ids');
    }

    public function test_perpanjangan_page_accessible(): void
    {
        PeriodeKepengurusan::create([
            'nama_periode'   => 'Test',
            'tahun_mulai'    => 2025,
            'tahun_selesai'  => 2026,
            'tanggal_mulai'  => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'         => 'aktif',
        ]);

        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($anggota)->get(route('keaktifan.perpanjangan'));
        $response->assertStatus(200);
    }

    public function test_submit_perpanjangan_updates_status(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'pasif']);

        $response = $this->actingAs($anggota)
            ->post(route('keaktifan.submit-perpanjangan'));

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals('aktif', $anggota->fresh()->status_keanggotaan);
    }

    public function test_unauthorized_cannot_toggle_status(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'anggota']);
        $anggota->assignRole('anggota_aktif');

        $target = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($anggota)
            ->post(route('keaktifan.toggle', $target), ['status' => 'pasif']);

        $response->assertStatus(403);
    }
}
