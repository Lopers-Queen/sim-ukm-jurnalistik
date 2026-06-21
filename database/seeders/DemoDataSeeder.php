<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\AnggaranUkmDivisi;
use App\Models\AnggaranEvent;
use App\Models\Event;
use App\Models\DivisiPanitia;
use App\Models\AnggotaPanitia;
use App\Models\Notulensi;
use App\Models\PeriodeKepengurusan;
use App\Models\TemplateKepanitiaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder data demo untuk testing dan UAT.
 * Data organisasi sesuai UKM Jurnalistik Polnes Periode 2025/2026.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Periode Kepengurusan ────────────────────────
        $periode = PeriodeKepengurusan::create([
            'nama_periode'     => '2025/2026',
            'tahun_mulai'      => 2025,
            'tahun_selesai'    => 2026,
            'tanggal_mulai'    => '2025-09-01',
            'tanggal_selesai'  => '2026-08-31',
            'deskripsi'        => 'Periode kepengurusan aktif hasil MUBES UKM Jurnalistik Polnes',
            'is_active'        => true,
            'status'           => 'aktif',
        ]);



        // ── Event Demo ─────────────────────────────────
        // Kegiatan Besar sesuai profil UKM Jurnalistik

        $ketumId  = Anggota::where('nim', '236651093')->first()?->id;
        $kadivFotoId = Anggota::where('nim', '246651001')->first()?->id;

        $event1 = Event::create([
            'nama_event'     => 'Pelatihan Jurnalistik Tingkat Dasar (PJTD)',
            'deskripsi'      => 'Pendidikan dan pelatihan jurnalis tingkat dasar yang ditujukan kepada mahasiswa yang memiliki ketertarikan di bidang Jurnalistik.',
            'tanggal_mulai'  => now()->addDays(14),
            'tanggal_selesai' => now()->addDays(16),
            'lokasi'         => 'Aula Politeknik Negeri Samarinda',
            'status'         => 'direncanakan',
            'pic_id'         => $ketumId,
            'anggaran_total' => 5000000,
            'periode_id'     => $periode->id,
        ]);

        $event2 = Event::create([
            'nama_event'     => 'Kaderisasi Anggota Baru 2025',
            'deskripsi'      => 'Kegiatan yang dilakukan dengan tujuan untuk melantik calon anggota baru menjadi anggota aktif UKM Jurnalistik sekaligus melihat, menilai, membentuk mental dan karakter mahasiswa menjadi pers mahasiswa.',
            'tanggal_mulai'  => now()->addDays(45),
            'tanggal_selesai' => now()->addDays(47),
            'lokasi'         => 'Gedung Serba Guna Polnes',
            'status'         => 'direncanakan',
            'pic_id'         => $ketumId,
            'anggaran_total' => 8000000,
            'periode_id'     => $periode->id,
        ]);

        $event3 = Event::create([
            'nama_event'     => 'Rapat Kerja Awal Periode 2025/2026',
            'deskripsi'      => 'Rapat kerja BPI dan BPH untuk menyusun program kerja periode baru UKM Jurnalistik.',
            'tanggal_mulai'  => now()->subDays(7),
            'tanggal_selesai' => now()->subDays(7),
            'lokasi'         => 'Sekretariat UKM Jurnalistik',
            'status'         => 'selesai',
            'pic_id'         => $ketumId,
            'anggaran_total' => 500000,
            'periode_id'     => $periode->id,
        ]);

        $event4 = Event::create([
            'nama_event'     => 'Workshop Fotografi Dasar',
            'deskripsi'      => 'Workshop pengenalan teknik fotografi dasar dan olah gambar untuk anggota baru divisi fotografi.',
            'tanggal_mulai'  => now()->addDays(21),
            'tanggal_selesai' => now()->addDays(21),
            'lokasi'         => 'Lab Komputer Gedung TI Lt. 2',
            'status'         => 'direncanakan',
            'pic_id'         => $kadivFotoId,
            'anggaran_total' => 2500000,
            'periode_id'     => $periode->id,
        ]);

        // Divisi panitia untuk PJTD
        $divAcara    = DivisiPanitia::create(['event_id' => $event1->id, 'nama_divisi' => 'Acara',         'deskripsi' => 'Menyusun rundown dan susunan acara PJTD']);
        $divKonsumsi = DivisiPanitia::create(['event_id' => $event1->id, 'nama_divisi' => 'Konsumsi',      'deskripsi' => 'Konsumsi peserta & panitia']);
        $divDokum    = DivisiPanitia::create(['event_id' => $event1->id, 'nama_divisi' => 'Dokumentasi',   'deskripsi' => 'Foto dan video dokumentasi kegiatan']);
        $divPublikasi = DivisiPanitia::create(['event_id' => $event1->id, 'nama_divisi' => 'Publikasi',    'deskripsi' => 'Media sosial & poster publikasi']);

        // Assign panitia PJTD
        $koordinator = Anggota::where('nim', '246651001')->first(); // Kadiv Fotografi
        if ($koordinator) {
            AnggotaPanitia::create([
                'event_id'          => $event1->id,
                'anggota_id'        => $koordinator->id,
                'divisi_panitia_id' => $divAcara->id,
                'jabatan_panitia'   => 'Koordinator',
            ]);
        }

        // ── Template Kepanitiaan ───────────────────────
        TemplateKepanitiaan::create([
            'nama_template'  => 'Template Pelatihan Jurnalistik',
            'deskripsi'      => 'Struktur standar untuk kegiatan PJTD/PJTL.',
            'struktur'       => [
                ['nama' => 'Acara', 'deskripsi' => 'Menyusun rundown dan susunan acara', 'estimasi_anggota' => 5],
                ['nama' => 'Konsumsi', 'deskripsi' => 'Menyiapkan makanan & minuman', 'estimasi_anggota' => 4],
                ['nama' => 'Perlengkapan', 'deskripsi' => 'Menyiapkan alat & ruangan', 'estimasi_anggota' => 3],
                ['nama' => 'Dokumentasi', 'deskripsi' => 'Foto & video dokumentasi kegiatan', 'estimasi_anggota' => 5],
                ['nama' => 'Publikasi', 'deskripsi' => 'Media sosial & poster', 'estimasi_anggota' => 3],
            ],
            'is_active' => true,
        ]);

        TemplateKepanitiaan::create([
            'nama_template'  => 'Template Kaderisasi',
            'deskripsi'      => 'Struktur untuk kegiatan kaderisasi anggota baru.',
            'struktur'       => [
                ['nama' => 'Steering Committee', 'deskripsi' => 'Pengawasan dan arahan keseluruhan', 'estimasi_anggota' => 3],
                ['nama' => 'Acara & Materi', 'deskripsi' => 'Penyusunan materi dan rundown', 'estimasi_anggota' => 5],
                ['nama' => 'Konsumsi & Logistik', 'deskripsi' => 'Konsumsi, transportasi & perlengkapan', 'estimasi_anggota' => 4],
                ['nama' => 'Dokumentasi', 'deskripsi' => 'Foto & video coverage kegiatan', 'estimasi_anggota' => 6],
                ['nama' => 'Publikasi & Humas', 'deskripsi' => 'Publikasi media sosial & hubungan masyarakat', 'estimasi_anggota' => 3],
                ['nama' => 'Keamanan & P3K', 'deskripsi' => 'Keamanan lokasi dan pertolongan pertama', 'estimasi_anggota' => 3],
            ],
            'is_active' => true,
        ]);

        TemplateKepanitiaan::create([
            'nama_template'  => 'Template Pekan Jurnalistik',
            'deskripsi'      => 'Struktur untuk event Pekan Jurnalistik (seminar & lomba).',
            'struktur'       => [
                ['nama' => 'Koordinator Lapangan', 'deskripsi' => 'Koordinasi antar tim di lokasi', 'estimasi_anggota' => 3],
                ['nama' => 'Tim Seminar', 'deskripsi' => 'Pengelolaan sesi seminar & narasumber', 'estimasi_anggota' => 5],
                ['nama' => 'Tim Lomba', 'deskripsi' => 'Pengelolaan kompetisi jurnalistik', 'estimasi_anggota' => 6],
                ['nama' => 'Tim Fotografi', 'deskripsi' => 'Foto jurnalistik & dokumentasi', 'estimasi_anggota' => 6],
                ['nama' => 'Tim Videografi', 'deskripsi' => 'Video coverage & editing', 'estimasi_anggota' => 4],
                ['nama' => 'Tim Redaksi', 'deskripsi' => 'Menulis berita & caption kegiatan', 'estimasi_anggota' => 4],
                ['nama' => 'Tim Publikasi', 'deskripsi' => 'Upload & interaksi media sosial', 'estimasi_anggota' => 3],
                ['nama' => 'Logistik & Perlengkapan', 'deskripsi' => 'Transportasi & perlengkapan', 'estimasi_anggota' => 3],
            ],
            'is_active' => true,
        ]);

        // ── Anggaran Divisi Demo ───────────────────────
        $divisiList = ['fotografi', 'pers_penyiaran', 'videografi', 'kominfo', 'redaksi', 'inventory', 'bpi'];
        $anggaranValues = [
            'fotografi'       => [800000, 350000],
            'pers_penyiaran'  => [600000, 200000],
            'videografi'      => [1000000, 500000],
            'kominfo'         => [500000, 150000],
            'redaksi'         => [400000, 100000],
            'inventory'       => [750000, 400000],
            'bpi'             => [2000000, 800000],
        ];

        foreach ($divisiList as $divisi) {
            AnggaranUkmDivisi::create([
                'periode_id'      => $periode->id,
                'divisi'          => $divisi,
                'bulan'           => 5,
                'tahun'           => 2026,
                'jumlah_anggaran' => $anggaranValues[$divisi][0],
                'jumlah_terpakai' => $anggaranValues[$divisi][1],
                'keterangan'      => 'Alokasi bulan Mei 2026',
            ]);
        }

        // ── Anggaran Event Demo ────────────────────────
        // PJTD
        AnggaranEvent::create(['event_id' => $event1->id, 'item' => 'Sewa Proyektor & Sound System', 'kategori' => 'perlengkapan', 'qty' => 1, 'harga_satuan' => 500000, 'jumlah_dianggarkan' => 500000, 'jumlah_realisasi' => 500000]);
        AnggaranEvent::create(['event_id' => $event1->id, 'item' => 'Snack Box Peserta', 'kategori' => 'konsumsi', 'qty' => 40, 'harga_satuan' => 20000, 'jumlah_dianggarkan' => 800000, 'jumlah_realisasi' => 760000]);
        AnggaranEvent::create(['event_id' => $event1->id, 'item' => 'Makan Siang Panitia & Pemateri', 'kategori' => 'konsumsi', 'qty' => 20, 'harga_satuan' => 25000, 'jumlah_dianggarkan' => 500000, 'jumlah_realisasi' => 475000]);
        AnggaranEvent::create(['event_id' => $event1->id, 'item' => 'Banner & Spanduk', 'kategori' => 'publikasi', 'qty' => 3, 'harga_satuan' => 100000, 'jumlah_dianggarkan' => 300000, 'jumlah_realisasi' => 300000]);

        // Kaderisasi
        AnggaranEvent::create(['event_id' => $event2->id, 'item' => 'Transportasi Tim', 'kategori' => 'transportasi', 'qty' => 15, 'harga_satuan' => 50000, 'jumlah_dianggarkan' => 750000, 'jumlah_realisasi' => 720000]);
        AnggaranEvent::create(['event_id' => $event2->id, 'item' => 'Konsumsi 3 Hari', 'kategori' => 'konsumsi', 'qty' => 60, 'harga_satuan' => 35000, 'jumlah_dianggarkan' => 2100000, 'jumlah_realisasi' => 2050000]);
        AnggaranEvent::create(['event_id' => $event2->id, 'item' => 'Cetak ID Card & Sertifikat', 'kategori' => 'perlengkapan', 'qty' => 50, 'harga_satuan' => 15000, 'jumlah_dianggarkan' => 750000, 'jumlah_realisasi' => 700000]);
        AnggaranEvent::create(['event_id' => $event2->id, 'item' => 'Dekorasi Venue', 'kategori' => 'perlengkapan', 'qty' => 1, 'harga_satuan' => 500000, 'jumlah_dianggarkan' => 500000, 'jumlah_realisasi' => 480000]);

        // Rapat Kerja
        AnggaranEvent::create(['event_id' => $event3->id, 'item' => 'Konsumsi Rapat', 'kategori' => 'konsumsi', 'qty' => 14, 'harga_satuan' => 20000, 'jumlah_dianggarkan' => 280000, 'jumlah_realisasi' => 280000]);
        AnggaranEvent::create(['event_id' => $event3->id, 'item' => 'ATK & Fotocopy', 'kategori' => 'perlengkapan', 'qty' => 1, 'harga_satuan' => 75000, 'jumlah_dianggarkan' => 75000, 'jumlah_realisasi' => 65000]);

        // Workshop Fotografi
        AnggaranEvent::create(['event_id' => $event4->id, 'item' => 'Sewa Proyektor', 'kategori' => 'perlengkapan', 'qty' => 1, 'harga_satuan' => 200000, 'jumlah_dianggarkan' => 200000, 'jumlah_realisasi' => 200000]);
        AnggaranEvent::create(['event_id' => $event4->id, 'item' => 'Snack Box Peserta', 'kategori' => 'konsumsi', 'qty' => 30, 'harga_satuan' => 15000, 'jumlah_dianggarkan' => 450000, 'jumlah_realisasi' => 420000]);

        // ── Notulensi Demo ─────────────────────────────
        $pencatatId = Anggota::where('nim', '246522033')->first()?->id; // Sekretaris I

        Notulensi::create([
            'judul'          => 'Rapat Kerja Awal Periode 2025/2026',
            'jenis_rapat'    => 'rapat_kerja',
            'tanggal_rapat'  => now()->subDays(7),
            'lokasi'         => 'Sekretariat UKM Jurnalistik',
            'isi_notulensi'  => "Pembahasan program kerja periode 2025/2026 UKM Jurnalistik Polnes.\n\n1. Pembentukan struktur BPI dan BPH\n2. Penyusunan kalender event tahunan\n3. Pembahasan anggaran tahun ini\n4. Jadwal Kaderisasi dan PJTD\n5. Rencana Pekan Jurnalistik 2026\n\nDisepakati 12 program kerja utama untuk periode ini.",
            'daftar_hadir'   => json_encode(['236651093', '236511039', '246522033', '236611052', '246221022', '246221025', '246521041', '246651001', '246652010', '236151093', '236201022', '236201036']),
            'pencatat_id'    => $pencatatId,
        ]);

        Notulensi::create([
            'judul'          => 'Rapat Koordinasi Persiapan PJTD',
            'jenis_rapat'    => 'rapat_rutin',
            'tanggal_rapat'  => now()->subDays(3),
            'lokasi'         => 'Sekretariat UKM Jurnalistik',
            'isi_notulensi'  => "Koordinasi persiapan PJTD 2025.\n\n1. Pembagian tugas panitia\n2. Konfirmasi pemateri\n3. Finalisasi rundown acara\n4. Update status anggaran",
            'daftar_hadir'   => json_encode(['236651093', '236511039', '246522033', '246521041', '246651001']),
            'pencatat_id'    => $pencatatId,
        ]);
    }
}
