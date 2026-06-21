<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Anggota Panitia (SRS 3.4.14)
     * Penugasan anggota ke dalam kepanitiaan event
     * CONSTRAINT: 1 anggota = 1 posisi per event (UNIQUE)
     * CONSTRAINT: Sekum 1 ATAU 2 (tidak keduanya per event)
     * CONSTRAINT: Bendum 1 ATAU 2 (tidak keduanya per event)
     */
    public function up(): void
    {
        Schema::create('anggota_panitia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('event')
                ->restrictOnDelete();
            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->restrictOnDelete();
            $table->foreignId('divisi_panitia_id')
                ->nullable()
                ->constrained('divisi_panitia')
                ->nullOnDelete()
                ->comment('Divisi/Sie dalam panitia (opsional untuk BPI)');
            $table->string('jabatan_panitia')->comment('Jabatan dalam kepanitiaan (ketua, sekretaris, anggota, dll)');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // CONSTRAINT UTAMA: 1 anggota hanya boleh 1 posisi per event
            $table->unique(['event_id', 'anggota_id'], 'panitia_event_anggota_unique');
            $table->index('jabatan_panitia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_panitia');
    }
};
