<?php

namespace Tests\Unit;

use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit Tests: Model Anggota
 * Menguji scopes, accessors, helper methods, dan business logic model.
 */
class AnggotaModelTest extends TestCase
{
    use RefreshDatabase;

    // ====================================================================
    // SCOPE TESTS
    // ====================================================================

    public function test_scope_non_admin_excludes_admin_accounts(): void
    {
        Anggota::factory()->create(['jabatan_struktural' => 'admin']);
        Anggota::factory()->create(['jabatan_struktural' => 'anggota']);

        $result = Anggota::nonAdmin()->get();

        $this->assertCount(1, $result);
        $this->assertEquals('anggota', $result->first()->jabatan_struktural);
    }

    public function test_scope_aktif_only_returns_active_members(): void
    {
        Anggota::factory()->create(['status_keanggotaan' => 'aktif']);
        Anggota::factory()->create(['status_keanggotaan' => 'pasif']);
        Anggota::factory()->create(['status_keanggotaan' => 'alumni']);

        $result = Anggota::aktif()->get();

        $this->assertCount(1, $result);
        $this->assertEquals('aktif', $result->first()->status_keanggotaan);
    }

    public function test_scope_search_by_nim(): void
    {
        Anggota::factory()->create(['nim' => '12345678']);
        Anggota::factory()->create(['nim' => '87654321']);

        $result = Anggota::search('12345')->get();

        $this->assertCount(1, $result);
        $this->assertEquals('12345678', $result->first()->nim);
    }

    public function test_scope_search_by_nama(): void
    {
        Anggota::factory()->create(['nama_lengkap' => 'Budi Santoso']);
        Anggota::factory()->create(['nama_lengkap' => 'Siti Aminah']);

        $result = Anggota::search('Budi')->get();

        $this->assertCount(1, $result);
        $this->assertEquals('Budi Santoso', $result->first()->nama_lengkap);
    }

    public function test_scope_search_by_email(): void
    {
        Anggota::factory()->create(['email' => 'budi@test.com']);
        Anggota::factory()->create(['email' => 'siti@test.com']);

        $result = Anggota::search('budi@test')->get();

        $this->assertCount(1, $result);
    }

    public function test_scope_search_with_null_returns_all(): void
    {
        Anggota::factory()->count(3)->create();

        $result = Anggota::search(null)->get();

        $this->assertCount(3, $result);
    }

    public function test_scope_divisi_filters_by_divisi(): void
    {
        Anggota::factory()->create(['divisi' => 'fotografi']);
        Anggota::factory()->create(['divisi' => 'redaksi']);

        $result = Anggota::divisi('fotografi')->get();

        $this->assertCount(1, $result);
        $this->assertEquals('fotografi', $result->first()->divisi);
    }

    public function test_scope_divisi_with_null_returns_all(): void
    {
        Anggota::factory()->count(3)->create();

        $result = Anggota::divisi(null)->get();

        $this->assertCount(3, $result);
    }

    // ====================================================================
    // METHOD TESTS: isLocked()
    // ====================================================================

    public function test_is_locked_returns_false_when_not_locked(): void
    {
        $anggota = Anggota::factory()->create([
            'is_locked' => false,
            'locked_until' => null,
        ]);

        $this->assertFalse($anggota->isLocked());
    }

    public function test_is_locked_returns_true_when_locked_and_not_expired(): void
    {
        $anggota = Anggota::factory()->create([
            'is_locked' => true,
            'locked_until' => now()->addMinutes(10),
        ]);

        $this->assertTrue($anggota->isLocked());
    }

    public function test_is_locked_auto_unlocks_when_time_expired(): void
    {
        $anggota = Anggota::factory()->create([
            'is_locked' => true,
            'locked_until' => now()->subMinutes(1),
            'failed_login_attempts' => 5,
        ]);

        $result = $anggota->isLocked();

        $this->assertFalse($result);
        $anggota->refresh();
        $this->assertFalse($anggota->is_locked);
        $this->assertNull($anggota->locked_until);
        $this->assertEquals(0, $anggota->failed_login_attempts);
    }

    // ====================================================================
    // METHOD TESTS: incrementFailedLogin()
    // ====================================================================

    public function test_increment_failed_login_increments_counter(): void
    {
        $anggota = Anggota::factory()->create(['failed_login_attempts' => 0]);

        $anggota->incrementFailedLogin();

        $anggota->refresh();
        $this->assertEquals(1, $anggota->failed_login_attempts);
        $this->assertFalse($anggota->is_locked);
    }

    public function test_increment_failed_login_locks_account_after_5_attempts(): void
    {
        $anggota = Anggota::factory()->create(['failed_login_attempts' => 4]);

        $anggota->incrementFailedLogin();

        $anggota->refresh();
        $this->assertEquals(5, $anggota->failed_login_attempts);
        $this->assertTrue($anggota->is_locked);
        $this->assertNotNull($anggota->locked_until);
        // Lock should be approximately 15 minutes from now
        $this->assertTrue($anggota->locked_until->isFuture());
    }

