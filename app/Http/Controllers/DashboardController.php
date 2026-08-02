<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $stats = [
            'jasa_aktif' => $user->services()->where('status', 'approved')->count(),
            'jasa_pending' => $user->services()->where('status', 'pending')->count(),
            'pesanan_berjalan' => $user->orders()->whereNotIn('status', ['selesai'])->count(),
            'pesanan_selesai' => $user->orders()->where('status', 'selesai')->count(),
        ];

        $pesanan_terbaru = $user->orders()->with('service')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'pesanan_terbaru'));
    }
}