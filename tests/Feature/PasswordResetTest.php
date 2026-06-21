<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Feature Tests: Admin Password Reset (Blackbox)
 * Menguji reset password individual dan bulk oleh super admin.
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->superAdmin = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'admin',
        ]);
        $this->superAdmin->assignRole('super_admin');
    }

    // ====================================================================
    // INDIVIDUAL PASSWORD RESET
    // ====================================================================

    public function test_reset_password_default(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->superAdmin)
            ->from(route('anggota.show', $anggota))
            ->post(route('anggota.reset-password', $anggota), []);

        $response->assertRedirect();

        $anggota->refresh();
        $this->assertTrue(Hash::check('12345678', $anggota->password));
        $this->assertTrue($anggota->is_first_login);
    }

    public function test_reset_password_custom(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->superAdmin)
            ->from(route('anggota.show', $anggota))
            ->post(route('anggota.reset-password', $anggota), [
                'password' => 'CustomPw99',
            ]);

        $response->assertRedirect();

        $anggota->refresh();
        $this->assertTrue(Hash::check('CustomPw99', $anggota->password));
    }

    public function test_reset_password_clears_lock(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create([
            'is_locked'    => true,
            'locked_until' => now()->addMinutes(10),
            'failed_login_attempts' => 5,
        ]);

        $this->actingAs($this->superAdmin)
            ->post(route('anggota.reset-password', $anggota), []);

        $anggota->refresh();
        $this->assertFalse($anggota->is_locked);
        $this->assertNull($anggota->locked_until);
        $this->assertEquals(0, $anggota->failed_login_attempts);
    }

    // ====================================================================
    // BULK PASSWORD RESET
    // ====================================================================

    public function test_bulk_reset_passwords(): void
    {
        Anggota::factory()->sudahLogin()->count(5)->create();

        $response = $this->actingAs($this->superAdmin)
            ->from(route('anggota.index'))
            ->post(route('anggota.reset-all-passwords'), []);

        $response->assertRedirect();

        $anggotas = Anggota::nonAdmin()->get();
        foreach ($anggotas as $anggota) {
            $this->assertTrue(Hash::check('12345678', $anggota->password));
        }
    }

    // ====================================================================
    // VALIDATION
    // ====================================================================

    public function test_reset_password_validates_min_length(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->superAdmin)
            ->from(route('anggota.show', $anggota))
            ->post(route('anggota.reset-password', $anggota), [
                'password' => 'ab',
            ]);

        $response->assertSessionHasErrors('password');
    }

    // ====================================================================
    // UNAUTHORIZED
    // ====================================================================

    public function test_non_admin_cannot_reset_password(): void
    {
        $staf = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'staf']);
        $staf->assignRole('staf');

        $target = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($staf)
            ->post(route('anggota.reset-password', $target), []);

        $response->assertStatus(403);
    }
}
