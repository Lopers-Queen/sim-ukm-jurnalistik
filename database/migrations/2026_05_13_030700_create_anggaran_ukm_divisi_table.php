<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Anggaran UKM per Divisi (SRS 3.4.10)
     * Pencatatan alokasi dan penggunaan anggaran per divisi/unit per bulan
     */
    public function up(): void
    {
        Schema::create('anggaran_ukm_divisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->constrained('periode_kepengurusan')
                ->cascadeOnDelete();
            $table->enum('divisi', [
                'fotografi',
                'pers_penyiaran',
                'videografi',
                'kominfo',
                'redaksi',
                'inventory',
                'bpi',
            ])->comment('Divisi/Unit/BPI pemilik anggaran');
            $table->unsignedTinyInteger('bulan')->comment('1-12');
            $table->year('tahun');
            $table->decimal('jumlah_anggaran', 15, 2)->default(0)->comment('Total alokasi anggaran');
            $table->decimal('jumlah_terpakai', 15, 2)->default(0)->comment('Total yang sudah terpakai');
            $table->text('keterangan')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['periode_id', 'divisi', 'bulan', 'tahun'], 'anggaran_divisi_bulan_unique');
            $table->index('divisi');
            $table->index(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran_ukm_divisi');
    }
};
