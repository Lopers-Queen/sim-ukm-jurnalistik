<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Seeder: Assign permissions ke setiap role (SRS Section 5 — Matriks Hak Akses)
 */
class AssignPermissionToRoleSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ketua Umum: Akses penuh ──────────────────
        $ketum = Role::findByName('ketua_umum');
        $ketum->givePermissionTo([
            // Organisasi (FR-02)
            'organisasi.view', 'organisasi.create', 'organisasi.edit', 'organisasi.delete',
            'organisasi.reset-password-anggota',
            // Profil (FR-03)
            'profil.view', 'profil.edit',
            // Notulensi (FR-04)
            'notulensi.view', 'notulensi.create', 'notulensi.edit', 'notulensi.delete',
            // Rekrutmen (FR-05)
            'rekrutmen.view', 'rekrutmen.create', 'rekrutmen.edit', 'rekrutmen.delete',
            // Jadwal Shift (FR-06)
            'jadwal-shift.view', 'jadwal-shift.create', 'jadwal-shift.edit', 'jadwal-shift.delete',
            // Anggaran Divisi (FR-07)
            'anggaran-divisi.view', 'anggaran-divisi.create', 'anggaran-divisi.edit', 'anggaran-divisi.delete',
            // Naskah Redaksi (FR-08)
            'naskah-redaksi.view', 'naskah-redaksi.approve',
            // Event (FR-09)
            'event.view', 'event.create', 'event.edit', 'event.delete',
            // Keamanan (FR-10)
            'keamanan.view-log', 'keamanan.manage-lockout',
            // Dashboard (FR-11)
            'dashboard.view-admin',
            // Laporan (FR-12)
            'laporan.view', 'laporan.create', 'laporan.export-pdf', 'laporan.export-excel',
            // Periode (FR-13)
            'periode.view', 'periode.create', 'periode.edit', 'periode.delete', 'periode.activate',
            // Kepanitiaan (FR-14)
            'kepanitiaan.view', 'kepanitiaan.create', 'kepanitiaan.edit', 'kepanitiaan.delete', 'kepanitiaan.assign',
            // Eligibility (FR-15)
            'eligibility.view', 'eligibility.validate', 'eligibility.override',
            // Template (FR-16)
            'template-panitia.view', 'template-panitia.create', 'template-panitia.edit', 'template-panitia.delete',
            // Pergantian (FR-17)
            'pergantian.view', 'pergantian.create', 'pergantian.approve',
            // Anggaran Event (FR-18)
            'anggaran-event.view', 'anggaran-event.create', 'anggaran-event.edit', 'anggaran-event.delete',
            // Laporan Event (FR-19)
            'laporan-event.view', 'laporan-event.create', 'laporan-event.edit',
            // Surat Pernyataan (FR-21)
            'surat-pernyataan.view', 'surat-pernyataan.create', 'surat-pernyataan.approve', 'surat-pernyataan.reject',
            // Activity Log (FR-22)
            'activity-log.view',
        ]);

        // ── Wakil Ketua Umum: Hampir sama dengan Ketum ──
        $waketum = Role::findByName('wakil_ketua_umum');
        $waketum->givePermissionTo([
            'organisasi.view', 'organisasi.create', 'organisasi.edit',
            'organisasi.reset-password-anggota',
            'profil.view', 'profil.edit',
            'notulensi.view', 'notulensi.create', 'notulensi.edit',
            'rekrutmen.view', 'rekrutmen.create', 'rekrutmen.edit',
            'jadwal-shift.view', 'jadwal-shift.create', 'jadwal-shift.edit',
            'anggaran-divisi.view', 'anggaran-divisi.create', 'anggaran-divisi.edit',
            'naskah-redaksi.view', 'naskah-redaksi.approve',
            'event.view', 'event.create', 'event.edit',
            'keamanan.view-log',
            'dashboard.view-admin',
            'laporan.view', 'laporan.create', 'laporan.export-pdf', 'laporan.export-excel',
            'periode.view', 'periode.create', 'periode.edit',
            'kepanitiaan.view', 'kepanitiaan.create', 'kepanitiaan.edit', 'kepanitiaan.assign',
            'eligibility.view', 'eligibility.validate', 'eligibility.override',
            'template-panitia.view', 'template-panitia.create', 'template-panitia.edit',
            'pergantian.view', 'pergantian.create', 'pergantian.approve',
            'anggaran-event.view', 'anggaran-event.create', 'anggaran-event.edit',
            'laporan-event.view', 'laporan-event.create', 'laporan-event.edit',
            'surat-pernyataan.view', 'surat-pernyataan.create', 'surat-pernyataan.approve', 'surat-pernyataan.reject',
            'activity-log.view',
        ]);

        // ── Sekretaris Umum 1 & 2 ──────────────────
        foreach (['sekretaris_umum_1', 'sekretaris_umum_2'] as $roleName) {
            $role = Role::findByName($roleName);
            $role->givePermissionTo([
                'organisasi.view',
                'profil.view', 'profil.edit',
                'notulensi.view', 'notulensi.create', 'notulensi.edit', 'notulensi.delete',
                'rekrutmen.view',
                'event.view', 'event.create', 'event.edit',
                'dashboard.view-admin',
                'laporan.view', 'laporan.create', 'laporan.export-pdf', 'laporan.export-excel',
                'kepanitiaan.view', 'kepanitiaan.create', 'kepanitiaan.edit',
                'template-panitia.view', 'template-panitia.create',
                'laporan-event.view', 'laporan-event.create', 'laporan-event.edit',
                'surat-pernyataan.view', 'surat-pernyataan.create',
                'activity-log.view',
            ]);
        }

        // ── Bendahara Umum 1 & 2 ──────────────────
        foreach (['bendahara_umum_1', 'bendahara_umum_2'] as $roleName) {
            $role = Role::findByName($roleName);
            $role->givePermissionTo([
                'organisasi.view',
                'profil.view', 'profil.edit',
                'anggaran-divisi.view', 'anggaran-divisi.create', 'anggaran-divisi.edit', 'anggaran-divisi.delete',
                'anggaran-event.view', 'anggaran-event.create', 'anggaran-event.edit', 'anggaran-event.delete',
                'event.view',
                'dashboard.view-admin',
                'laporan.view', 'laporan.export-pdf', 'laporan.export-excel',
            ]);
        }

        // ── Kepala Divisi (Fotografi, Pers & Penyiaran, Videografi) ──
        foreach (['kadiv_fotografi', 'kadiv_pers_penyiaran', 'kadiv_videografi'] as $roleName) {
            $role = Role::findByName($roleName);
            $role->givePermissionTo([
                'organisasi.view',
                'profil.view', 'profil.edit',
                'notulensi.view', 'notulensi.create',
                'jadwal-shift.view',
                'anggaran-divisi.view',
                'event.view',
                'dashboard.view-kadiv',
                'laporan.view',
                'kepanitiaan.view',
            ]);
        }

        // ── Kepala Unit (Kominfo, Redaksi, Inventory) ──
        foreach (['kanit_kominfo', 'kanit_redaksi', 'kanit_inventory'] as $roleName) {
            $role = Role::findByName($roleName);
            $permissions = [
                'organisasi.view',
                'profil.view', 'profil.edit',
                'jadwal-shift.view', 'jadwal-shift.create', 'jadwal-shift.edit', 'jadwal-shift.delete',
                'event.view',
                'dashboard.view-kanit',
                'laporan.view',
                'kepanitiaan.view',
            ];

            // Kanit Redaksi punya akses khusus naskah
            if ($roleName === 'kanit_redaksi') {
                $permissions = array_merge($permissions, [
                    'naskah-redaksi.view', 'naskah-redaksi.create',
                    'naskah-redaksi.edit', 'naskah-redaksi.delete',
                    'naskah-redaksi.approve', 'naskah-redaksi.publish',
                ]);
            }

            $role->givePermissionTo($permissions);
        }

        // ── Staf ─────────────────────────────────
        $staf = Role::findByName('staf');
        $staf->givePermissionTo([
            'profil.view', 'profil.edit',
            'notulensi.view',
            'jadwal-shift.view',
            'event.view',
            'dashboard.view-anggota',
            'kepanitiaan.view',
            'naskah-redaksi.view', 'naskah-redaksi.create',
        ]);

        // ── Anggota Aktif ────────────────────────
        $aktif = Role::findByName('anggota_aktif');
        $aktif->givePermissionTo([
            'profil.view', 'profil.edit',
            'notulensi.view',
            'event.view',
            'dashboard.view-anggota',
            'kepanitiaan.view',
            'surat-pernyataan.view', 'surat-pernyataan.upload-ttd',
        ]);

        // ── Anggota Pasif ────────────────────────
        $pasif = Role::findByName('anggota_pasif');
        $pasif->givePermissionTo([
            'profil.view', 'profil.edit',
            'event.view',
            'dashboard.view-anggota',
            'kepanitiaan.view',
            'surat-pernyataan.view', 'surat-pernyataan.create', 'surat-pernyataan.upload-ttd',
        ]);

        // ── Ketua Panitia (dynamic per event) ────
        $ketuaPanitia = Role::findByName('ketua_panitia');
        $ketuaPanitia->givePermissionTo([
            'profil.view', 'profil.edit',
            'event.view', 'event.edit',
            'kepanitiaan.view', 'kepanitiaan.create', 'kepanitiaan.edit', 'kepanitiaan.assign',
            'anggaran-event.view', 'anggaran-event.create', 'anggaran-event.edit',
            'laporan-event.view', 'laporan-event.create', 'laporan-event.edit',
            'dashboard.view-anggota',
            'surat-pernyataan.view',
        ]);
    }
}
