<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Auto-redirect jika endpoint / model tidak ditemukan (404)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if (!$request->expectsJson()) {
                if (\Illuminate\Support\Facades\Auth::check()) {
                    return redirect()->route('dashboard')->with('error', 'Halaman atau data yang Anda tuju tidak ditemukan.');
                }
                return redirect()->route('login')->with('error', 'Halaman tidak ditemukan. Silakan login terlebih dahulu.');
            }
        });

        // Auto-redirect jika hak akses ditolak (403)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, \Illuminate\Http\Request $request) {
            if (!$request->expectsJson()) {
                $message = $e->getMessage() ?: 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman tersebut.';
                if (\Illuminate\Support\Facades\Auth::check()) {
                    return redirect()->route('dashboard')->with('error', $message);
                }
                return redirect()->route('login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
            }
        });

        // Auto-redirect untuk HttpException umum (status 403 / 404)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if (!$request->expectsJson()) {
                if ($e->getStatusCode() === 403) {
                    $message = $e->getMessage() ?: 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman tersebut.';
                    if (\Illuminate\Support\Facades\Auth::check()) {
                        return redirect()->route('dashboard')->with('error', $message);
                    }
                    return redirect()->route('login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
                }

                if ($e->getStatusCode() === 404) {
                    if (\Illuminate\Support\Facades\Auth::check()) {
                        return redirect()->route('dashboard')->with('error', 'Halaman atau data yang Anda tuju tidak ditemukan.');
                    }
                    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan. Silakan login terlebih dahulu.');
                }
            }
        });
    })->create();