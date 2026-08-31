<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            // Jangan memakai redirect()->intended() untuk admin: nilai
            // "url.intended" di session (misalnya /dashboard atau /pesanan yang
            // diset middleware auth saat guest) akan menyuruh admin ke halaman
            // pengguna biasa. Paksa ke admin dashboard & bersihkan intended.
            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        // Cek jika intended URL adalah API endpoint, redirect ke dashboard
        $intended = $request->session()->get('url.intended');
        if ($intended && (str_contains($intended, '/unread-count') || str_contains($intended, '/api/') || str_contains($intended, 'json=1') || str_contains($intended, '/notifikasi'))) {
            $request->session()->forget('url.intended');
            return redirect()->route('dashboard');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
