<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Template Kepanitiaan (SRS 3.4.15)
     * Template struktur kepanitiaan yang bisa digunakan ulang
     */
    public function up(): void
    {
        Schema::create('template_kepanitiaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->json('struktur')->comment('Struktur divisi & jabatan dalam format JSON');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_kepanitiaan');
    }
};
