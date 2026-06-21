<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Notulensi;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Notulensi CRUD (Blackbox)
 */
class NotulensiCrudTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $sekretaris;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->sekretaris = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'sekretaris_umum_1',
        ]);
        $this->sekretaris->assignRole('sekretaris_umum_1');
    }

    private function validData(): array
    {
        return [
            'judul'         => 'Rapat Rutin Bulanan',
            'tanggal_rapat' => '2025-06-01',
            'lokasi'        => 'Sekretariat UKM',
            'jenis_rapat'   => 'rapat_rutin',
            'isi_notulensi' => 'Pembahasan program kerja bulan Juni',
        ];
    }

    public function test_notulensi_index_accessible(): void
    {
        $response = $this->actingAs($this->sekretaris)->get(route('notulensi.index'));
        $response->assertStatus(200);
    }

    public function test_store_notulensi_valid(): void
    {
        $response = $this->actingAs($this->sekretaris)
            ->post(route('notulensi.store'), $this->validData());

        $response->assertRedirect(route('notulensi.index'));
        $this->assertDatabaseHas('notulensi', ['judul' => 'Rapat Rutin Bulanan']);
    }

    public function test_store_notulensi_sets_pencatat_id(): void
    {
        $this->actingAs($this->sekretaris)
            ->post(route('notulensi.store'), $this->validData());

        $notulensi = Notulensi::first();
        $this->assertEquals($this->sekretaris->id, $notulensi->pencatat_id);
    }

    public function test_store_notulensi_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->sekretaris)
            ->from(route('notulensi.create'))
            ->post(route('notulensi.store'), []);

        $response->assertSessionHasErrors(['judul', 'tanggal_rapat', 'jenis_rapat', 'isi_notulensi']);
    }

    public function test_store_notulensi_fails_with_invalid_jenis_rapat(): void
    {
        $data = $this->validData();
        $data['jenis_rapat'] = 'rapat_tidakk_ada';

        $response = $this->actingAs($this->sekretaris)
            ->from(route('notulensi.create'))
            ->post(route('notulensi.store'), $data);

        $response->assertSessionHasErrors('jenis_rapat');
    }

    public function test_show_notulensi(): void
    {
        $data = $this->validData();
        $data['pencatat_id'] = $this->sekretaris->id;
        $notulensi = Notulensi::create($data);

        $response = $this->actingAs($this->sekretaris)->get(route('notulensi.show', $notulensi));
        $response->assertStatus(200);
    }

    public function test_update_notulensi(): void
    {
        $data = $this->validData();
        $data['pencatat_id'] = $this->sekretaris->id;
        $notulensi = Notulensi::create($data);

        $response = $this->actingAs($this->sekretaris)->put(route('notulensi.update', $notulensi), array_merge(
            $this->validData(),
            ['judul' => 'Updated Judul']
        ));

        $response->assertRedirect(route('notulensi.index'));
        $this->assertEquals('Updated Judul', $notulensi->fresh()->judul);
    }

    public function test_delete_notulensi(): void
    {
        $data = $this->validData();
        $data['pencatat_id'] = $this->sekretaris->id;
        $notulensi = Notulensi::create($data);

        $response = $this->actingAs($this->sekretaris)->delete(route('notulensi.destroy', $notulensi));

        $response->assertRedirect(route('notulensi.index'));
        $this->assertSoftDeleted('notulensi', ['id' => $notulensi->id]);
    }

    public function test_search_notulensi(): void
    {
        $data1 = $this->validData();
        $data1['pencatat_id'] = $this->sekretaris->id;
        $data1['judul'] = 'Rapat Penting';
        Notulensi::create($data1);

        $data2 = $this->validData();
        $data2['pencatat_id'] = $this->sekretaris->id;
        $data2['judul'] = 'Evaluasi Bulanan';
        Notulensi::create($data2);

        $response = $this->actingAs($this->sekretaris)->get(route('notulensi.index', ['search' => 'Penting']));
        $response->assertSee('Rapat Penting');
        $response->assertDontSee('Evaluasi Bulanan');
    }

    public function test_unauthorized_cannot_access_notulensi(): void
    {
        $bendahara = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'bendahara_umum_1']);
        $bendahara->assignRole('bendahara_umum_1');

        $response = $this->actingAs($bendahara)->get(route('notulensi.index'));
        $response->assertStatus(403);
    }
}
