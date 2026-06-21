<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Log Override (SRS 3.4.6)
     * Mencatat override eligibility jabatan oleh pejabat berwenang
     */
    public function up(): void
    {
        Schema::create('log_override', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->cascadeOnDelete()
                ->comment('Anggota yang jabatannya di-override');
            $table->foreignId('pelaku_id')
                ->constrained('anggota')
                ->cascadeOnDelete()
                ->comment('Pejabat yang melakukan override');
            $table->string('jabatan_target')->comment('Jabatan yang di-assign via override');
            $table->text('alasan')->comment('Alasan override (min. 50 karakter)');
            $table->timestamp('created_at')->useCurrent();

            $table->index('anggota_id');
            $table->index('pelaku_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_override');
    }
};
