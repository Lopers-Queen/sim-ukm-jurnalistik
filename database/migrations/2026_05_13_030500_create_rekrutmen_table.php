<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Rekrutmen (SRS 3.4.8)
     * Data penerimaan anggota baru per periode
     */
    public function up(): void
    {
        Schema::create('rekrutmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->constrained('periode_kepengurusan')
                ->cascadeOnDelete();
            $table->string('nama_rekrutmen');
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->enum('status', [
                'draft',
                'dibuka',
                'ditutup',
                'selesai',
            ])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('kuota')->nullable()->comment('Jumlah kuota penerimaan');
            $table->text('persyaratan')->nullable()->comment('Daftar persyaratan pendaftaran');

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_buka');
            $table->index('tanggal_tutup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekrutmen');
    }
};
