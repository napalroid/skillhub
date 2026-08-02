<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Report;


class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_user' => User::where('role', 'user')->count(),
            'jasa_pending' => Service::where('status', 'pending')->count(),
            'pembayaran_pending' => Payment::where('status', 'pending')->count(),
            'pesanan_berjalan' => Order::whereNotIn('status', ['selesai'])->count(),
            'laporan_terbuka' => Report::where('status', 'open')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function approveService(Service $service)
    {
        $service->update(['status' => 'approved']);
        return back()->with('success', 'Jasa berhasil disetujui.');
    }

    public function rejectService(Service $service)
    {
        $service->update(['status' => 'rejected']);
        return back()->with('success', 'Jasa ditolak.');
    }


public function releaseFunds(Order $order)
{
    if ($order->status !== 'selesai') {
        return back()->with('error', 'Pesanan belum disetujui buyer, dana belum bisa dicairkan.');
    }

    // Di dunia nyata, di sini bisa dicatat sebagai transaksi pencairan terpisah.
    // Untuk MVP, kita tandai lewat kolom di payment.
    $order->payment->update(['status' => 'verified']);

    return back()->with('success', 'Dana berhasil dicairkan ke seller: ' . $order->service->seller->name);
}

}