<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel utama anggota UKM Jurnalistik (menggantikan tabel users default Laravel).
     * Sesuai SRS v6.5 Section 3.4.1
     */
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique()->comment('Nomor Induk Mahasiswa (username login)');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('program_studi', 100)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('foto_profil')->nullable()->comment('Path file foto profil');

            // Posisi dalam organisasi
            $table->enum('divisi', [
                'fotografi',
                'pers_penyiaran',
                'videografi',
                'kominfo',
                'redaksi',
                'inventory',
            ])->nullable()->comment('Divisi/Unit yang diikuti');

            $table->enum('jabatan_struktural', [
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
            ])->default('anggota');

            $table->enum('status_keanggotaan', [
                'aktif',
                'pasif',
                'alumni',
            ])->default('aktif');

            $table->date('tanggal_bergabung');

            // Auth & Security (SRS FR-01)
            $table->boolean('is_first_login')->default(true)->comment('Wajib ganti password saat login pertama');
            $table->boolean('is_locked')->default(false)->comment('Akun terkunci karena gagal login 5x');
            $table->timestamp('locked_until')->nullable()->comment('Waktu unlock otomatis (15 menit)');
            $table->unsignedTinyInteger('failed_login_attempts')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->softDeletes();
            $table->timestamps();

            // Indexes untuk query yang sering digunakan
            $table->index('divisi');
            $table->index('jabatan_struktural');
            $table->index('status_keanggotaan');
            $table->index('tanggal_bergabung');
        });

        // Tabel password reset tokens (SRS 3.4.3)
        // Menggunakan NIM sebagai identifier, bukan email
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('nim', 20)->primary()->comment('NIM anggota');
            $table->string('token');
            $table->timestamp('expires_at')->nullable()->comment('Token kedaluwarsa setelah 60 menit');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel sessions (Laravel default)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
