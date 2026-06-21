<?php

namespace Tests\Feature\Auth;

use App\Models\Anggota;
use App\Models\LoginHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Feature Tests: Login Flow (Blackbox)
 * Menguji seluruh alur autentikasi: login, rate limiting, lockout, history.
 */
class LoginFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createAnggota(array $overrides = []): Anggota
    {
        return Anggota::factory()->sudahLogin()->create(array_merge([
            'nim'      => '12345678',
            'password' => Hash::make('password123'),
        ], $overrides));
    }

    // ====================================================================
    // LOGIN BERHASIL
    // ====================================================================

    public function test_login_success_with_correct_credentials(): void
    {
        $anggota = $this->createAnggota();

        $response = $this->post('/login', [
            'nim'      => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($anggota);
    }

    public function test_login_records_success_history(): void
    {
        $this->createAnggota();

        $this->post('/login', [
            'nim'      => '12345678',
            'password' => 'password123',
        ]);

        $history = LoginHistory::where('status', 'success')->first();
        $this->assertNotNull($history);
        $this->assertEquals('Login berhasil', $history->keterangan);
    }

    public function test_login_resets_failed_attempts_on_success(): void
    {
        $anggota = $this->createAnggota([
            'failed_login_attempts' => 3,
        ]);

        $this->post('/login', [
            'nim'      => '12345678',
            'password' => 'password123',
        ]);

        $anggota->refresh();
        $this->assertEquals(0, $anggota->failed_login_attempts);
        $this->assertFalse($anggota->is_locked);
    }

    // ====================================================================
    // LOGIN GAGAL
    // ====================================================================

    public function test_login_fails_with_wrong_nim(): void
    {
        $this->createAnggota();

        $response = $this->post('/login', [
            'nim'      => '99999999',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $anggota = $this->createAnggota();

        $response = $this->from('/login')->post('/login', [
            'nim'      => '12345678',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();

        $anggota->refresh();
        $this->assertEquals(1, $anggota->failed_login_attempts);
    }

    public function test_login_fails_records_failed_history(): void
    {
        $this->createAnggota();

        $this->from('/login')->post('/login', [
            'nim'      => '12345678',
            'password' => 'wrongpassword',
        ]);

        $history = LoginHistory::where('status', 'failed')->first();
        $this->assertNotNull($history);
        $this->assertStringContainsString('Gagal login', $history->keterangan);
    }

    public function test_login_validates_required_fields(): void
    {
        $response = $this->from('/login')->post('/login', [
            'nim'      => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['nim', 'password']);
    }

    // ====================================================================
    // ACCOUNT LOCKOUT
    // ====================================================================

    public function test_account_locks_after_5_failed_attempts(): void
    {
        $anggota = $this->createAnggota();

        for ($i = 0; $i < 5; $i++) {
            $this->from('/login')->post('/login', [
                'nim'      => '12345678',
                'password' => 'wrongpassword',
            ]);
        }

        $anggota->refresh();
        $this->assertTrue($anggota->is_locked);
        $this->assertNotNull($anggota->locked_until);
    }

    public function test_login_fails_when_account_is_locked(): void
    {
        $this->createAnggota([
            'is_locked'    => true,
            'locked_until' => now()->addMinutes(15),
        ]);

        $response = $this->from('/login')->post('/login', [
            'nim'      => '12345678',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();

        $history = LoginHistory::where('status', 'locked')->first();
        $this->assertNotNull($history);
    }

    // ====================================================================
    // FIRST LOGIN REDIRECT
    // ====================================================================

    public function test_login_redirects_to_first_password_change_for_first_login(): void
    {
        Anggota::factory()->create([
            'nim'            => '12345678',
            'password'       => Hash::make('password123'),
            'is_first_login' => true,
        ]);

        $response = $this->post('/login', [
            'nim'      => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('password.first-change'));
    }

    // ====================================================================
    // LOGOUT
    // ====================================================================

    public function test_logout_works_correctly(): void
    {
        $anggota = $this->createAnggota();

        $this->actingAs($anggota);
        $this->assertAuthenticated();

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_logout_invalidates_session(): void
    {
        $anggota = $this->createAnggota();
        $this->actingAs($anggota);

        $this->post(route('logout'));

        // Attempting to access protected route should redirect to login
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    // ====================================================================
    // REMEMBER ME
    // ====================================================================

    public function test_login_with_remember_me(): void
    {
        $this->createAnggota();

        $response = $this->post('/login', [
            'nim'      => '12345678',
            'password' => 'password123',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }
}
