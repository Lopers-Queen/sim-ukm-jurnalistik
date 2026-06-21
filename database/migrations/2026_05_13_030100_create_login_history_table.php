<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Login History (SRS 3.4.2)
     * Mencatat setiap percobaan login (berhasil/gagal/terkunci)
     */
    public function up(): void
    {
        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->cascadeOnDelete()
                ->comment('FK ke tabel anggota');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->enum('status', ['success', 'failed', 'locked'])
                ->comment('Hasil percobaan login');
            $table->text('keterangan')->nullable()->comment('Detail tambahan (misal: alasan lock)');
            $table->timestamp('attempted_at')->useCurrent();

            $table->index('anggota_id');
            $table->index('status');
            $table->index('attempted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_history');
    }
};
