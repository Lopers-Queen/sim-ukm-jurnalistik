<?php

namespace App\Services;

use App\Enums\Jabatan;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

/**
 * Service: Anggota business logic.
 * Handles role assignment, password reset, and default password generation.
 */
class AnggotaService
{
    /**
     * Auto-assign Spatie role based on jabatan_struktural.
     * Removes all previous roles and assigns the new one.
     */
    public function assignRoleByJabatan(Anggota $anggota): void
    {
        $anggota->syncRoles([]);

        $roleName = Jabatan::roleName($anggota->jabatan_struktural);

        // Override to passive role if member status is passive
        if ($anggota->status_keanggotaan === 'pasif') {
            $roleName = 'anggota_pasif';
        }

        $anggota->assignRole($roleName);
    }

    /**
     * Reset member password to a given password or default (12345678).
     * Also clears lock state and sets first-login flag so user is prompted to change.
     *
     * @param  \App\Models\Anggota  $anggota
     * @param  string|null  $password  Custom password; defaults to '12345678' if null.
     */
    public function resetPassword(Anggota $anggota, ?string $password = null): void
    {
        $newPassword = $password ?? '12345678';

        $anggota->update([
            'password'            => Hash::make($newPassword),
            'is_first_login'      => true,
            'is_locked'           => false,
            'locked_until'        => null,
            'failed_login_attempts' => 0,
        ]);

        // Log the password reset in activity log
        activity()
            ->causedBy(auth()->user())
            ->performedOn($anggota)
            ->withProperties(['action' => 'password_reset', 'target' => $anggota->nim])
            ->log("Admin reset password untuk {$anggota->nama_lengkap} ({$anggota->nim})");
    }

    /**
     * Bulk reset passwords for multiple members at once.
     *
     * @param  \Illuminate\Support\Collection|array  $anggotas  Collection or array of Anggota models.
     * @param  string|null  $password  Custom password; defaults to '12345678' if null.
     * @return int  Number of members reset.
     */
    public function resetAllPasswords($anggotas, ?string $password = null): int
    {
        $count = 0;
        foreach ($anggotas as $anggota) {
            $this->resetPassword($anggota, $password);
            $count++;
        }

        // Log the bulk reset
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'bulk_password_reset', 'count' => $count])
            ->log("Admin bulk reset password untuk {$count} anggota");

        return $count;
    }

    /**
     * Prepare member data for creation: generate default password and set defaults.
     *
     * @param  array  $data  Validated input data.
     * @return array  Data with password and is_first_login added.
     */
    public function prepareForCreation(array $data): array
    {
        $tglLahir = \Carbon\Carbon::parse($data['tanggal_lahir']);

        $data['password'] = Hash::make($tglLahir->format('dmY'));
        $data['is_first_login'] = true;
        $data['tanggal_bergabung'] = $data['tanggal_bergabung'] ?? now()->toDateString();

        return $data;
    }
}
