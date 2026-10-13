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

        // Header proteksi Reflected XSS (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Header referensi kebijakan URL
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Proteksi akses fitur browser sensitif
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy — cegah inline script injeksi XSS
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net cdnjs.cloudflare.com unpkg.com; " .
            "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com unpkg.com; " .
            "font-src 'self' fonts.gstatic.com cdnjs.cloudflare.com data:; " .
            "img-src 'self' data: blob: *; " .
            "connect-src 'self'; " .
            "frame-ancestors 'self'; " .
            "form-action 'self'; " .
            "base-uri 'self';"
        );

        // HSTS — paksa HTTPS (aktif otomatis saat production pakai HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
