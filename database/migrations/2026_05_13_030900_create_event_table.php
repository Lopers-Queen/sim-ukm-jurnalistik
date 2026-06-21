<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Event (SRS 3.4.12)
     * Data kegiatan/event UKM Jurnalistik
     */
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->string('nama_event');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi', 200)->nullable();
            $table->enum('status', [
                'draft',
                'direncanakan',
                'aktif',
                'selesai',
                'batal',
            ])->default('draft');
            $table->foreignId('pic_id')
                ->nullable()
                ->constrained('anggota')
                ->nullOnDelete()
                ->comment('Penanggung jawab event (Ketua Panitia)');
            $table->decimal('anggaran_total', 15, 2)->default(0)->comment('Total anggaran yang diajukan');
            $table->foreignId('periode_id')
                ->nullable()
                ->constrained('periode_kepengurusan')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_mulai');
            $table->index('pic_id');
            $table->index('periode_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
