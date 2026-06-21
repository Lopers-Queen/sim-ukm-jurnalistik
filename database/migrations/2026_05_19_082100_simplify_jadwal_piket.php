<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sederhanakan jadwal piket: hanya Hari + Anggota.
     * Hapus jam, lokasi, keterangan (tidak diperlukan).
     */
    public function up(): void
    {
        Schema::table('jadwal_shift', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai', 'lokasi', 'keterangan']);
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_shift', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->text('keterangan')->nullable();
        });
    }
};
