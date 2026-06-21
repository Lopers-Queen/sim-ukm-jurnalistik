<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambah 'admin' ke enum jabatan_struktural.
     * Admin murni tanpa data organisasi.
     */
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN — enum values are not enforced in SQLite
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE anggota MODIFY COLUMN jabatan_struktural ENUM(
                'admin',
                'ketua_umum',
                'wakil_ketua_umum',
                'sekretaris_umum_1',
                'sekretaris_umum_2',
                'bendahara_umum_1',
                'bendahara_umum_2',
                'kadiv_fotografi',
                'kadiv_pers_penyiaran',
                'kadiv_videografi',
                'kanit_kominfo',
                'kanit_redaksi',
                'kanit_inventory',
                'staf',
                'anggota'
            ) DEFAULT 'anggota'");
        }

        // Make non-essential fields nullable for admin
        Schema::table('anggota', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->change();
            $table->date('tanggal_bergabung')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE anggota MODIFY COLUMN jabatan_struktural ENUM(
                'ketua_umum',
                'wakil_ketua_umum',
                'sekretaris_umum_1',
                'sekretaris_umum_2',
                'bendahara_umum_1',
                'bendahara_umum_2',
                'kadiv_fotografi',
                'kadiv_pers_penyiaran',
                'kadiv_videografi',
                'kanit_kominfo',
                'kanit_redaksi',
                'kanit_inventory',
                'staf',
                'anggota'
            ) DEFAULT 'anggota'");
        }
    }
};
