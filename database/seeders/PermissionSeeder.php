<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * Seeder Permissions (SRS Section 5)
 * Permissions per Functional Requirement (FR-01 sampai FR-23).
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar modul berdasarkan Functional Requirements
        $modules = [
            'auth'              => ['login', 'logout', 'change-password', 'reset-password'],          // FR-01
            'organisasi'        => ['view', 'create', 'edit', 'delete', 'reset-password-anggota'],     // FR-02
            'profil'            => ['view', 'edit'],                                                   // FR-03
            'notulensi'         => ['view', 'create', 'edit', 'delete'],                              // FR-04
            'rekrutmen'         => ['view', 'create', 'edit', 'delete'],                              // FR-05
            'jadwal-shift'      => ['view', 'create', 'edit', 'delete'],                              // FR-06
            'anggaran-divisi'   => ['view', 'create', 'edit', 'delete'],                              // FR-07
            'naskah-redaksi'    => ['view', 'create', 'edit', 'delete', 'approve', 'publish'],        // FR-08
            'event'             => ['view', 'create', 'edit', 'delete'],                              // FR-09
            'keamanan'          => ['view-log', 'manage-lockout'],                                     // FR-10
            'dashboard'         => ['view-admin', 'view-kadiv', 'view-kanit', 'view-anggota'],        // FR-11
            'laporan'           => ['view', 'create', 'export-pdf', 'export-excel'],                  // FR-12
            'periode'           => ['view', 'create', 'edit', 'delete', 'activate'],                  // FR-13
            'kepanitiaan'       => ['view', 'create', 'edit', 'delete', 'assign'],                    // FR-14
            'eligibility'       => ['view', 'validate', 'override'],                                  // FR-15
            'template-panitia'  => ['view', 'create', 'edit', 'delete'],                              // FR-19
            'pergantian'        => ['view', 'create', 'approve'],                                      // FR-17
            'anggaran-event'    => ['view', 'create', 'edit', 'delete'],                              // FR-18
            'laporan-event'     => ['view', 'create', 'edit'],                                         // FR-23
            'surat-pernyataan'  => ['view', 'create', 'upload-ttd', 'approve', 'reject'],             // FR-21
            'activity-log'      => ['view'],                                                           // FR-22
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name'       => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
