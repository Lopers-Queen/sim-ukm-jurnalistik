<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Periode Kepengurusan (SRS 3.4.5)
     * Base table — tidak memiliki FK dependencies
     */
    public function up(): void
    {
        Schema::create('periode_kepengurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode', 50)->comment('Contoh: 2024/2025');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(false)->comment('Hanya 1 periode aktif');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->unique('nama_periode');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_kepengurusan');
    }
};