    // ====================================================================
    // METHOD TESTS: resetFailedLogin()
    // ====================================================================

    public function test_reset_failed_login_clears_all_lock_data(): void
    {
        $anggota = Anggota::factory()->create([
            'failed_login_attempts' => 5,
            'is_locked' => true,
            'locked_until' => now()->addMinutes(15),
        ]);

        $anggota->resetFailedLogin();

        $anggota->refresh();
        $this->assertEquals(0, $anggota->failed_login_attempts);
        $this->assertFalse($anggota->is_locked);
        $this->assertNull($anggota->locked_until);
    }

    // ====================================================================
    // METHOD TESTS: generateDefaultPassword()
    // ====================================================================

    public function test_generate_default_password_from_tanggal_lahir(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_lahir' => '2000-08-15',
        ]);

        $password = $anggota->generateDefaultPassword();

        $this->assertEquals('15082000', $password);
    }

    public function test_generate_default_password_returns_admin123_when_no_birthdate(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_lahir' => null,
        ]);

        $password = $anggota->generateDefaultPassword();

        $this->assertEquals('admin123', $password);
    }

    // ====================================================================
    // METHOD TESTS: masaKeanggotaan()
    // ====================================================================

    public function test_masa_keanggotaan_calculates_years(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => now()->subYears(3),
        ]);

        $this->assertEquals(3, $anggota->masaKeanggotaan());
    }

    public function test_masa_keanggotaan_returns_zero_when_null(): void
    {
        $anggota = Anggota::factory()->create([
            'tanggal_bergabung' => null,
        ]);

        $this->assertEquals(0, $anggota->masaKeanggotaan());
    }

    // ====================================================================
    // METHOD TESTS: isFirstLogin()
    // ====================================================================

    public function test_is_first_login_returns_true(): void
    {
        $anggota = Anggota::factory()->create(['is_first_login' => true]);
        $this->assertTrue($anggota->isFirstLogin());
    }

    public function test_is_first_login_returns_false(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();
        $this->assertFalse($anggota->isFirstLogin());
    }

    // ====================================================================
    // ACCESSOR TESTS
    // ====================================================================

    public function test_get_status_label_attribute(): void
    {
        $anggota = Anggota::factory()->create(['status_keanggotaan' => 'aktif']);
        $this->assertEquals('Aktif', $anggota->status_label);

        $anggota->update(['status_keanggotaan' => 'pasif']);
        $this->assertEquals('Pasif', $anggota->fresh()->status_label);

        $anggota->update(['status_keanggotaan' => 'alumni']);
        $this->assertEquals('Alumni', $anggota->fresh()->status_label);
    }

    public function test_get_jabatan_lengkap_attribute(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'ketua_umum']);
        $this->assertEquals('Ketua Umum', $anggota->jabatan_lengkap);
    }

    public function test_get_divisi_label_attribute(): void
    {
        $anggota = Anggota::factory()->create(['divisi' => 'fotografi']);
        $this->assertEquals('Fotografi', $anggota->divisi_label);
    }

    public function test_get_divisi_label_returns_dash_when_null(): void
    {
        $anggota = Anggota::factory()->create(['divisi' => null]);
        $this->assertEquals('-', $anggota->divisi_label);
    }

    public function test_get_full_name_attribute(): void
    {
        $anggota = Anggota::factory()->create(['nama_lengkap' => 'Budi Santoso']);
        $this->assertEquals('Budi Santoso', $anggota->full_name);
    }

    // ====================================================================
    // HELPER METHOD TESTS
    // ====================================================================

    public function test_is_bpi_returns_true_for_bpi_positions(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'ketua_umum']);
        $this->assertTrue($anggota->isBpi());

        $anggota->update(['jabatan_struktural' => 'bendahara_umum_1']);
        $this->assertTrue($anggota->fresh()->isBpi());
    }

    public function test_is_bpi_returns_false_for_non_bpi(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'kadiv_fotografi']);
        $this->assertFalse($anggota->isBpi());
    }

    public function test_is_kadiv_returns_true_for_kadiv_positions(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'kadiv_fotografi']);
        $this->assertTrue($anggota->isKadiv());

        $anggota->update(['jabatan_struktural' => 'kadiv_videografi']);
        $this->assertTrue($anggota->fresh()->isKadiv());
    }

    public function test_is_kadiv_returns_false_for_non_kadiv(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'staf']);
        $this->assertFalse($anggota->isKadiv());
    }

    public function test_is_kanit_returns_true_for_kanit_positions(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'kanit_kominfo']);
        $this->assertTrue($anggota->isKanit());
    }

    public function test_is_kanit_returns_false_for_non_kanit(): void
    {
        $anggota = Anggota::factory()->create(['jabatan_struktural' => 'anggota']);
        $this->assertFalse($anggota->isKanit());
    }
}
