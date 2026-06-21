<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Authorization Matrix Test (SRS Section 5)
 *
 * Verifies that every controller endpoint enforces the correct
 * Spatie permission via $this->authorize() calls added during audit.
 */
class AuthorizationMatrixTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed roles & permissions before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);
    }

    /**
     * Create and return an Anggota with the given role.
     */
    private function createAnggotaWithRole(string $role, array $overrides = []): Anggota
    {
        $jabatanMap = [
            'ketua_umum'          => 'ketua_umum',
            'wakil_ketua_umum'    => 'wakil_ketua_umum',
            'sekretaris_umum_1'   => 'sekretaris_umum_1',
            'sekretaris_umum_2'   => 'sekretaris_umum_2',
            'bendahara_umum_1'    => 'bendahara_umum_1',
            'bendahara_umum_2'    => 'bendahara_umum_2',
            'kadiv_fotografi'     => 'kadiv_fotografi',
            'kadiv_pers_penyiaran' => 'kadiv_pers_penyiaran',
            'kadiv_videografi'    => 'kadiv_videografi',
            'kanit_kominfo'       => 'kanit_kominfo',
            'kanit_redaksi'       => 'kanit_redaksi',
            'kanit_inventory'     => 'kanit_inventory',
            'staf'                => 'staf',
            'anggota_aktif'       => 'anggota',
            'anggota_pasif'       => 'anggota',
        ];

        static $counter = 0;
        $counter++;

        $anggota = Anggota::create(array_merge([
            'nim'                 => '220000' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'nama_lengkap'        => 'Test User ' . $role,
            'email'               => "test.{$role}.{$counter}@polnes.ac.id",
            'password'            => Hash::make('password123'),
            'tanggal_lahir'       => '2000-01-15',
            'tempat_lahir'        => 'Samarinda',
            'jenis_kelamin'       => 'L',
            'no_hp'               => '08' . str_pad($counter, 10, '0', STR_PAD_LEFT),
            'program_studi'       => 'Teknik Informatika',
            'jurusan'             => 'Teknologi Informasi',
            'divisi'              => 'kominfo',
            'jabatan_struktural'  => $jabatanMap[$role] ?? 'anggota',
            'status_keanggotaan'  => $role === 'anggota_pasif' ? 'pasif' : 'aktif',
            'tanggal_bergabung'   => '2023-01-01',
            'is_first_login'      => false,
        ], $overrides));

        $anggota->assignRole($role);

        return $anggota;
    }

    /**
     * Create a dummy anggota for testing CRUD operations on.
     */
    private function createDummyAnggota(): Anggota
    {
        static $dummyCounter = 0;
        $dummyCounter++;

        return Anggota::create([
            'nim'                 => '230000' . str_pad($dummyCounter, 4, '0', STR_PAD_LEFT),
            'nama_lengkap'        => 'Dummy Anggota ' . $dummyCounter,
            'email'               => "dummy.{$dummyCounter}@polnes.ac.id",
            'password'            => Hash::make('password123'),
            'tanggal_lahir'       => '2001-06-15',
            'tempat_lahir'        => 'Samarinda',
            'jenis_kelamin'       => 'P',
            'no_hp'               => '08' . str_pad($dummyCounter + 100, 10, '0', STR_PAD_LEFT),
            'program_studi'       => 'Teknik Informatika',
            'jurusan'             => 'Teknologi Informasi',
            'divisi'              => 'redaksi',
            'jabatan_struktural'  => 'anggota',
            'status_keanggotaan'  => 'aktif',
            'tanggal_bergabung'   => '2023-09-01',
            'is_first_login'      => false,
        ]);
    }

    // ====================================================================
    // ANGGOTA (FR-02) — requires organisasi.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_anggota_index(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('anggota.index'));
        $response->assertStatus(200);
    }

    public function test_ketua_umum_can_create_anggota(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('anggota.create'));
        $response->assertStatus(200);
    }

    public function test_ketua_umum_can_delete_anggota(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $dummy = $this->createDummyAnggota();

        $response = $this->actingAs($user)->delete(route('anggota.destroy', $dummy));
        $response->assertRedirect(route('anggota.index'));
    }

    public function test_anggota_pasif_cannot_access_anggota_index(): void
    {
        $user = $this->createAnggotaWithRole('anggota_pasif');
        $response = $this->actingAs($user)->get(route('anggota.index'));
        $response->assertStatus(403);
    }

    public function test_anggota_pasif_cannot_create_anggota(): void
    {
        $user = $this->createAnggotaWithRole('anggota_pasif');
        $response = $this->actingAs($user)->get(route('anggota.create'));
        $response->assertStatus(403);
    }

    public function test_anggota_pasif_cannot_delete_anggota(): void
    {
        $user = $this->createAnggotaWithRole('anggota_pasif');
        $dummy = $this->createDummyAnggota();
        $response = $this->actingAs($user)->delete(route('anggota.destroy', $dummy));
        $response->assertStatus(403);
    }

    public function test_anggota_aktif_cannot_create_anggota(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('anggota.create'));
        $response->assertStatus(403);
    }

    public function test_staf_cannot_delete_anggota(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $dummy = $this->createDummyAnggota();
        $response = $this->actingAs($user)->delete(route('anggota.destroy', $dummy));
        $response->assertStatus(403);
    }

    // ====================================================================
    // NOTULENSI (FR-04) — requires notulensi.* permissions
    // ====================================================================

    public function test_sekretaris_can_access_notulensi(): void
    {
        $user = $this->createAnggotaWithRole('sekretaris_umum_1');
        $response = $this->actingAs($user)->get(route('notulensi.index'));
        $response->assertStatus(200);
    }

    public function test_sekretaris_can_create_notulensi(): void
    {
        $user = $this->createAnggotaWithRole('sekretaris_umum_1');
        $response = $this->actingAs($user)->get(route('notulensi.create'));
        $response->assertStatus(200);
    }

    public function test_anggota_pasif_cannot_access_notulensi(): void
    {
        $user = $this->createAnggotaWithRole('anggota_pasif');
        $response = $this->actingAs($user)->get(route('notulensi.index'));
        $response->assertStatus(403);
    }

    public function test_bendahara_cannot_create_notulensi(): void
    {
        $user = $this->createAnggotaWithRole('bendahara_umum_1');
        $response = $this->actingAs($user)->get(route('notulensi.create'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // ANGGARAN DIVISI (FR-07) — requires anggaran-divisi.* permissions
    // ====================================================================

    public function test_bendahara_can_access_anggaran_divisi(): void
    {
        $user = $this->createAnggotaWithRole('bendahara_umum_1');
        $response = $this->actingAs($user)->get(route('anggaran-divisi.index'));
        $response->assertStatus(200);
    }

    public function test_bendahara_can_create_anggaran_divisi(): void
    {
        $user = $this->createAnggotaWithRole('bendahara_umum_1');
        $response = $this->actingAs($user)->get(route('anggaran-divisi.create'));
        $response->assertStatus(200);
    }

    public function test_staf_cannot_access_anggaran_divisi(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $response = $this->actingAs($user)->get(route('anggaran-divisi.index'));
        $response->assertStatus(403);
    }

    public function test_anggota_aktif_cannot_access_anggaran_divisi(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('anggaran-divisi.index'));
        $response->assertStatus(403);
    }

    public function test_kadiv_cannot_create_anggaran_divisi(): void
    {
        $user = $this->createAnggotaWithRole('kadiv_fotografi');
        $response = $this->actingAs($user)->get(route('anggaran-divisi.create'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // EVENT (FR-09) — requires event.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_event(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('event.index'));
        $response->assertStatus(200);
    }

    public function test_anggota_aktif_can_view_event(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('event.index'));
        $response->assertStatus(200);
    }

    public function test_anggota_aktif_cannot_create_event(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('event.create'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // JADWAL SHIFT (FR-06) — requires jadwal-shift.* permissions
    // ====================================================================

    public function test_kanit_can_manage_jadwal_shift(): void
    {
        $user = $this->createAnggotaWithRole('kanit_kominfo');
        $response = $this->actingAs($user)->get(route('jadwal.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('jadwal.create'));
        $response->assertStatus(200);
    }

    public function test_anggota_aktif_cannot_access_jadwal(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('jadwal.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // NASKAH REDAKSI (FR-08) — requires naskah-redaksi.* permissions
    // ====================================================================

    public function test_kanit_redaksi_can_access_naskah(): void
    {
        $user = $this->createAnggotaWithRole('kanit_redaksi');
        $response = $this->actingAs($user)->get(route('naskah.index'));
        $response->assertStatus(200);
    }

    public function test_kanit_redaksi_can_create_naskah(): void
    {
        $user = $this->createAnggotaWithRole('kanit_redaksi');
        $response = $this->actingAs($user)->get(route('naskah.create'));
        $response->assertStatus(200);
    }

    public function test_kadiv_cannot_access_naskah(): void
    {
        $user = $this->createAnggotaWithRole('kadiv_fotografi');
        $response = $this->actingAs($user)->get(route('naskah.index'));
        $response->assertStatus(403);
    }

    public function test_bendahara_cannot_access_naskah(): void
    {
        $user = $this->createAnggotaWithRole('bendahara_umum_1');
        $response = $this->actingAs($user)->get(route('naskah.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // KEAMANAN (FR-10) — requires keamanan.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_login_history(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('keamanan.login-history'));
        $response->assertStatus(200);
    }

    public function test_staf_cannot_access_login_history(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $response = $this->actingAs($user)->get(route('keamanan.login-history'));
        $response->assertStatus(403);
    }

    public function test_anggota_cannot_access_activity_log(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('keamanan.activity-log'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // LAPORAN (FR-12) — requires laporan.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_laporan(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('laporan.index'));
        $response->assertStatus(200);
    }

    public function test_kadiv_can_view_laporan(): void
    {
        $user = $this->createAnggotaWithRole('kadiv_fotografi');
        $response = $this->actingAs($user)->get(route('laporan.index'));
        $response->assertStatus(200);
    }

    public function test_staf_cannot_access_laporan(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $response = $this->actingAs($user)->get(route('laporan.index'));
        $response->assertStatus(403);
    }

    public function test_anggota_pasif_cannot_access_laporan(): void
    {
        $user = $this->createAnggotaWithRole('anggota_pasif');
        $response = $this->actingAs($user)->get(route('laporan.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // PERGANTIAN (FR-17) — requires pergantian.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_pergantian(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('pergantian.index'));
        $response->assertStatus(200);
    }

    public function test_staf_cannot_access_pergantian(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $response = $this->actingAs($user)->get(route('pergantian.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // PERIODE (FR-16) — requires periode.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_periode(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('periode.index'));
        $response->assertStatus(200);
    }

    public function test_anggota_aktif_cannot_access_periode(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('periode.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // REKRUTMEN (FR-05) — requires rekrutmen.* permissions
    // ====================================================================

    public function test_ketua_umum_can_access_rekrutmen(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('rekrutmen.index'));
        $response->assertStatus(200);
    }

    public function test_kadiv_cannot_access_rekrutmen(): void
    {
        $user = $this->createAnggotaWithRole('kadiv_fotografi');
        $response = $this->actingAs($user)->get(route('rekrutmen.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // SURAT PERNYATAAN (FR-21) — mixed permissions
    // ====================================================================

    public function test_ketua_umum_can_access_surat_pernyataan(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('surat-pernyataan.index'));
        $response->assertStatus(200);
    }

    public function test_anggota_pasif_can_view_surat_pernyataan(): void
    {
        $user = $this->createAnggotaWithRole('anggota_pasif');
        $response = $this->actingAs($user)->get(route('surat-pernyataan.index'));
        $response->assertStatus(200);
    }

    public function test_anggota_aktif_can_view_surat_pernyataan(): void
    {
        $user = $this->createAnggotaWithRole('anggota_aktif');
        $response = $this->actingAs($user)->get(route('surat-pernyataan.index'));
        $response->assertStatus(200);
    }

    public function test_staf_cannot_access_surat_pernyataan(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $response = $this->actingAs($user)->get(route('surat-pernyataan.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // TEMPLATE KEPANITIAAN (FR-19) — requires template-panitia.*
    // ====================================================================

    public function test_ketua_umum_can_access_template(): void
    {
        $user = $this->createAnggotaWithRole('ketua_umum');
        $response = $this->actingAs($user)->get(route('template-kepanitiaan.index'));
        $response->assertStatus(200);
    }

    public function test_staf_cannot_access_template(): void
    {
        $user = $this->createAnggotaWithRole('staf');
        $response = $this->actingAs($user)->get(route('template-kepanitiaan.index'));
        $response->assertStatus(403);
    }

    // ====================================================================
    // UNAUTHENTICATED REDIRECT
    // ====================================================================

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('anggota.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }
}
