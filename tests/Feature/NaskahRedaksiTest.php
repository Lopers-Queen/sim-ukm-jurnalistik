<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\NaskahRedaksi;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Naskah Redaksi Workflow (Blackbox)
 */
class NaskahRedaksiTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $kanitRedaksi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->kanitRedaksi = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'kanit_redaksi',
        ]);
        $this->kanitRedaksi->assignRole('kanit_redaksi');
    }

    private function createNaskah(array $overrides = []): NaskahRedaksi
    {
        return NaskahRedaksi::create(array_merge([
            'judul'      => 'Artikel Uji',
            'konten'     => 'Konten artikel untuk pengujian.',
            'penulis_id' => $this->kanitRedaksi->id,
            'status'     => 'draft',
            'kategori'   => 'berita',
        ], $overrides));
    }

    public function test_create_naskah_stores_as_draft(): void
    {
        $response = $this->actingAs($this->kanitRedaksi)
            ->post(route('naskah.store'), [
                'judul'    => 'Artikel Baru',
                'konten'   => 'Konten baru',
                'kategori' => 'berita',
            ]);

        $response->assertRedirect(route('naskah.index'));

        $naskah = NaskahRedaksi::where('judul', 'Artikel Baru')->first();
        $this->assertEquals('draft', $naskah->status);
        $this->assertEquals($this->kanitRedaksi->id, $naskah->penulis_id);
    }

    public function test_submit_review_changes_status(): void
    {
        $naskah = $this->createNaskah(['status' => 'draft']);

        $response = $this->actingAs($this->kanitRedaksi)
            ->post(route('naskah.submit-review', $naskah));

        $response->assertRedirect(route('naskah.show', $naskah));
        $this->assertEquals('review', $naskah->fresh()->status);
    }

    public function test_approve_naskah_changes_status(): void
    {
        $naskah = $this->createNaskah(['status' => 'review']);

        $response = $this->actingAs($this->kanitRedaksi)
            ->post(route('naskah.approve', $naskah), [
                'catatan_editor' => 'Bagus, disetujui.',
            ]);

        $response->assertRedirect(route('naskah.show', $naskah));
        $naskah->refresh();
        $this->assertEquals('disetujui', $naskah->status);
        $this->assertEquals($this->kanitRedaksi->id, $naskah->editor_id);
    }

    public function test_revisi_naskah_requires_catatan(): void
    {
        $naskah = $this->createNaskah(['status' => 'review']);

        $response = $this->actingAs($this->kanitRedaksi)
            ->from(route('naskah.show', $naskah))
            ->post(route('naskah.revisi', $naskah), [
                'catatan_editor' => '',
            ]);

        $response->assertSessionHasErrors('catatan_editor');
    }

    public function test_revisi_naskah_with_valid_catatan(): void
    {
        $naskah = $this->createNaskah(['status' => 'review']);

        $response = $this->actingAs($this->kanitRedaksi)
            ->post(route('naskah.revisi', $naskah), [
                'catatan_editor' => 'Perlu perbaikan pada bagian pendahuluan artikel ini.',
            ]);

        $response->assertRedirect(route('naskah.show', $naskah));
        $this->assertEquals('revisi', $naskah->fresh()->status);
    }

    public function test_publish_naskah(): void
    {
        $naskah = $this->createNaskah(['status' => 'disetujui']);

        $response = $this->actingAs($this->kanitRedaksi)
            ->post(route('naskah.publish', $naskah));

        $response->assertRedirect(route('naskah.show', $naskah));
        $naskah->refresh();
        $this->assertEquals('published', $naskah->status);
        $this->assertNotNull($naskah->tanggal_publish);
    }

    public function test_delete_naskah(): void
    {
        $naskah = $this->createNaskah();

        $response = $this->actingAs($this->kanitRedaksi)->delete(route('naskah.destroy', $naskah));

        $response->assertRedirect(route('naskah.index'));
        $this->assertSoftDeleted('naskah_redaksi', ['id' => $naskah->id]);
    }

    public function test_naskah_index_accessible(): void
    {
        $response = $this->actingAs($this->kanitRedaksi)->get(route('naskah.index'));
        $response->assertStatus(200);
    }

    public function test_unauthorized_cannot_access_naskah(): void
    {
        $bendahara = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'bendahara_umum_1']);
        $bendahara->assignRole('bendahara_umum_1');

        $response = $this->actingAs($bendahara)->get(route('naskah.index'));
        $response->assertStatus(403);
    }

    public function test_store_naskah_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->kanitRedaksi)
            ->from(route('naskah.create'))
            ->post(route('naskah.store'), []);

        $response->assertSessionHasErrors(['judul', 'konten']);
    }
}
