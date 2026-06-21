<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan: Roles → Permissions → Assignment → Data organisasi
     */
    public function run(): void
    {
        // 1. Seed Roles
        $this->call(RoleSeeder::class);

        // 2. Seed Permissions
        $this->call(PermissionSeeder::class);

        // 3. Assign Permissions ke Roles
        $this->call(AssignPermissionToRoleSeeder::class);

        // 4. Buat Super Admin — murni admin, bukan anggota organisasi
        $superAdmin = Anggota::create([
            'nim'                  => 'admin',
            'nama_lengkap'         => 'Administrator',
            'email'                => 'admin@sim-jurnalistik.local',
            'password'             => Hash::make('admin123'),
            'tanggal_lahir'        => null,
            'tempat_lahir'         => null,
            'jenis_kelamin'        => 'L',
            'no_hp'                => null,
            'program_studi'        => null,
            'jurusan'              => null,
            'divisi'               => null,
            'jabatan_struktural'   => 'admin',
            'status_keanggotaan'   => 'aktif',
            'tanggal_bergabung'    => null,
            'is_first_login'       => false,
        ]);
        $superAdmin->assignRole('super_admin');

        // ══════════════════════════════════════════════════════════════
        // BADAN PENGURUS INTI (BPI) — Periode 2025/2026
        // Data resmi UKM Jurnalistik Politeknik Negeri Samarinda
        // ══════════════════════════════════════════════════════════════

        // 5. Ketua Umum
        $ketum = Anggota::create([
            'nim'                  => '236651093',
            'nama_lengkap'         => 'Indra Maulana',
            'email'                => 'indra.maulana@polnes.ac.id',
            'password'             => Hash::make('12345678'),
            'tanggal_lahir'        => '2004-08-15',
            'tempat_lahir'         => 'Samarinda',
            'jenis_kelamin'        => 'L',
            'no_hp'                => '081234567001',
            'program_studi'        => 'Teknik Multimedia dan Jaringan',
            'jurusan'              => 'Teknologi Informasi',
            'divisi'               => null,
            'jabatan_struktural'   => 'ketua_umum',
            'status_keanggotaan'   => 'aktif',
            'tanggal_bergabung'    => '2023-09-01',
            'is_first_login'       => false,
        ]);
        $ketum->assignRole('ketua_umum');

        // 6. Anggota BPI & BPH lainnya
        $pengurusData = [
            // ── BPI ──────────────────────────────────────
            [
                'nim'                => '236511039',
                'nama_lengkap'       => 'Mutia Salsabila',
                'email'              => 'mutia.salsabila@polnes.ac.id',
                'tanggal_lahir'      => '2004-03-12',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'P',
                'program_studi'      => 'Administrasi Bisnis',
                'jurusan'            => 'Administrasi Niaga',
                'jabatan_struktural' => 'wakil_ketua_umum',
                'divisi'             => null,
                'role'               => 'wakil_ketua_umum',
            ],
            [
                'nim'                => '246522033',
                'nama_lengkap'       => 'Dianra Putri Ashari',
                'email'              => 'dianra.putri@polnes.ac.id',
                'tanggal_lahir'      => '2005-06-20',
                'tempat_lahir'       => 'Balikpapan',
                'jenis_kelamin'      => 'P',
                'program_studi'      => 'Administrasi Bisnis',
                'jurusan'            => 'Administrasi Niaga',
                'jabatan_struktural' => 'sekretaris_umum_1',
                'divisi'             => null,
                'role'               => 'sekretaris_umum_1',
            ],
            [
                'nim'                => '236611052',
                'nama_lengkap'       => "Ma'rifah Afra",
                'email'              => 'marifah.afra@polnes.ac.id',
                'tanggal_lahir'      => '2004-11-05',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'P',
                'program_studi'      => 'Teknik Sipil',
                'jurusan'            => 'Teknik Sipil',
                'jabatan_struktural' => 'sekretaris_umum_2',
                'divisi'             => null,
                'role'               => 'sekretaris_umum_2',
            ],
            [
                'nim'                => '246221022',
                'nama_lengkap'       => 'Adinda Mutiarakasih',
                'email'              => 'adinda.mutia@polnes.ac.id',
                'tanggal_lahir'      => '2005-04-18',
                'tempat_lahir'       => 'Tenggarong',
                'jenis_kelamin'      => 'P',
                'program_studi'      => 'Akuntansi',
                'jurusan'            => 'Akuntansi',
                'jabatan_struktural' => 'bendahara_umum_1',
                'divisi'             => null,
                'role'               => 'bendahara_umum_1',
            ],
            [
                'nim'                => '246221025',
                'nama_lengkap'       => 'Siti Melly Natasya',
                'email'              => 'siti.melly@polnes.ac.id',
                'tanggal_lahir'      => '2005-12-25',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'P',
                'program_studi'      => 'Akuntansi',
                'jurusan'            => 'Akuntansi',
                'jabatan_struktural' => 'bendahara_umum_2',
                'divisi'             => null,
                'role'               => 'bendahara_umum_2',
            ],

            // ── BPH — Kepala Divisi ─────────────────────
            [
                'nim'                => '246521041',
                'nama_lengkap'       => 'Angrayni Panca Juliyanti',
                'email'              => 'angrayni.panca@polnes.ac.id',
                'tanggal_lahir'      => '2005-07-05',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'P',
                'program_studi'      => 'Administrasi Bisnis',
                'jurusan'            => 'Administrasi Niaga',
                'jabatan_struktural' => 'kadiv_pers_penyiaran',
                'divisi'             => 'pers_penyiaran',
                'role'               => 'kadiv_pers_penyiaran',
            ],
            [
                'nim'                => '246651001',
                'nama_lengkap'       => 'Muhammad Zaky Firdaus',
                'email'              => 'zaky.firdaus@polnes.ac.id',
                'tanggal_lahir'      => '2005-01-10',
                'tempat_lahir'       => 'Bontang',
                'jenis_kelamin'      => 'L',
                'program_studi'      => 'Teknik Multimedia dan Jaringan',
                'jurusan'            => 'Teknologi Informasi',
                'jabatan_struktural' => 'kadiv_fotografi',
                'divisi'             => 'fotografi',
                'role'               => 'kadiv_fotografi',
            ],
            [
                'nim'                => '246652010',
                'nama_lengkap'       => 'Abdurrahman Raher Azzahidan R.',
                'email'              => 'abdurrahman.raher@polnes.ac.id',
                'tanggal_lahir'      => '2005-09-22',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'L',
                'program_studi'      => 'Teknik Multimedia dan Jaringan',
                'jurusan'            => 'Teknologi Informasi',
                'jabatan_struktural' => 'kadiv_videografi',
                'divisi'             => 'videografi',
                'role'               => 'kadiv_videografi',
            ],

            // ── BPH — Kepala Unit ───────────────────────
            [
                'nim'                => '236151093',
                'nama_lengkap'       => 'Arya Putra Pradana',
                'email'              => 'arya.putra@polnes.ac.id',
                'tanggal_lahir'      => '2004-02-28',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'L',
                'program_studi'      => 'Teknik Mesin',
                'jurusan'            => 'Teknik Mesin',
                'jabatan_struktural' => 'kanit_inventory',
                'divisi'             => 'inventory',
                'role'               => 'kanit_inventory',
            ],
            [
                'nim'                => '236201022',
                'nama_lengkap'       => 'Muhammad Fauzan Haqiqi',
                'email'              => 'fauzan.haqiqi@polnes.ac.id',
                'tanggal_lahir'      => '2004-05-17',
                'tempat_lahir'       => 'Balikpapan',
                'jenis_kelamin'      => 'L',
                'program_studi'      => 'Teknik Elektro',
                'jurusan'            => 'Teknik Elektro',
                'jabatan_struktural' => 'kanit_kominfo',
                'divisi'             => 'kominfo',
                'role'               => 'kanit_kominfo',
            ],
            [
                'nim'                => '236201036',
                'nama_lengkap'       => 'Rafi Firjatullah Arya Nugrah',
                'email'              => 'rafi.firjatullah@polnes.ac.id',
                'tanggal_lahir'      => '2004-10-03',
                'tempat_lahir'       => 'Samarinda',
                'jenis_kelamin'      => 'L',
                'program_studi'      => 'Teknik Elektro',
                'jurusan'            => 'Teknik Elektro',
                'jabatan_struktural' => 'kanit_redaksi',
                'divisi'             => 'redaksi',
                'role'               => 'kanit_redaksi',
            ],
        ];

        foreach ($pengurusData as $data) {
            $role = $data['role'];
            unset($data['role']);

            $anggota = Anggota::create(array_merge($data, [
                'password'           => Hash::make(date('dmY', strtotime($data['tanggal_lahir']))),
                'no_hp'              => '08' . rand(1000000000, 9999999999),
                'status_keanggotaan' => 'aktif',
                'tanggal_bergabung'  => '2024-09-01',
                'is_first_login'     => true,
            ]));
            $anggota->assignRole($role);
        }

        // 7. Seed demo data (events, templates, notulensi, anggaran)
        $this->call(DemoDataSeeder::class);

        // 8. Seed anggota dari data absen UKM Jurnalistik 2025/2026
        $this->call(AnggotaAbsenSeeder::class);
    }
}
