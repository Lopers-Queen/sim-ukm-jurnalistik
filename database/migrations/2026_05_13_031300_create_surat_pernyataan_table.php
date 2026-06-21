<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Surat Pernyataan (SRS 3.4.16)
     * Surat pernyataan kesediaan anggota pasif berpartisipasi di kepanitiaan
     * Workflow: Pending TTD → Menunggu Konfirmasi → Disetujui/Ditolak
     */
    public function up(): void
    {
        Schema::create('surat_pernyataan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->restrictOnDelete()
                ->comment('Anggota pasif yang membuat surat');
            $table->foreignId('event_id')
                ->constrained('event')
                ->restrictOnDelete();
            $table->foreignId('anggota_panitia_id')
                ->nullable()
                ->constrained('anggota_panitia')
                ->nullOnDelete()
                ->comment('Referensi ke penugasan panitia');
            $table->string('nomor_surat', 50)->nullable()->unique()->comment('Nomor surat resmi');
            $table->enum('status', [
                'pending_ttd',
                'menunggu_konfirmasi',
                'disetujui',
                'ditolak',
            ])->default('pending_ttd');
            $table->string('file_pdf')->nullable()->comment('Path file PDF surat pernyataan');
            $table->string('file_ttd')->nullable()->comment('Path file scan/digital TTD');
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('anggota')
                ->nullOnDelete()
                ->comment('Ketum/Waketum yang menyetujui/menolak');
            $table->timestamp('tanggal_approval')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('anggota_id');
            $table->index('event_id');
            $table->index('status');
            $table->index('approver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pernyataan');
    }
};
