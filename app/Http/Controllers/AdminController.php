<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\Payment;
use App\Models\Report;
use App\Models\Service;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard(Request $request)
    {
        // Statistik dasar
        $totalStudents = User::where('role', 'user')->count();
        $totalServices = Service::where('status', 'approved')->count();
        $pendingServices = Service::where('status', 'pending')->latest()->get();
        $pendingCount = $pendingServices->count();
        $totalOrders = Order::count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        $chartPeriod = $request->query('period', 'monthly');
        $chartPeriods = ['daily', 'weekly', 'monthly'];
        if (! in_array($chartPeriod, $chartPeriods, true)) {
            $chartPeriod = 'monthly';
        }

        [$chartBuckets, $chartMaxOffset] = match ($chartPeriod) {
            'daily' => [7, 1088],
            'weekly' => [8, 148],
            default => [6, 30],
        };

        $chartOffset = max(0, min((int) $request->query('offset', 0), $chartMaxOffset));
        $chartEnd = match ($chartPeriod) {
            'daily' => now()->subDays($chartOffset)->endOfDay(),
            'weekly' => now()->subWeeks($chartOffset)->endOfWeek(),
            default => now()->subMonths($chartOffset)->endOfMonth(),
        };
        $chartStart = match ($chartPeriod) {
            'daily' => $chartEnd->copy()->subDays($chartBuckets - 1)->startOfDay(),
            'weekly' => $chartEnd->copy()->subWeeks($chartBuckets - 1)->startOfWeek(),
            default => $chartEnd->copy()->subMonths($chartBuckets - 1)->startOfMonth(),
        };

        $chartOrdersQuery = Order::query()
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('COALESCE(SUM(final_price), 0) as total_revenue')
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->where('status', '!=', 'menunggu_pembayaran');

        $chartGroupExpression = match ($chartPeriod) {
            'daily' => 'DATE(created_at)',
            'weekly' => 'YEARWEEK(created_at, 1)',
            default => 'DATE_FORMAT(created_at, "%Y-%m")',
        };

        $salesByBucket = $chartOrdersQuery
            ->selectRaw("{$chartGroupExpression} as bucket")
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $chartLabels = [];
        $chartOrders = [];
        $chartRevenue = [];

        for ($i = $chartBuckets - 1; $i >= 0; $i--) {
            $date = match ($chartPeriod) {
                'daily' => $chartEnd->copy()->subDays($i),
                'weekly' => $chartEnd->copy()->subWeeks($i)->startOfWeek(),
                default => $chartEnd->copy()->subMonths($i)->startOfMonth(),
            };

            $bucket = match ($chartPeriod) {
                'daily' => $date->format('Y-m-d'),
                'weekly' => $date->format('oW'),
                default => $date->format('Y-m'),
            };

            $chartLabels[] = match ($chartPeriod) {
                'daily' => $date->translatedFormat('D, d M'),
                'weekly' => 'Minggu '.$date->weekOfYear,
                default => $date->translatedFormat('M Y'),
            };
            $chartOrders[] = (int) ($salesByBucket[$bucket]->total_orders ?? 0);
            $chartRevenue[] = (float) ($salesByBucket[$bucket]->total_revenue ?? 0);
        }

        // Saldo escrow (dana ditahan)
        $escrowBalance = Payment::where('status', 'verified')
            ->sum('amount');

        // Laporan pending dari order yang sudah dibayar
        $pendingReports = Report::where('status', 'open')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })
            ->with(['order.service', 'reporter', 'reportedUser'])
            ->latest()
            ->take(5)
            ->get();

        // Kategori & Subkategori untuk management
        $categories = Category::with('subcategories')->orderBy('name')->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalServices',
            'pendingServices',
            'pendingCount',
            'totalOrders',
            'pendingPayments',
            'chartPeriod',
            'chartOffset',
            'chartMaxOffset',
            'chartStart',
            'chartEnd',
            'chartLabels',
            'chartOrders',
            'chartRevenue',
            'escrowBalance',
            'pendingReports',
            'categories'
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
    /**
     * Cairkan manual (override delay 1 jam) — hanya untuk pesanan yang sudah selesai.
     * Dana masuk ke saldo dompet seller, anti double-payout via lockForUpdate.
     */
    public function releaseFunds(Order $order)
    {
        if ($order->status !== Order::STATUS_SELESAI) {
            return back()->with('error', 'Pesanan belum selesai (buyer belum menekan Selesaikan Pesanan).');
        }

        $payment = $order->payment;
        if (! $payment || $payment->status !== 'verified') {
            return back()->with('error', 'Pembayaran belum di-escrow / sudah diproses.');
        }

        $seller = $order->service?->seller;
        if (! $seller) {
            return back()->with('error', 'Seller tidak ditemukan.');
        }

        $released = false;
        DB::transaction(function () use ($payment, $seller, $order, &$released) {
            $fresh = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            if ($fresh->status !== 'verified') {
                return;
            }

            $fresh->update([
                'status' => 'released',
                'released_at' => now(),
                'released_by' => auth()->id(),
            ]);

            User::whereKey($seller->id)->lockForUpdate()->increment('balance', $fresh->amount);

            UserNotification::create([
                'user_id' => $seller->id,
                'order_id' => $order->id,
                'payment_id' => $fresh->id,
                'service_id' => $order->service_id,
                'type' => 'payout_released',
                'title' => 'Dana pesanan cair',
                'message' => 'Dana pesanan #'.$order->id.' "'.($order->service?->title ?? 'jasa').'" sebesar Rp'.number_format($fresh->amount, 0, ',', '.').' telah cair ke saldo dompet Anda. Cek & tarik di halaman Dompet.',
                'is_read' => false,
            ]);

            $released = true;
        });

        if (! $released) {
            return back()->with('error', 'Dana sudah diproses atau belum di-escrow.');
        }

        return back()->with('success', 'Dana berhasil dicairkan ke penjual.');
    }

    // ==================== REFUND / BATALKAN PESANAN (C3) ====================
    /**
     * Admin membatalkan pesanan dan mengembalikan dana ke buyer.
     * Boleh dilakukan selama dana belum cair ke seller.
     */
    public function refundOrder(Order $order)
    {
        if ($order->isCancelled()) {
            return back()->with('error', 'Pesanan ini sudah dibatalkan.');
        }

        $payment = $order->payment;
        if ($payment && $payment->status === 'released') {
            return back()->with('error', 'Dana sudah cair ke seller, tidak bisa refund.');
        }
        if ($payment && $payment->status === 'refunded') {
            return back()->with('error', 'Pesanan ini sudah dikembalikan.');
        }

        DB::transaction(function () use ($payment, $order) {
            $order->update(['status' => Order::STATUS_DIBATALKAN]);

            if ($payment) {
                $fresh = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                if (in_array($fresh->status, ['verified', 'paid', 'pending', 'rejected'], true)) {
                    $fresh->update([
                        'status' => 'refunded',
                        'released_at' => now(),
                        'released_by' => auth()->id(),
                    ]);
                }
            }

            UserNotification::create([
                'user_id' => $order->buyer_id,
                'order_id' => $order->id,
                'payment_id' => $payment?->id,
                'service_id' => $order->service_id,
                'type' => 'order_refunded',
                'title' => 'Pesanan dikembalikan',
                'message' => 'Pesanan #'.$order->id.' dibatalkan dan dana dikembalikan ke Anda.',
                'is_read' => false,
            ]);
        });

        return back()->with('success', 'Pesanan dibatalkan dan dana dikembalikan ke buyer.');
    }

    // ==================== PENCAIRAN DANA (PAYOUT) ====================
    public function payoutIndex(Request $request)
    {
        $status = $request->status ?? 'all';

        $query = PayoutRequest::with('user')->latest();
        if (in_array($status, ['pending', 'completed', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $counts = [
            'all' => PayoutRequest::count(),
            'pending' => PayoutRequest::where('status', 'pending')->count(),
            'completed' => PayoutRequest::where('status', 'completed')->count(),
            'rejected' => PayoutRequest::where('status', 'rejected')->count(),
        ];

        $payouts = $query->paginate(15)->withQueryString();

        return view('admin.payouts.index', compact('payouts', 'counts', 'status'));
    }

    public function payoutProcess(PayoutRequest $payoutRequest)
    {
        if (! $payoutRequest->isPending()) {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $payoutRequest->update([
            'status' => PayoutRequest::STATUS_COMPLETED,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        UserNotification::create([
            'user_id' => $payoutRequest->user_id,
            'type' => 'payout_completed',
            'title' => 'Pencairan diproses',
            'message' => 'Permintaan pencairan Rp'.number_format($payoutRequest->amount, 0, ',', '.').' ke '.$payoutRequest->methodLabel().' ('.$payoutRequest->account_identifier.') telah diproses dan dananya dikirim.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pencairan ditandai selesai.');
    }

    public function payoutReject(Request $request, PayoutRequest $payoutRequest)
    {
        if (! $payoutRequest->isPending()) {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($payoutRequest, $request) {
            User::whereKey($payoutRequest->user_id)->lockForUpdate()->increment('balance', $payoutRequest->amount);

            $payoutRequest->update([
                'status' => PayoutRequest::STATUS_REJECTED,
                'admin_note' => $request->admin_note,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            UserNotification::create([
                'user_id' => $payoutRequest->user_id,
                'type' => 'payout_rejected',
                'title' => 'Pencairan ditolak',
                'message' => 'Permintaan pencairan Rp'.number_format($payoutRequest->amount, 0, ',', '.').' ditolak'.
                    ($request->admin_note ? ': '.$request->admin_note : '.').' Saldo dikembalikan ke dompet Anda.',
                'is_read' => false,
            ]);
        });

        return back()->with('success', 'Pencairan ditolak & saldo dikembalikan.');
    }

    // ==================== PENDING SERVICES ====================
    public function pendingServices()
    {
        $pendingServices = Service::with(['seller', 'subcategory.category'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.services.pending', compact('pendingServices'));
    }

    // ==================== SERVICES MANAGEMENT (ALL) ====================
    public function servicesIndex(Request $request)
    {
        $query = Service::with(['seller', 'subcategory.category'])->latest();

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('seller', fn ($sq) => $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        // Sort
        match ($request->get('sort', 'latest')) {
            'oldest' => $query->oldest(),
            'price_high' => $query->orderByDesc('price'),
            'price_low' => $query->orderBy('price'),
            'title_asc' => $query->orderBy('title'),
            default => $query->latest(),
        };

        $services = $query->paginate(15)->withQueryString();

        return view('admin.services.index', compact('services'));
    }
}
