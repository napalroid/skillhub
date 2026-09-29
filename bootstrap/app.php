<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Midtrans sends a server-to-server POST, so it does not carry a
        // browser CSRF token. The payment signature is verified separately
        // by PaymentController before any order state is changed.
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);

        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'ensureApiRequest' => \App\Http\Middleware\EnsureApiRequest::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $exception, Request $request) {
            if ($request->routeIs('services.store')) {
                return back()->withInput()->withErrors([
                    'uploads' => 'Total ukuran gambar terlalu besar. Maksimal 2 MB per gambar dan 8 MB untuk seluruh pengajuan.',
                ]);
            }
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
