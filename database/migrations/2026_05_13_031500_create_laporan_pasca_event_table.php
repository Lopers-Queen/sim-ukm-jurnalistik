<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Laporan Pasca Event (SRS 3.4.18)
     * Laporan evaluasi setelah event selesai dilaksanakan
     */
    public function up(): void
    {
        Schema::create('laporan_pasca_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('event')
                ->cascadeOnDelete();
            $table->foreignId('pelapor_id')
                ->constrained('anggota')
                ->restrictOnDelete()
                ->comment('Anggota yang menyusun laporan');
            $table->text('ringkasan')->comment('Ringkasan pelaksanaan event');
            $table->longText('evaluasi')->nullable()->comment('Evaluasi pelaksanaan');
            $table->text('saran')->nullable()->comment('Saran untuk perbaikan');
            $table->unsignedInteger('jumlah_peserta')->nullable();
            $table->string('file_laporan')->nullable()->comment('Path file laporan (PDF)');
            $table->string('file_dokumentasi')->nullable()->comment('Path file foto/video dokumentasi');
            $table->timestamp('tanggal_submit')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('event_id');
            $table->index('pelapor_id');
            $table->unique('event_id', 'laporan_event_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pasca_event');
    }
};
