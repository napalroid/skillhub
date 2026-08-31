<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika request tidak diharapkan sebagai API (bukan AJAX/json), redirect ke dashboard
        if (!$request->expectsJson() && !$request->ajax() && !$request->wantsJson()) {
            // Izinkan akses ke /login, /register, dll
            if ($request->is('login') || $request->is('register') || $request->is('forgot-password')) {
                return $next($request);
            }
            
            // Redirect API endpoints yang diakses langsung ke dashboard
            if ($request->is('api/*') || $request->is('notifikasi/unread-count')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
