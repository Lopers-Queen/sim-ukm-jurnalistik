<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\AnggotaPanitia;
use App\Models\DivisiPanitia;
use App\Models\Event;
use App\Models\PeriodeKepengurusan;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Event & Kepanitiaan (Blackbox)
 */
class EventCrudTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $ketuaUmum;
    private PeriodeKepengurusan $periode;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AssignPermissionToRoleSeeder::class);

        $this->ketuaUmum = Anggota::factory()->sudahLogin()->create([
            'jabatan_struktural' => 'ketua_umum',
        ]);
        $this->ketuaUmum->assignRole('ketua_umum');

        $this->periode = PeriodeKepengurusan::create([
            'nama_periode'   => 'Test Periode',
            'tahun_mulai'    => 2025,
            'tahun_selesai'  => 2026,
            'tanggal_mulai'  => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'         => 'aktif',
        ]);
    }

    private function validEventData(): array
    {
        return [
            'nama_event'      => 'Workshop Fotografi 2025',
            'deskripsi'       => 'Workshop fotografi dasar',
            'tanggal_mulai'   => '2025-06-01',
            'tanggal_selesai' => '2025-06-02',
            'lokasi'          => 'Gedung UKM',
            'status'          => 'direncanakan',
            'pic_id'          => $this->ketuaUmum->id,
            'anggaran_total'  => 5000000,
            'periode_id'      => $this->periode->id,
        ];
    }

    // ====================================================================
    // CRUD EVENT
    // ====================================================================

    public function test_event_index_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('event.index'));
        $response->assertStatus(200);
    }

    public function test_create_event_page_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('event.create'));
        $response->assertStatus(200);
    }

    public function test_store_event_with_valid_data(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->post(route('event.store'), $this->validEventData());

        $response->assertRedirect();
        $this->assertDatabaseHas('event', ['nama_event' => 'Workshop Fotografi 2025']);
    }

    public function test_store_event_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('event.create'))
            ->post(route('event.store'), []);

        $response->assertSessionHasErrors(['nama_event', 'tanggal_mulai', 'status']);
    }

    public function test_store_event_fails_with_invalid_status(): void
    {
        $data = $this->validEventData();
        $data['status'] = 'invalid_status';

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('event.create'))
            ->post(route('event.store'), $data);

        $response->assertSessionHasErrors('status');
    }

    public function test_show_event_accessible(): void
    {
        $event = Event::create($this->validEventData());

        $response = $this->actingAs($this->ketuaUmum)->get(route('event.show', $event));
        $response->assertStatus(200);
    }

    public function test_update_event(): void
    {
        $event = Event::create($this->validEventData());

        $response = $this->actingAs($this->ketuaUmum)->put(route('event.update', $event), array_merge(
            $this->validEventData(),
            ['nama_event' => 'Updated Event Name']
        ));

        $response->assertRedirect();
        $this->assertEquals('Updated Event Name', $event->fresh()->nama_event);
    }

    public function test_delete_event(): void
    {
        $event = Event::create($this->validEventData());

        $response = $this->actingAs($this->ketuaUmum)->delete(route('event.destroy', $event));

        $response->assertRedirect(route('event.index'));
        $this->assertSoftDeleted('event', ['id' => $event->id]);
    }

    // ====================================================================
    // KEPANITIAAN
    // ====================================================================

    public function test_add_divisi_panitia(): void
    {
        $event = Event::create($this->validEventData());

        $response = $this->actingAs($this->ketuaUmum)
            ->post(route('event.add-divisi', $event), [
                'nama_divisi' => 'Divisi Acara',
                'deskripsi'   => 'Mengurus acara',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('divisi_panitia', [
            'event_id'    => $event->id,
            'nama_divisi' => 'Divisi Acara',
        ]);
    }

    public function test_assign_panitia(): void
    {
        $event = Event::create($this->validEventData());
        $divisi = DivisiPanitia::create([
            'event_id'    => $event->id,
            'nama_divisi' => 'Divisi Acara',
        ]);
        $anggota = Anggota::factory()->sudahLogin()->create();

        $response = $this->actingAs($this->ketuaUmum)
            ->post(route('event.assign-panitia', $event), [
                'anggota_id'        => $anggota->id,
                'divisi_panitia_id' => $divisi->id,
                'jabatan_panitia'   => 'Koordinator',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('anggota_panitia', [
            'event_id'        => $event->id,
            'anggota_id'      => $anggota->id,
            'jabatan_panitia' => 'Koordinator',
        ]);
    }

    public function test_duplicate_assign_panitia_rejected(): void
    {
        $event = Event::create($this->validEventData());
        $divisi = DivisiPanitia::create([
            'event_id'    => $event->id,
            'nama_divisi' => 'Divisi Acara',
        ]);
        $anggota = Anggota::factory()->sudahLogin()->create();

        // First assign
        AnggotaPanitia::create([
            'event_id'          => $event->id,
            'anggota_id'        => $anggota->id,
            'divisi_panitia_id' => $divisi->id,
            'jabatan_panitia'   => 'Anggota',
        ]);

        // Duplicate assign
        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('event.show', $event))
            ->post(route('event.assign-panitia', $event), [
                'anggota_id'        => $anggota->id,
                'divisi_panitia_id' => $divisi->id,
                'jabatan_panitia'   => 'Koordinator',
            ]);

        $response->assertSessionHas('error');
    }

    public function test_remove_panitia(): void
    {
        $event = Event::create($this->validEventData());
        $divisi = DivisiPanitia::create([
            'event_id'    => $event->id,
            'nama_divisi' => 'Divisi Acara',
        ]);
        $anggota = Anggota::factory()->sudahLogin()->create();

        $panitia = AnggotaPanitia::create([
            'event_id'          => $event->id,
            'anggota_id'        => $anggota->id,
            'divisi_panitia_id' => $divisi->id,
            'jabatan_panitia'   => 'Anggota',
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->delete(route('event.remove-panitia', [$event, $panitia]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('anggota_panitia', ['id' => $panitia->id]);
    }

    // ====================================================================
    // SEARCH & FILTER
    // ====================================================================

    public function test_search_event(): void
    {
        Event::create(array_merge($this->validEventData(), ['nama_event' => 'Seminar ABC']));
        Event::create(array_merge($this->validEventData(), ['nama_event' => 'Workshop XYZ']));

        $response = $this->actingAs($this->ketuaUmum)->get(route('event.index', ['search' => 'Seminar']));

        $response->assertStatus(200);
        $response->assertSee('Seminar ABC');
        $response->assertDontSee('Workshop XYZ');
    }

    // ====================================================================
    // UNAUTHORIZED
    // ====================================================================

    public function test_anggota_aktif_cannot_create_event(): void
    {
        $anggota = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'anggota']);
        $anggota->assignRole('anggota_aktif');

        $response = $this->actingAs($anggota)->get(route('event.create'));
        $response->assertStatus(403);
    }
}
