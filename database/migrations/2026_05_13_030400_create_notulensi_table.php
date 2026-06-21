<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Notulensi (SRS 3.4.7)
     * Catatan rapat organisasi UKM Jurnalistik
     */
    public function up(): void
    {
        Schema::create('notulensi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal_rapat');
            $table->string('lokasi', 150)->nullable();
            $table->enum('jenis_rapat', [
                'rapat_rutin',
                'rapat_khusus',
                'rapat_evaluasi',
                'rapat_kerja',
                'rapat_pleno',
            ])->default('rapat_rutin');
            $table->longText('isi_notulensi');
            $table->foreignId('pencatat_id')
                ->constrained('anggota')
                ->restrictOnDelete()
                ->comment('Anggota yang mencatat notulensi');
            $table->json('daftar_hadir')->nullable()->comment('Array NIM anggota yang hadir');
            $table->string('file_lampiran')->nullable()->comment('Path file lampiran');

            $table->softDeletes();
            $table->timestamps();

            $table->index('tanggal_rapat');
            $table->index('jenis_rapat');
            $table->index('pencatat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notulensi');
    }
};
