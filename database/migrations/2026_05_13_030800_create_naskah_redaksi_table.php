<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Naskah Redaksi (SRS 3.4.11)
     * Karya tulis/naskah yang dikelola unit Redaksi
     */
    public function up(): void
    {
        Schema::create('naskah_redaksi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('konten')->comment('Isi naskah lengkap');
            $table->foreignId('penulis_id')
                ->constrained('anggota')
                ->restrictOnDelete()
                ->comment('Anggota yang menulis naskah');
            $table->foreignId('editor_id')
                ->nullable()
                ->constrained('anggota')
                ->nullOnDelete()
                ->comment('Anggota yang mengedit/review naskah');
            $table->enum('status', [
                'draft',
                'review',
                'revisi',
                'disetujui',
                'ditolak',
                'published',
            ])->default('draft');
            $table->string('kategori', 50)->nullable()->comment('Kategori naskah (berita, opini, feature, dll)');
            $table->timestamp('tanggal_publish')->nullable();
            $table->text('catatan_editor')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('penulis_id');
            $table->index('editor_id');
            $table->index('status');
            $table->index('kategori');
            $table->index('tanggal_publish');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naskah_redaksi');
    }
};
