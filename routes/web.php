<?php

use App\Http\Controllers\AdminToolsController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AnggaranDivisiController;
use App\Http\Controllers\AnggaranEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\JadwalShiftController;
use App\Http\Controllers\KeaktifanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanPascaEventController;
use App\Http\Controllers\LogKeamananController;
use App\Http\Controllers\NaskahRedaksiController;
use App\Http\Controllers\NotulensiController;
use App\Http\Controllers\PergantianKepengurusanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekrutmenController;
use App\Http\Controllers\SuratPernyataanController;
use App\Http\Controllers\TemplateKepanitiaanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SIM UKM Jurnalistik
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

// Dashboard (FR-11)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'check.lockout', 'force.first.password'])
    ->name('dashboard');

// ── Authenticated Routes ─────────────────────
Route::middleware(['auth', 'check.lockout', 'force.first.password'])->group(function () {

    // Admin Tools (Super Admin + Debug Only)
    Route::get('admin/impersonate-roles', [AdminToolsController::class, 'impersonateRoles'])->name('admin.impersonate-roles');
    Route::post('admin/impersonate/{roleName}', [AdminToolsController::class, 'startImpersonate'])->name('admin.start-impersonate');
    Route::get('admin/stop-impersonate', [AdminToolsController::class, 'stopImpersonate'])->name('admin.stop-impersonate');

    // Profil (FR-10)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/foto', [ProfileController::class, 'updateFoto'])->name('profile.update-foto');
    Route::delete('/profile/foto', [ProfileController::class, 'deleteFoto'])->name('profile.delete-foto');

    // ── ORGANISASI ────────────────────────────

    // Manajemen Anggota (FR-02)
    Route::resource('anggota', AnggotaController::class);
    Route::post('anggota/{anggota}/reset-password', [AnggotaController::class, 'resetPassword'])
        ->name('anggota.reset-password');
    Route::post('anggota-bulk-reset-password', [AnggotaController::class, 'resetAllPasswords'])
        ->name('anggota.reset-all-passwords');
    Route::post('anggota-import', [AnggotaController::class, 'importExcel'])->name('anggota.import');
    Route::get('anggota-template', [AnggotaController::class, 'downloadTemplate'])->name('anggota.template');

    // Periode Kepengurusan (FR-16)
    Route::resource('periode', PeriodeController::class);

    // Pergantian Kepengurusan (FR-17)
    Route::get('pergantian', [PergantianKepengurusanController::class, 'index'])->name('pergantian.index');
    Route::post('pergantian/validate', [PergantianKepengurusanController::class, 'validateSusunan'])->name('pergantian.validate');
    Route::post('pergantian', [PergantianKepengurusanController::class, 'store'])->name('pergantian.store');

    // Manajemen Keaktifan (FR-13)
    Route::get('keaktifan', [KeaktifanController::class, 'index'])->name('keaktifan.index');
    Route::get('keaktifan/perpanjangan', [KeaktifanController::class, 'perpanjangan'])->name('keaktifan.perpanjangan');
    Route::post('keaktifan/perpanjangan', [KeaktifanController::class, 'submitPerpanjangan'])->name('keaktifan.submit-perpanjangan');
    Route::post('keaktifan/{anggota}/toggle', [KeaktifanController::class, 'toggleStatus'])->name('keaktifan.toggle');
    Route::post('keaktifan/batch-update', [KeaktifanController::class, 'batchUpdate'])->name('keaktifan.batch-update');

    // Rekrutmen (FR-05)
    Route::resource('rekrutmen', RekrutmenController::class);

    // ── KEGIATAN ──────────────────────────────

    // Event (FR-09) + Kepanitiaan (FR-14)
    Route::resource('event', EventController::class);
    Route::post('event/{event}/divisi-panitia', [EventController::class, 'addDivisiPanitia'])->name('event.add-divisi');
    Route::post('event/{event}/assign-panitia', [EventController::class, 'assignPanitia'])->name('event.assign-panitia');
    Route::delete('event/{event}/panitia/{panitia}', [EventController::class, 'removePanitia'])->name('event.remove-panitia');

    // Template Kepanitiaan (FR-19)
    Route::resource('template-kepanitiaan', TemplateKepanitiaanController::class);
    Route::post('template-kepanitiaan/{templateKepanitiaan}/duplicate', [TemplateKepanitiaanController::class, 'duplicate'])->name('template-kepanitiaan.duplicate');

    // Laporan Pasca Event (FR-23)
    Route::get('event/{event}/laporan/create', [LaporanPascaEventController::class, 'create'])->name('laporan-event.create');
    Route::post('event/{event}/laporan', [LaporanPascaEventController::class, 'store'])->name('laporan-event.store');
    Route::get('event/{event}/laporan/{laporan}', [LaporanPascaEventController::class, 'show'])->name('laporan-event.show');
    Route::post('event/{event}/laporan/{laporan}/finalize', [LaporanPascaEventController::class, 'finalize'])->name('laporan-event.finalize');
    Route::get('event/{event}/laporan/{laporan}/pdf', [LaporanPascaEventController::class, 'exportPdf'])->name('laporan-event.export-pdf');

    // Surat Pernyataan (FR-21)
    Route::get('surat-pernyataan', [SuratPernyataanController::class, 'index'])->name('surat-pernyataan.index');
    Route::get('surat-pernyataan/{suratPernyataan}', [SuratPernyataanController::class, 'show'])->name('surat-pernyataan.show');
    Route::post('surat-pernyataan/generate', [SuratPernyataanController::class, 'generate'])->name('surat-pernyataan.generate');
    Route::post('surat-pernyataan/{suratPernyataan}/upload-ttd', [SuratPernyataanController::class, 'uploadTtd'])->name('surat-pernyataan.upload-ttd');
    Route::post('surat-pernyataan/{suratPernyataan}/approve', [SuratPernyataanController::class, 'approve'])->name('surat-pernyataan.approve');
    Route::post('surat-pernyataan/{suratPernyataan}/reject', [SuratPernyataanController::class, 'reject'])->name('surat-pernyataan.reject');
    Route::get('surat-pernyataan/{suratPernyataan}/download', [SuratPernyataanController::class, 'download'])->name('surat-pernyataan.download');

    // ── ADMINISTRASI ──────────────────────────

    // Notulensi (FR-04)
    Route::resource('notulensi', NotulensiController::class);

    // Naskah Redaksi (FR-08)
    Route::resource('naskah', NaskahRedaksiController::class);
    Route::post('naskah/{naskah}/submit-review', [NaskahRedaksiController::class, 'submitReview'])->name('naskah.submit-review');
    Route::post('naskah/{naskah}/approve', [NaskahRedaksiController::class, 'approve'])->name('naskah.approve');
    Route::post('naskah/{naskah}/revisi', [NaskahRedaksiController::class, 'revisi'])->name('naskah.revisi');
    Route::post('naskah/{naskah}/publish', [NaskahRedaksiController::class, 'publish'])->name('naskah.publish');

    // Jadwal Piket (FR-06)
    Route::resource('jadwal', JadwalShiftController::class)->except('show');
    Route::post('jadwal/generate-acak', [JadwalShiftController::class, 'generateAcak'])->name('jadwal.generate-acak');

    // ── KEUANGAN ──────────────────────────────

    // Anggaran Divisi (FR-07)
    Route::resource('anggaran-divisi', AnggaranDivisiController::class)->except('show');

    // Anggaran Event (FR-18)
    Route::resource('anggaran-event', AnggaranEventController::class)->except('show');

    // ── LAPORAN & LOG ─────────────────────────

    // Laporan & Ekspor (FR-12)
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/anggota', [LaporanController::class, 'anggota'])->name('laporan.anggota');
    Route::get('laporan/anggota/export-pdf', [LaporanController::class, 'exportAnggotaPdf'])->name('laporan.anggota.pdf');
    Route::get('laporan/anggota/export-excel', [LaporanController::class, 'exportAnggotaExcel'])->name('laporan.anggota.excel');
    Route::get('laporan/event', [LaporanController::class, 'event'])->name('laporan.event');
    Route::get('laporan/event/export-pdf', [LaporanController::class, 'exportEventPdf'])->name('laporan.event.pdf');
    Route::get('laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('laporan/keuangan/export-pdf', [LaporanController::class, 'exportKeuanganPdf'])->name('laporan.keuangan.pdf');

    // Log Keamanan (FR-10) & Activity Log (FR-22)
    Route::get('keamanan/login-history', [LogKeamananController::class, 'loginHistory'])->name('keamanan.login-history');
    Route::get('keamanan/activity-log', [LogKeamananController::class, 'activityLog'])->name('keamanan.activity-log');
    Route::post('keamanan/unlock-account', [LogKeamananController::class, 'unlockAccount'])->name('keamanan.unlock-account');
});

require __DIR__ . '/auth.php';
