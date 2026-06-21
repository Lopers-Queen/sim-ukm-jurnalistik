<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Seeder Roles (SRS Section 5)
 * 16 roles sesuai struktur organisasi UKM Jurnalistik.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            // Super Admin (bypass semua permission)
            ['name' => 'super_admin',         'guard_name' => 'web'],

            // BPI (Badan Pengurus Inti)
            ['name' => 'ketua_umum',          'guard_name' => 'web'],
            ['name' => 'wakil_ketua_umum',    'guard_name' => 'web'],
            ['name' => 'sekretaris_umum_1',   'guard_name' => 'web'],
            ['name' => 'sekretaris_umum_2',   'guard_name' => 'web'],
            ['name' => 'bendahara_umum_1',    'guard_name' => 'web'],
            ['name' => 'bendahara_umum_2',    'guard_name' => 'web'],

            // BPH — Kepala Divisi
            ['name' => 'kadiv_fotografi',     'guard_name' => 'web'],
            ['name' => 'kadiv_pers_penyiaran', 'guard_name' => 'web'],
            ['name' => 'kadiv_videografi',    'guard_name' => 'web'],

            // BPH — Kepala Unit
            ['name' => 'kanit_kominfo',       'guard_name' => 'web'],
            ['name' => 'kanit_redaksi',       'guard_name' => 'web'],
            ['name' => 'kanit_inventory',     'guard_name' => 'web'],

            // Anggota
            ['name' => 'staf',                'guard_name' => 'web'],
            ['name' => 'anggota_aktif',       'guard_name' => 'web'],
            ['name' => 'anggota_pasif',       'guard_name' => 'web'],

            // Dynamic per event
            ['name' => 'ketua_panitia',       'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
