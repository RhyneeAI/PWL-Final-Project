<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'   => \App\Http\Middleware\CheckRole::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // ── Helper: apakah request ini expects JSON? ─────────────────────
        // Dipakai agar response error bisa dibedakan: web redirect vs API json.

        // ── 401 Unauthenticated ──────────────────────────────────────────
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'code'    => 401,
                ], 401);
            }

            // Web: redirect ke halaman login
            return redirect()->guest(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        });

        // ── 403 Unauthorized ─────────────────────────────────────────────
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak.',
                    'code'    => 403,
                ], 403);
            }

            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan ini.');
        });

        // ── 404 Model Not Found ──────────────────────────────────────────
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            $model = class_basename($e->getModel());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "{$model} tidak ditemukan.",
                    'code'    => 404,
                ], 404);
            }

            return redirect()->back()->with('error', "{$model} tidak ditemukan.");
        });

        // ── 404 Route Not Found ──────────────────────────────────────────
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Halaman tidak ditemukan.',
                    'code'    => 404,
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        // ── 405 Method Not Allowed ───────────────────────────────────────
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Method tidak diizinkan.',
                    'code'    => 405,
                ], 405);
            }

            return redirect()->back()->with('error', 'Method tidak diizinkan.');
        });

        // ── 419 CSRF Token Mismatch ──────────────────────────────────────
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session telah kadaluarsa. Silakan refresh halaman.',
                    'code'    => 419,
                ], 419);
            }

            return redirect()->back()
                ->withInput($request->except('_token'))
                ->with('error', 'Session kadaluarsa. Silakan coba lagi.');
        });

        // ── 422 Validation ───────────────────────────────────────────────
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors'  => $e->errors(),
                    'code'    => 422,
                ], 422);
            }

            // Web: Laravel sudah auto-redirect with errors, tapi kita tambah flash
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dikirim tidak valid.');
        });

        // ── 429 Too Many Requests ────────────────────────────────────────
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan. Coba lagi sebentar.',
                    'code'    => 429,
                ], 429);
            }

            return redirect()->back()->with('error', 'Terlalu banyak percobaan. Silakan tunggu sebentar.');
        });

        // ── 500 General / Unexpected ─────────────────────────────────────
        $exceptions->render(function (Throwable $e, Request $request) {
            // Log semua unexpected error
            \Log::error('Unexpected error: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url'   => $request->fullUrl(),
                'user'  => $request->user()?->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => app()->isProduction()
                        ? 'Terjadi kesalahan pada server. Silakan coba lagi.'
                        : $e->getMessage(),
                    'code'    => 500,
                ], 500);
            }

            // Development: biarkan Laravel render default exception page (dengan stack trace)
            // Production: render custom view
            if (app()->isProduction()) {
                return response()->view('errors.500', [], 500);
            }
        });

    })->create();
