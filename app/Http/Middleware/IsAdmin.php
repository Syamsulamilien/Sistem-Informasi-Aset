<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan logika pengecekan seperti ini:
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Debug - uncomment untuk testing
        // dd([
        //     'user_id' => $user->id,
        //     'user_role' => $user->role,
        //     'is_admin' => $user->role === 'admin'
        // ]);
        
        // ✅ Pastikan ini benar
        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}