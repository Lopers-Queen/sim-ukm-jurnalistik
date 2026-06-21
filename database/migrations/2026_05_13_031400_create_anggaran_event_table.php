<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Anggaran Event (SRS 3.4.17)
     * Detail item anggaran per event (RAB & realisasi)
     */
    public function up(): void
    {
        Schema::create('anggaran_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('event')
                ->cascadeOnDelete();
            $table->string('item')->comment('Nama item pengeluaran');
            $table->string('kategori', 100)->nullable()->comment('Kategori pengeluaran (konsumsi, transportasi, dll)');
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('jumlah_dianggarkan', 15, 2)->default(0)->comment('Total RAB');
            $table->decimal('jumlah_realisasi', 15, 2)->default(0)->comment('Total realisasi pengeluaran');
            $table->string('bukti_transaksi')->nullable()->comment('Path file bukti transaksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('event_id');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran_event');
    }
};
