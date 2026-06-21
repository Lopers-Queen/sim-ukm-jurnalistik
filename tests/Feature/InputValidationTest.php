<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Input Validation (Blackbox)
 * Menguji validasi input pada berbagai form request.
 */
class InputValidationTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->admin = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'admin',
        ]);
        $this->admin->assignRole('super_admin');
    }

    // ====================================================================
    // StoreAnggotaRequest
    // ====================================================================

    public function test_anggota_nim_max_20_chars(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), [
                'nim'                => str_repeat('A', 21),
                'nama_lengkap'       => 'Test',
                'email'              => 'test@test.com',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'aktif',
            ]);

        $response->assertSessionHasErrors('nim');
    }

    public function test_anggota_invalid_email(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), [
                'nim'                => '12345678',
                'nama_lengkap'       => 'Test',
                'email'              => 'not-an-email',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'aktif',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_anggota_invalid_divisi(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), [
                'nim'                => '12345678',
                'nama_lengkap'       => 'Test',
                'email'              => 'test@test.com',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'divisi'             => 'invalid_divisi',
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'aktif',
            ]);

        $response->assertSessionHasErrors('divisi');
    }

    public function test_anggota_invalid_jabatan(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), [
                'nim'                => '12345678',
                'nama_lengkap'       => 'Test',
                'email'              => 'test@test.com',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'jabatan_struktural' => 'invalid_jabatan',
                'status_keanggotaan' => 'aktif',
            ]);

        $response->assertSessionHasErrors('jabatan_struktural');
    }

    public function test_anggota_invalid_status(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), [
                'nim'                => '12345678',
                'nama_lengkap'       => 'Test',
                'email'              => 'test@test.com',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'invalid',
            ]);

        $response->assertSessionHasErrors('status_keanggotaan');
    }

    // ====================================================================
    // StoreEventRequest
    // ====================================================================

    public function test_event_nama_required(): void
    {
        $ketua = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'ketua_umum']);
        $ketua->assignRole('ketua_umum');

        $response = $this->actingAs($ketua)
            ->from(route('event.create'))
            ->post(route('event.store'), [
                'tanggal_mulai' => '2025-06-01',
                'status'        => 'draft',
            ]);

        $response->assertSessionHasErrors('nama_event');
    }

    public function test_event_tanggal_selesai_must_be_after_tanggal_mulai(): void
    {
        $ketua = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'ketua_umum']);
        $ketua->assignRole('ketua_umum');

        $response = $this->actingAs($ketua)
            ->from(route('event.create'))
            ->post(route('event.store'), [
                'nama_event'      => 'Test Event',
                'tanggal_mulai'   => '2025-06-10',
                'tanggal_selesai' => '2025-06-05',
                'status'          => 'draft',
            ]);

        $response->assertSessionHasErrors('tanggal_selesai');
    }

    public function test_event_anggaran_must_be_numeric_non_negative(): void
    {
        $ketua = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'ketua_umum']);
        $ketua->assignRole('ketua_umum');

        $response = $this->actingAs($ketua)
            ->from(route('event.create'))
            ->post(route('event.store'), [
                'nama_event'     => 'Test Event',
                'tanggal_mulai'  => '2025-06-01',
                'status'         => 'draft',
                'anggaran_total' => -1000,
            ]);

        $response->assertSessionHasErrors('anggaran_total');
    }

    // ====================================================================
    // StoreNaskahRequest
    // ====================================================================

    public function test_naskah_judul_max_255_chars(): void
    {
        $kanit = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'kanit_redaksi']);
        $kanit->assignRole('kanit_redaksi');

        $response = $this->actingAs($kanit)
            ->from(route('naskah.create'))
            ->post(route('naskah.store'), [
                'judul'  => str_repeat('A', 256),
                'konten' => 'Konten valid',
            ]);

        $response->assertSessionHasErrors('judul');
    }

    public function test_naskah_konten_required(): void
    {
        $kanit = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'kanit_redaksi']);
        $kanit->assignRole('kanit_redaksi');

        $response = $this->actingAs($kanit)
            ->from(route('naskah.create'))
            ->post(route('naskah.store'), [
                'judul' => 'Judul valid',
            ]);

        $response->assertSessionHasErrors('konten');
    }

    // ====================================================================
    // FILE UPLOAD VALIDATION
    // ====================================================================

    public function test_foto_profil_must_be_image(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), [
                'nim'                => '12345678',
                'nama_lengkap'       => 'Test',
                'email'              => 'test@test.com',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'aktif',
                'foto_profil'        => 'not-a-file',
            ]);

        // foto_profil expects an uploaded file, passing a string should fail
        $response->assertSessionHasErrors('foto_profil');
    }

    // ====================================================================
    // NOTULENSI VALIDATION
    // ====================================================================

    public function test_notulensi_judul_max_255_chars(): void
    {
        $sekretaris = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'sekretaris_umum_1']);
        $sekretaris->assignRole('sekretaris_umum_1');

        $response = $this->actingAs($sekretaris)
            ->from(route('notulensi.create'))
            ->post(route('notulensi.store'), [
                'judul'         => str_repeat('X', 256),
                'tanggal_rapat' => '2025-06-01',
                'jenis_rapat'   => 'rapat_rutin',
                'isi_notulensi' => 'Isi notulensi',
            ]);

        $response->assertSessionHasErrors('judul');
    }

    // ====================================================================
    // EDGE CASES
    // ====================================================================

    public function test_empty_string_treated_as_null_for_nullable_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('anggota.store'), [
                'nim'                => '12345678',
                'nama_lengkap'       => 'Test',
                'email'              => 'test@test.com',
                'tanggal_lahir'      => '2000-01-01',
                'jenis_kelamin'      => 'L',
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'aktif',
                'tempat_lahir'       => '',
                'no_hp'              => '',
            ]);

        $response->assertRedirect(route('anggota.index'));
    }
}
