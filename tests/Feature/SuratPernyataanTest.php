<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Event;
use App\Models\PeriodeKepengurusan;
use App\Models\SuratPernyataan;
use Database\Seeders\AssignPermissionToRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests: Surat Pernyataan Workflow (Blackbox)
 */
class SuratPernyataanTest extends TestCase
{
    use RefreshDatabase;

    private Anggota $ketuaUmum;
    private Anggota $anggota;
    private Event $event;

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

        $this->anggota = Anggota::factory()->sudahLogin()->create();
        $this->anggota->assignRole('anggota_pasif');

        $periode = PeriodeKepengurusan::create([
            'nama_periode'   => 'Test',
            'tahun_mulai'    => 2025,
            'tahun_selesai'  => 2026,
            'tanggal_mulai'  => '2025-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status'         => 'aktif',
        ]);

        $this->event = Event::create([
            'nama_event'    => 'Test Event',
            'tanggal_mulai' => '2025-06-01',
            'status'        => 'direncanakan',
            'pic_id'        => $this->ketuaUmum->id,
            'periode_id'    => $periode->id,
        ]);
    }

    // ====================================================================
    // INDEX & SHOW
    // ====================================================================

    public function test_surat_pernyataan_index_accessible(): void
    {
        $response = $this->actingAs($this->ketuaUmum)->get(route('surat-pernyataan.index'));
        $response->assertStatus(200);
    }

    public function test_surat_pernyataan_show_accessible(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'pending_ttd',
        ]);

        $response = $this->actingAs($this->ketuaUmum)->get(route('surat-pernyataan.show', $surat));
        $response->assertStatus(200);
    }

    // ====================================================================
    // GENERATE
    // ====================================================================

    public function test_generate_surat_pernyataan(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.index'))
            ->post(route('surat-pernyataan.generate'), [
                'anggota_id' => $this->anggota->id,
                'event_id'   => $this->event->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('surat_pernyataan', [
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'pending_ttd',
        ]);
    }

    public function test_generate_requires_valid_anggota(): void
    {
        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.index'))
            ->post(route('surat-pernyataan.generate'), [
                'anggota_id' => 99999,
                'event_id'   => $this->event->id,
            ]);

        $response->assertSessionHasErrors('anggota_id');
    }

    // ====================================================================
    // UPLOAD TTD
    // ====================================================================

    public function test_upload_ttd_requires_image(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'pending_ttd',
        ]);

        $response = $this->actingAs($this->anggota)
            ->from(route('surat-pernyataan.show', $surat))
            ->post(route('surat-pernyataan.upload-ttd', $surat), []);

        $response->assertSessionHasErrors('file_ttd');
    }

    // ====================================================================
    // APPROVE
    // ====================================================================

    public function test_approve_surat_pernyataan(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'menunggu_konfirmasi',
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.show', $surat))
            ->post(route('surat-pernyataan.approve', $surat));

        $response->assertRedirect();
        $surat->refresh();
        $this->assertEquals('disetujui', $surat->status);
        $this->assertEquals($this->ketuaUmum->id, $surat->approver_id);
        $this->assertNotNull($surat->tanggal_approval);
    }

    // ====================================================================
    // REJECT
    // ====================================================================

    public function test_reject_surat_pernyataan_requires_reason(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'menunggu_konfirmasi',
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.show', $surat))
            ->post(route('surat-pernyataan.reject', $surat), [
                'alasan_penolakan' => '',
            ]);

        $response->assertSessionHasErrors('alasan_penolakan');
    }

    public function test_reject_surat_pernyataan_reason_min_10_chars(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'menunggu_konfirmasi',
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.show', $surat))
            ->post(route('surat-pernyataan.reject', $surat), [
                'alasan_penolakan' => 'Short',
            ]);

        $response->assertSessionHasErrors('alasan_penolakan');
    }

    public function test_reject_surat_pernyataan_with_valid_reason(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'menunggu_konfirmasi',
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.show', $surat))
            ->post(route('surat-pernyataan.reject', $surat), [
                'alasan_penolakan' => 'Dokumen tidak lengkap dan perlu dilengkapi kembali.',
            ]);

        $response->assertRedirect();
        $surat->refresh();
        $this->assertEquals('ditolak', $surat->status);
        $this->assertNotNull($surat->alasan_penolakan);
    }

    // ====================================================================
    // DOWNLOAD
    // ====================================================================

    public function test_download_without_pdf_redirects_back(): void
    {
        $surat = SuratPernyataan::create([
            'anggota_id' => $this->anggota->id,
            'event_id'   => $this->event->id,
            'status'     => 'pending_ttd',
            'file_pdf'   => null,
        ]);

        $response = $this->actingAs($this->ketuaUmum)
            ->from(route('surat-pernyataan.show', $surat))
            ->get(route('surat-pernyataan.download', $surat));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // ====================================================================
    // UNAUTHORIZED
    // ====================================================================

    public function test_staf_cannot_access_surat_pernyataan(): void
    {
        $staf = Anggota::factory()->sudahLogin()->create(['jabatan_struktural' => 'staf']);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->get(route('surat-pernyataan.index'));
        $response->assertStatus(403);
    }
}
