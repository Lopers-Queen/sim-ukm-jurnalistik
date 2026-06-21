<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Divisi Panitia (SRS 3.4.13)
     * Struktur divisi/sie dalam kepanitiaan event
     */
    public function up(): void
    {
        Schema::create('divisi_panitia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('event')
                ->cascadeOnDelete();
            $table->string('nama_divisi')->comment('Nama divisi/sie dalam panitia');
            $table->text('deskripsi')->nullable()->comment('Deskripsi tugas divisi');
            $table->unsignedInteger('urutan')->default(0)->comment('Urutan tampil');
            $table->timestamps();

            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisi_panitia');
    }
};
