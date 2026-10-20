<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika tidak ada role spesifik yang didefinisikan, izinkan
        if (empty($roles)) {
            return $next($request);
        }

        // Parse semua role yang diizinkan (mendukung format 'admin,teknisi' atau 'admin', 'teknisi')
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $trimmed = trim($r);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // Cek apakah role user ada dalam daftar yang diizinkan (super_admin otomatis mewarisi hak admin)
        $hasAccess = in_array($user->role, $allowedRoles, true)
            || ($user->role === 'super_admin' && in_array('admin', $allowedRoles, true));

        if (!$hasAccess) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses tindakan atau endpoint ini.');
        }

        return $next($request);
    }
}
