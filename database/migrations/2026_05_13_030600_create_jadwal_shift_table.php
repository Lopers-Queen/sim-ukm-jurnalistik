<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Jadwal Shift (SRS 3.4.9)
     * Jadwal piket anggota unit (Kominfo, Redaksi, Inventory)
     */
    public function up(): void
    {
        Schema::create('jadwal_shift', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->cascadeOnDelete();
            $table->enum('unit', [
                'kominfo',
                'redaksi',
                'inventory',
            ])->comment('Unit yang dijadwalkan');
            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu',
            ]);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('lokasi', 150)->nullable();
            $table->text('keterangan')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('anggota_id');
            $table->index('unit');
            $table->index('hari');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_shift');
    }
};
