<?php

namespace Tests\Unit;

use App\Models\Anggota;
use App\Services\AnggotaService;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Unit Tests: AnggotaService
 * Menguji business logic untuk role assignment, password reset, dan preparation.
 */
class AnggotaServiceTest extends TestCase
{
    use RefreshDatabase;

    private AnggotaService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->service = new AnggotaService();
    }

    // ====================================================================
    // assignRoleByJabatan()
    // ====================================================================

    public function test_assign_role_by_jabatan_ketua_umum(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'ketua_umum', 'status_keanggotaan' => 'aktif']);

        $this->service->assignRoleByJabatan($anggota);

        $this->assertTrue($anggota->hasRole('ketua_umum'));
    }

    public function test_assign_role_by_jabatan_staf(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'staf', 'status_keanggotaan' => 'aktif']);

        $this->service->assignRoleByJabatan($anggota);

        $this->assertTrue($anggota->hasRole('staf'));
    }

    public function test_assign_role_by_jabatan_anggota_aktif(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'anggota', 'status_keanggotaan' => 'aktif']);

        $this->service->assignRoleByJabatan($anggota);

        $this->assertTrue($anggota->hasRole('anggota_aktif'));
    }

    public function test_assign_role_by_jabatan_pasif_overrides_to_anggota_pasif(): void
    {
        $anggota = Anggota::factory()->create([
            'jabatan_struktural' => 'ketua_umum',
            'status_keanggotaan' => 'pasif',
        ]);

        $this->service->assignRoleByJabatan($anggota);

        $this->assertTrue($anggota->hasRole('anggota_pasif'));
        $this->assertFalse($anggota->hasRole('ketua_umum'));
    }

    public function test_assign_role_removes_previous_roles(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'staf', 'status_keanggotaan' => 'aktif']);
        $anggota->assignRole('staf');

        $anggota->update(['jabatan_struktural' => 'kadiv_fotografi']);
        $this->service->assignRoleByJabatan($anggota);

        $this->assertTrue($anggota->hasRole('kadiv_fotografi'));
        $this->assertFalse($anggota->hasRole('staf'));
    }

    // ====================================================================
    // prepareForCreation()
    // ====================================================================

    public function test_prepare_for_creation_generates_password_from_birthdate(): void
    {
        $data = [
            'tanggal_lahir' => '2000-08-15',
        ];

        $result = $this->service->prepareForCreation($data);

        $this->assertTrue(Hash::check('15082000', $result['password']));
        $this->assertTrue($result['is_first_login']);
        $this->assertArrayHasKey('tanggal_bergabung', $result);
    }

    public function test_prepare_for_creation_preserves_existing_tanggal_bergabung(): void
    {
        $data = [
            'tanggal_lahir' => '2000-08-15',
            'tanggal_bergabung' => '2023-09-01',
        ];

        $result = $this->service->prepareForCreation($data);

        $this->assertEquals('2023-09-01', $result['tanggal_bergabung']);
    }

    // ====================================================================
    // resetPassword()
    // ====================================================================

    public function test_reset_password_with_default(): void
    {
        // Need an authenticated user for activity log
        $admin = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'admin']);
        $this->actingAs($admin);

        $anggota = Anggota::factory()->create([
            'is_locked' => true,
            'locked_until' => now()->addMinutes(10),
            'failed_login_attempts' => 3,
        ]);

        $this->service->resetPassword($anggota, null);

        $anggota->refresh();
        $this->assertTrue(Hash::check('12345678', $anggota->password));
        $this->assertTrue($anggota->is_first_login);
        $this->assertFalse($anggota->is_locked);
        $this->assertNull($anggota->locked_until);
        $this->assertEquals(0, $anggota->failed_login_attempts);
    }

    public function test_reset_password_with_custom(): void
    {
        $admin = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'admin']);
        $this->actingAs($admin);

        $anggota = Anggota::factory()->create();

        $this->service->resetPassword($anggota, 'MyCustomPw123');

        $anggota->refresh();
        $this->assertTrue(Hash::check('MyCustomPw123', $anggota->password));
    }

    // ====================================================================
    // resetAllPasswords()
    // ====================================================================

    public function test_reset_all_passwords_returns_count(): void
    {
        $admin = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'admin']);
        $this->actingAs($admin);

        $anggotas = Anggota::factory()->count(3)->create();

        $count = $this->service->resetAllPasswords($anggotas, '12345678');

        $this->assertEquals(3, $count);

        foreach ($anggotas as $anggota) {
            $anggota->refresh();
            $this->assertTrue(Hash::check('12345678', $anggota->password));
        }
    }
}
