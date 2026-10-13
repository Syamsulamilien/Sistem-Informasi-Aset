<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Menambahkan HTTP response headers untuk meningkatkan keamanan web terhadap clickjacking,
     * MIME sniffing, XSS, dan kebocoran referrer.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Header proteksi clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Header proteksi MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Header proteksi Reflected XSS
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Header referensi kebijakan URL
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Proteksi akses fitur browser sensitif
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
