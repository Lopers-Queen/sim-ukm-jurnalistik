<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jadwal Shift → Jadwal Piket
     * Hapus kolom `unit` karena piket sekarang diacak dari seluruh anggota UKM,
     * tidak terpatok jabatan/divisi/unit.
     */
    public function up(): void
    {
        Schema::table('jadwal_shift', function (Blueprint $table) {
            $table->dropIndex(['unit']);
            $table->dropColumn('unit');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_shift', function (Blueprint $table) {
            $table->enum('unit', ['kominfo', 'redaksi', 'inventory'])
                ->after('anggota_id')
                ->comment('Unit yang dijadwalkan');
            $table->index('unit');
        });
    }
};
