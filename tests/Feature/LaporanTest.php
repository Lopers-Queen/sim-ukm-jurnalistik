<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Laporan & Export (Blackbox)
 */
class LaporanTest extends TestCase
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
    // LAPORAN PAGES
    // ====================================================================

    public function test_laporan_index_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('laporan.index'));
        $response->assertStatus(200);
    }

    public function test_laporan_anggota_accessible(): void
    {
        Anggota::factory()->sudahLogin()->count(3)->create();

        $response = $this->actingAs($this->ketuaUmum)->get(route('laporan.anggota'));
        $response->assertStatus(200);
    }

    public function test_laporan_event_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('laporan.event'));
        $response->assertStatus(200);
    }

    public function test_laporan_keuangan_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('laporan.keuangan'));
        $response->assertStatus(200);
    }

    // ====================================================================
    // FILTER
    // ====================================================================

    public function test_laporan_anggota_with_divisi_filter(): void
    {
        Anggota::factory()->sudahLogin()->create(['divisi' => 'fotografi']);
        Anggota::factory()->sudahLogin()->create(['divisi' => 'redaksi']);

        $response = $this->actingAs($this->ketuaUmum)
            ->get(route('laporan.anggota', ['divisi' => 'fotografi']));

        $response->assertStatus(200);
    }

    public function test_laporan_anggota_with_status_filter(): void
    {
        Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'aktif']);
        Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'pasif']);

        $response = $this->actingAs($this->ketuaUmum)
            ->get(route('laporan.anggota', ['status' => 'aktif']));

        $response->assertStatus(200);
    }

    // ====================================================================
    // EXPORT
    // ====================================================================

    public function test_export_anggota_pdf(): void
    {
        Anggota::factory()->sudahLogin()->count(2)->create();

        $response = $this->actingAs($this->ketuaUmum)
            ->get(route('laporan.anggota.pdf'));

        // PDF download returns 200 with PDF content type
        $response->assertStatus(200);
    }

    public function test_export_anggota_excel(): void
    {
        Anggota::factory()->sudahLogin()->count(2)->create();

        $response = $this->actingAs($this->ketuaUmum)
            ->get(route('laporan.anggota.excel'));

        $response->assertStatus(200);
    }

    public function test_export_event_pdf(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->get(route('laporan.event.pdf'));

        $response->assertStatus(200);
    }

    public function test_export_keuangan_pdf(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->get(route('laporan.keuangan.pdf'));

        $response->assertStatus(200);
    }

    // ====================================================================
    // UNAUTHORIZED
    // ====================================================================

    public function test_unauthorized_cannot_access_laporan(): void
    {
        $staf = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'staf']);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->get(route('laporan.index'));
        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_export(): void
    {
        $response = $this->get(route('laporan.anggota.pdf'));
        $response->assertRedirect(route('login'));
    }
}
