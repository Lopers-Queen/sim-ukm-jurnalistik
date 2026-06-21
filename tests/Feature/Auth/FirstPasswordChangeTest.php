<?php

namespace Tests\Feature\Auth;

use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Feature Tests: First Password Change Flow
 * Menguji alur ganti password pertama kali dan middleware ForceFirstPasswordChange.
 */
class FirstPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    // ====================================================================
    // MIDDLEWARE ForceFirstPasswordChange
    // ====================================================================

    public function test_first_login_user_redirected_to_password_change(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('password.first-change'));
    }

    public function test_non_first_login_user_not_redirected(): void
    {
        $user = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_skip_sets_session_flag_and_allows_access(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $response = $this->actingAs($user)->post(route('password.first-change.skip'));

        $response->assertRedirect(route('dashboard'));

        // After skip, user should be able to access dashboard
        $response = $this->actingAs($user)
            ->withSession(['password_change_postponed' => true])
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_first_password_change_page_is_accessible(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $response = $this->actingAs($user)->get(route('password.first-change'));

        $response->assertStatus(200);
    }

    // ====================================================================
    // UPDATE PASSWORD
    // ====================================================================

    public function test_update_password_succeeds_with_valid_input(): void
    {
        $user = Anggota::factory()->create([
            'is_first_login' => true,
            'nim'            => '12345678',
            'tanggal_lahir'  => '2000-01-15',
        ]);

        $response = $this->actingAs($user)->post(route('password.first-change.update'), [
            'password'              => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertFalse($user->is_first_login);
        $this->assertTrue(Hash::check('NewPassword123', $user->password));
    }

    public function test_update_password_fails_without_confirmation(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $response = $this->actingAs($user)
            ->from(route('password.first-change'))
            ->post(route('password.first-change.update'), [
                'password'              => 'NewPassword123',
                'password_confirmation' => 'DifferentPassword',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_update_password_fails_when_same_as_nim(): void
    {
        $user = Anggota::factory()->create([
            'is_first_login' => true,
            'nim'            => 'mypassword1',
        ]);

        $response = $this->actingAs($user)
            ->from(route('password.first-change'))
            ->post(route('password.first-change.update'), [
                'password'              => 'mypassword1',
                'password_confirmation' => 'mypassword1',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_update_password_fails_when_same_as_birthdate(): void
    {
        $user = Anggota::factory()->create([
            'is_first_login' => true,
            'tanggal_lahir'  => '2000-08-15',
        ]);

        $response = $this->actingAs($user)
            ->from(route('password.first-change'))
            ->post(route('password.first-change.update'), [
                'password'              => '15082000',
                'password_confirmation' => '15082000',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_update_password_fails_too_short(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $response = $this->actingAs($user)
            ->from(route('password.first-change'))
            ->post(route('password.first-change.update'), [
                'password'              => 'Short1',
                'password_confirmation' => 'Short1',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_update_password_fails_without_numbers(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $response = $this->actingAs($user)
            ->from(route('password.first-change'))
            ->post(route('password.first-change.update'), [
                'password'              => 'NoNumbersHere',
                'password_confirmation' => 'NoNumbersHere',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_update_password_clears_postponed_session(): void
    {
        $user = Anggota::factory()->create(['is_first_login' => true]);

        $this->actingAs($user)
            ->withSession(['password_change_postponed' => true])
            ->post(route('password.first-change.update'), [
                'password'              => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ]);

        $this->assertFalse(session()->has('password_change_postponed'));
    }
}
