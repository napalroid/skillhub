<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
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

        $jasa_saya = $user->services()->with(['subcategory.category'])->latest()->get();

        // Statistik komunitas (angka real dari database)
        $platformStats = [
            'total_user' => User::count(),
            'total_jasa' => Service::count(),
            'total_jasa_aktif' => Service::where('status', 'approved')->count(),
            'total_kategori' => Category::count(),
        ];

        // Rekomendasi jasa dari siswa lain (agar halaman selalu terisi)
        $rekomendasi_jasa = Service::approved()
            ->with(['seller', 'subcategory'])
            ->where('user_id', '!=', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('stats', 'pesanan_terbaru', 'jasa_saya', 'platformStats', 'rekomendasi_jasa'));
    }
}
