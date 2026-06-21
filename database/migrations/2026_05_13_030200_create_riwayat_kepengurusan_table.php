<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Riwayat Kepengurusan (SRS 3.4.4)
     * Mencatat riwayat jabatan anggota di setiap periode kepengurusan
     */
    public function up(): void
    {
        Schema::create('riwayat_kepengurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->cascadeOnDelete();
            $table->foreignId('periode_id')
                ->constrained('periode_kepengurusan')
                ->cascadeOnDelete();

            $table->enum('jabatan', [
                'ketua_umum',
                'wakil_ketua_umum',
                'sekretaris_umum_1',
                'sekretaris_umum_2',
                'bendahara_umum_1',
                'bendahara_umum_2',
                'kadiv_fotografi',
                'kadiv_pers_penyiaran',
                'kadiv_videografi',
                'kanit_kominfo',
                'kanit_redaksi',
                'kanit_inventory',
                'staf',
                'anggota',
            ]);

            $table->enum('divisi', [
                'fotografi',
                'pers_penyiaran',
                'videografi',
                'kominfo',
                'redaksi',
                'inventory',
            ])->nullable()->comment('Divisi/unit tempat bertugas');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Satu anggota hanya 1 jabatan per periode
            $table->unique(['anggota_id', 'periode_id'], 'riwayat_anggota_periode_unique');
            $table->index('jabatan');
            $table->index('divisi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kepengurusan');
    }
};
