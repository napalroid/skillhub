<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Report;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        // Statistik
        $totalStudents = User::where('role', 'user')->count();
        $totalServices = Service::where('status', 'approved')->count();
        $pendingServices = Service::where('status', 'pending')->latest()->get();
        $pendingCount = $pendingServices->count();
        $totalOrders = Order::count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalServices',
            'pendingServices',
            'pendingCount',
            'totalOrders',
            'pendingPayments'
        ));
    }

    // ==================== APPROVE JASA ====================
    public function approveService(Service $service)
    {
        if ($service->status !== 'pending') {
            return back()->with('error', 'Jasa ini sudah diproses.');
        }
        $service->update(['status' => 'approved']);

        UserNotification::create([
            'user_id' => $service->user_id,
            'service_id' => $service->id,
            'type' => 'approved',
            'title' => "Jasa disetujui ({$service->title})",
            'message' => "Selamat! Jasa kamu \"{$service->title}\" telah disetujui dan tampil di marketplace.",
            'is_read' => false,
        ]);

        return back()->with('success', 'Jasa berhasil disetujui dan dipublikasikan.');
    }

    // ==================== REJECT JASA ====================
    public function rejectService(Service $service)
    {
        if ($service->status !== 'pending') {
            return back()->with('error', 'Jasa ini sudah diproses.');
        }
        $service->update(['status' => 'rejected']);

        UserNotification::create([
            'user_id' => $service->user_id,
            'service_id' => $service->id,
            'type' => 'rejected',
            'title' => "Jasa ditolak ({$service->title})",
            'message' => "Mohon maaf, jasa kamu \"{$service->title}\" ditolak admin. Kamu dapat mengajukan ulang.",
            'is_read' => false,
        ]);

        return back()->with('success', 'Jasa ditolak. User dapat mengajukan ulang.');
    }

    // ==================== RELEASE DANA (ESCROW) ====================
    public function releaseFunds(Order $order)
    {
        // Logika pencairan dana ke seller
        if ($order->status !== 'completed') {
            return back()->with('error', 'Pesanan belum selesai.');
        }
        // Misal kita ubah status payment menjadi 'released'
        $payment = $order->payment;
        if ($payment && $payment->status === 'verified') {
            $payment->update(['status' => 'released']);
            return back()->with('success', 'Dana berhasil dicairkan ke penjual.');
        }
        return back()->with('error', 'Pembayaran belum terverifikasi.');
    }
}   