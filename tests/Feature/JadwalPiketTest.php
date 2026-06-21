<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\JadwalShift;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Jadwal Piket (Blackbox)
 */
class JadwalPiketTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $kanit;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->kanit = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'kanit_kominfo',
        ]);
        $this->kanit->assignRole('kanit_kominfo');
    }

    public function test_jadwal_index_accessible(): void
    {
        $response = $this->actingAs($this->kanit)->get(route('jadwal.index'));
        $response->assertStatus(200);
    }

    public function test_store_jadwal_shift(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->kanit)->post(route('jadwal.store'), [
            'anggota_id' => $anggota->id,
            'hari'       => 'senin',
        ]);

        $response->assertRedirect(route('jadwal.index'));
        $this->assertDatabaseHas('jadwal_shift', [
            'anggota_id' => $anggota->id,
            'hari'       => 'senin',
        ]);
    }

    public function test_update_jadwal_shift(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();
        $jadwal = JadwalShift::create([
            'anggota_id' => $anggota->id,
            'hari'       => 'senin',
        ]);

        $response = $this->actingAs($this->kanit)->put(route('jadwal.update', $jadwal), [
            'anggota_id' => $anggota->id,
            'hari'       => 'rabu',
        ]);

        $response->assertRedirect(route('jadwal.index'));
        $this->assertEquals('rabu', $jadwal->fresh()->hari);
    }

    public function test_delete_jadwal_shift(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();
        $jadwal = JadwalShift::create([
            'anggota_id' => $anggota->id,
            'hari'       => 'senin',
        ]);

        $response = $this->actingAs($this->kanit)->delete(route('jadwal.destroy', $jadwal));

        $response->assertRedirect(route('jadwal.index'));
        $this->assertSoftDeleted('jadwal_shift', ['id' => $jadwal->id]);
    }

    public function test_generate_jadwal_acak(): void
    {
        Anggota::factory()->sudahLogin()->count(6)->create(['status_keanggotaan' => 'aktif']);

        $response = $this->actingAs($this->kanit)->post(route('jadwal.generate-acak'), [
            'hari' => ['senin', 'rabu', 'jumat'],
        ]);

        $response->assertRedirect(route('jadwal.index'));
        $this->assertGreaterThan(0, JadwalShift::count());
    }

    public function test_generate_jadwal_fails_with_no_active_members(): void
    {
        // Buat user non-admin yang tidak aktif (pasif), sehingga nonAdmin()->aktif() kosong
        $nonActive = Anggota::factory()->sudahLogin()->create([
            'status_keanggotaan' => 'pasif',
            'jabatan_struktural' => 'anggota',
        ]);

        // User kanit dari setUp juga harus pasif agar tidak terdeteksi
        $this->kanit->update(['status_keanggotaan' => 'pasif']);

        $response = $this->actingAs($this->kanit)
            ->from(route('jadwal.index'))
            ->post(route('jadwal.generate-acak'), [
                'hari' => ['senin'],
            ]);

        $response->assertSessionHas('error');
    }

    public function test_generate_jadwal_validates_hari(): void
    {
        Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'aktif']);

        $response = $this->actingAs($this->kanit)
            ->from(route('jadwal.index'))
            ->post(route('jadwal.generate-acak'), [
                'hari' => [],
            ]);

        $response->assertSessionHasErrors('hari');
    }

    public function test_unauthorized_cannot_access_jadwal(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'anggota']);
        $anggota->assignRole('anggota_aktif');

        $response = $this->actingAs($anggota)->get(route('jadwal.index'));
        $response->assertStatus(403);
    }
}
