<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: Paksa ganti password jika first login (FR-01)
 * Redirect ke halaman ganti password jika is_first_login = true.
 * User bisa memilih untuk melewati (skip) — password diganti nanti.
 */
class ForceFirstPasswordChange
{
    /**
     * Daftar route yang dikecualikan dari pengecekan ini.
     */
    protected array $except = [
        'password.first-change',
        'password.first-change.update',
        'password.first-change.skip',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\Anggota $user */
            $user = Auth::user();

            // Skip first-password check when:
            // 1. Super Admin sedang impersonate (testing mode)
            // 2. APP_DEBUG=true dan user adalah super_admin (dev testing)
            if (session()->has('impersonating_role')) {
                return $next($request);
            }

            // Skip jika user sudah memilih "lewati" pada sesi ini
            if ($user->is_first_login
                && ! in_array($request->route()?->getName(), $this->except)
                && ! session('password_change_postponed')
            ) {
                return redirect()->route('password.first-change')
                    ->with('info', 'Anda dapat mengganti password sekarang atau melewatkannya dan mengganti nanti.');
            }
        }

        return $next($request);
    }
}
