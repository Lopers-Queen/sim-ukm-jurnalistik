<?php

use App\Http\Controllers\Auth\FirstPasswordChangeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes — SIM UKM Jurnalistik
|--------------------------------------------------------------------------
| Registrasi publik dihapus. Anggota hanya ditambahkan oleh admin (BPI).
| Login menggunakan NIM sebagai username.
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    // Forgot Password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    // First Password Change (wajib untuk first login)
    Route::get('ganti-password-pertama', [FirstPasswordChangeController::class, 'show'])->name('password.first-change');
    Route::post('ganti-password-pertama', [FirstPasswordChangeController::class, 'update'])->name('password.first-change.update');
    Route::post('lewati-ganti-password', [FirstPasswordChangeController::class, 'skip'])->name('password.first-change.skip');

    // Profile Password Update (ubah password dari halaman Profil)
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
