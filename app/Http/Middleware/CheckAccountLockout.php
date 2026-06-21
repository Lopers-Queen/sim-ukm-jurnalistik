<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: Cek Account Lockout (FR-01)
 * Block akses jika akun terkunci (5x gagal login = lock 15 menit).
 */
class CheckAccountLockout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\Anggota $user */
            $user = Auth::user();

            if ($user->isLocked()) {
                Auth::logout();
                $request->session()->invalidate();

                return redirect()->route('login')
                    ->withErrors(['nim' => 'Akun Anda sedang terkunci. Silakan coba lagi nanti.']);
            }
        }

        return $next($request);
    }
}
