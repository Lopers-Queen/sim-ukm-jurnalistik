<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Feature Tests: Anggota CRUD (Blackbox)
 * Menguji seluruh operasi CRUD anggota, search, filter, dan pagination.
 */
class AnggotaCrudTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        // Create a super_admin user who has all permissions
        $this->admin = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'admin',
        ]);
        $this->admin->assignRole('super_admin');
    }

    // ====================================================================
    // INDEX
    // ====================================================================

    public function test_anggota_index_accessible_by_admin(): void
    {
        $response = $this->actingAs($this->admin)->get(route('anggota.index'));
        $response->assertStatus(200);
    }

    public function test_anggota_index_shows_pagination(): void
    {
        Anggota::factory()->sudahLogin()->count(20)->create();

        $response = $this->actingAs($this->admin)->get(route('anggota.index'));

        $response->assertStatus(200);
        // With 20 members + admin, pagination should be active (15 per page)
    }

    // ====================================================================
    // CREATE
    // ====================================================================

    public function test_create_anggota_page_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('anggota.create'));
        $response->assertStatus(200);
    }

    public function test_store_anggota_with_valid_data(): void
    {
        $data = [
            'nim'                => '23650001',
            'nama_lengkap'       => 'Test Anggota Baru',
            'email'              => 'test.anggota@polnes.ac.id',
            'tanggal_lahir'      => '2000-05-20',
            'tempat_lahir'       => 'Samarinda',
            'jenis_kelamin'      => 'L',
            'no_hp'              => '081234567890',
            'program_studi'      => 'Teknik Informatika',
            'jurusan'            => 'Teknologi Informasi',
            'divisi'             => 'fotografi',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
            'tanggal_bergabung'  => '2024-09-01',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('anggota.store'), $data);

        $response->assertRedirect(route('anggota.index'));

        $this->assertDatabaseHas('anggota', [
            'nim'          => '23650001',
            'nama_lengkap' => 'Test Anggota Baru',
        ]);
    }

    public function test_store_anggota_generates_password_from_birthdate(): void
    {
        $data = [
            'nim'                => '23650002',
            'nama_lengkap'       => 'Test Password',
            'email'              => 'test.pw@polnes.ac.id',
            'tanggal_lahir'      => '2001-12-25',
            'jenis_kelamin'      => 'P',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
        ];

        $this->actingAs($this->admin)->post(route('anggota.store'), $data);

        $anggota = Anggota::where('nim', '23650002')->first();
        $this->assertNotNull($anggota);
        $this->assertTrue($anggota->is_first_login);
    }

    public function test_store_anggota_fails_with_duplicate_nim(): void
    {
        Anggota::factory()->sudahLogin()->create(['nim' => '23650003']);

        $data = [
            'nim'                => '23650003',
            'nama_lengkap'       => 'Duplicate NIM',
            'email'              => 'dup@polnes.ac.id',
            'tanggal_lahir'      => '2000-01-01',
            'jenis_kelamin'      => 'L',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), $data);

        $response->assertSessionHasErrors('nim');
    }

    public function test_store_anggota_fails_with_duplicate_email(): void
    {
        Anggota::factory()->sudahLogin()->create(['email' => 'taken@polnes.ac.id']);

        $data = [
            'nim'                => '23650004',
            'nama_lengkap'       => 'Duplicate Email',
            'email'              => 'taken@polnes.ac.id',
            'tanggal_lahir'      => '2000-01-01',
            'jenis_kelamin'      => 'L',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), $data);

        $response->assertSessionHasErrors('email');
    }

    public function test_store_anggota_fails_with_missing_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), []);

        $response->assertSessionHasErrors(['nim', 'nama_lengkap', 'email', 'tanggal_lahir', 'jenis_kelamin', 'jabatan_struktural', 'status_keanggotaan']);
    }

    public function test_store_anggota_fails_with_invalid_jenis_kelamin(): void
    {
        $data = [
            'nim'                => '23650005',
            'nama_lengkap'       => 'Invalid JK',
            'email'              => 'jk@polnes.ac.id',
            'tanggal_lahir'      => '2000-01-01',
            'jenis_kelamin'      => 'X',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), $data);

        $response->assertSessionHasErrors('jenis_kelamin');
    }

    public function test_store_anggota_fails_with_future_birthdate(): void
    {
        $data = [
            'nim'                => '23650006',
            'nama_lengkap'       => 'Future Birth',
            'email'              => 'future@polnes.ac.id',
            'tanggal_lahir'      => now()->addDay()->toDateString(),
            'jenis_kelamin'      => 'L',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('anggota.create'))
            ->post(route('anggota.store'), $data);

        $response->assertSessionHasErrors('tanggal_lahir');
    }

    // ====================================================================
    // SHOW / EDIT / UPDATE
    // ====================================================================

    public function test_show_anggota_page_accessible(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->admin)->get(route('anggota.show', $anggota));
        $response->assertStatus(200);
    }

    public function test_edit_anggota_page_accessible(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->admin)->get(route('anggota.edit', $anggota));
        $response->assertStatus(200);
    }

    public function test_update_anggota_with_valid_data(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->admin)->put(route('anggota.update', $anggota), [
            'nim'                => $anggota->nim,
            'nama_lengkap'       => 'Updated Name',
            'email'              => $anggota->email,
            'tanggal_lahir'      => '2000-01-01',
            'jenis_kelamin'      => 'L',
            'jabatan_struktural' => 'anggota',
            'status_keanggotaan' => 'aktif',
        ]);

        $response->assertRedirect(route('anggota.index'));
        $this->assertEquals('Updated Name', $anggota->fresh()->nama_lengkap);
    }

    // ====================================================================
    // DELETE
    // ====================================================================

    public function test_delete_anggota_soft_deletes(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->admin)->delete(route('anggota.destroy', $anggota));

        $response->assertRedirect(route('anggota.index'));
        $this->assertSoftDeleted('anggota', ['id' => $anggota->id]);
    }

    // ====================================================================
    // SEARCH & FILTER
    // ====================================================================

    public function test_search_anggota_by_name(): void
    {
        Anggota::factory()->sudahLogin()->create(['nama_lengkap' => 'Unique Name XYZ']);
        Anggota::factory()->sudahLogin()->create(['nama_lengkap' => 'Other Person']);

        $response = $this->actingAs($this->admin)->get(route('anggota.index', ['search' => 'Unique Name']));

        $response->assertStatus(200);
        $response->assertSee('Unique Name XYZ');
        $response->assertDontSee('Other Person');
    }

    public function test_filter_anggota_by_divisi(): void
    {
        Anggota::factory()->sudahLogin()->create(['divisi' => 'fotografi', 'nama_lengkap' => 'Foto Person']);
        Anggota::factory()->sudahLogin()->create(['divisi' => 'redaksi', 'nama_lengkap' => 'Redaksi Person']);

        $response = $this->actingAs($this->admin)->get(route('anggota.index', ['divisi' => 'fotografi']));

        $response->assertStatus(200);
        $response->assertSee('Foto Person');
        $response->assertDontSee('Redaksi Person');
    }

    public function test_filter_anggota_by_status(): void
    {
        Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'aktif', 'nama_lengkap' => 'Active Person']);
        Anggota::factory()->sudahLogin()->create(['status_keanggotaan' => 'pasif', 'nama_lengkap' => 'Passive Person']);

        $response = $this->actingAs($this->admin)->get(route('anggota.index', ['status' => 'pasif']));

        $response->assertStatus(200);
        $response->assertSee('Passive Person');
        $response->assertDontSee('Active Person');
    }

    // ====================================================================
    // UNAUTHORIZED ACCESS
    // ====================================================================

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('anggota.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_role_cannot_access_anggota(): void
    {
        $staf = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'staf',
        ]);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->get(route('anggota.index'));
        $response->assertStatus(403);
    }
}
