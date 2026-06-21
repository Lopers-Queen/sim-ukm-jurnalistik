<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add status column and make tanggal_mulai/tanggal_selesai nullable.
     * Controller uses enum status instead of boolean is_active.
     */
    public function up(): void
    {
        Schema::table('periode_kepengurusan', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'selesai', 'upcoming'])
                  ->default('upcoming')
                  ->after('is_active');

            // Make tanggal fields nullable (controller doesn't require them)
            $table->date('tanggal_mulai')->nullable()->change();
            $table->date('tanggal_selesai')->nullable()->change();
        });

        // Migrate is_active to status
        DB::table('periode_kepengurusan')->where('is_active', true)->update(['status' => 'aktif']);
        DB::table('periode_kepengurusan')->where('is_active', false)->update(['status' => 'selesai']);
    }

    public function down(): void
    {
        Schema::table('periode_kepengurusan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
