<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;

class AdminEscrowController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $allowedFilters = ['all', 'masuk', 'keluar', 'pending', 'expired'];
        if (!in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $sort = $request->query('sort', 'latest');
        $allowedSorts = ['latest', 'oldest', 'amount_desc', 'amount_asc'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'latest';
        }

        $escrowService = app(EscrowService::class);
        $currentBalance = $escrowService->getCurrentBalance();
        $pendingBalance = $escrowService->getPendingBalance();

        $expiringSoon = EscrowTransaction::expiringSoon(60)->count();

        $todayIn = EscrowTransaction::where('type', 'in')
            ->whereDate('created_at', today())
            ->sum('amount');

        $todayOut = EscrowTransaction::where('type', 'out')
            ->whereDate('created_at', today())
            ->sum('amount');

        $counts = [
            'all' => EscrowTransaction::count(),
            'masuk' => EscrowTransaction::where('type', 'in')->count(),
            'keluar' => EscrowTransaction::where('type', 'out')->count(),
            'pending' => EscrowTransaction::pending()->count(),
            'expired' => EscrowTransaction::expired()->count(),
        ];

        $query = EscrowTransaction::with(['payment.order.service', 'payment.order.buyer', 'order.service', 'processor']);

        if ($filter === 'masuk') {
            $query->where('type', 'in');
        } elseif ($filter === 'keluar') {
            $query->where('type', 'out');
        } elseif ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'expired') {
            $query->where('status', 'expired');
        }

        match ($sort) {
            'oldest' => $query->oldest(),
            'amount_desc' => $query->orderByDesc('amount'),
            'amount_asc' => $query->orderBy('amount'),
            default => $query->latest(),
        };

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.escrow.index', compact(
            'transactions',
            'filter',
            'sort',
            'counts',
            'currentBalance',
            'pendingBalance',
            'expiringSoon',
            'todayIn',
            'todayOut'
        ));
    }

    public function confirm(Request $request, EscrowTransaction $escrowTransaction)
    {
        if (!$escrowTransaction->isPending()) {
            return back()->with('error', 'Transaksi ini tidak bisa dikonfirmasi.');
        }

        if ($escrowTransaction->isOverdue()) {
            return back()->with('error', 'Transaksi sudah melewati batas waktu. Silakan refresh halaman.');
        }

        $escrowService = app(EscrowService::class);
        $success = $escrowService->confirmCredit($escrowTransaction, auth()->user());

        if ($success) {
            return back()->with('success', 'Saldo dikonfirmasi masuk. Seller telah diberi notifikasi untuk mengerjakan pesanan.');
        }

        return back()->with('error', 'Gagal mengkonfirmasi transaksi.');
    }

    public function reject(Request $request, EscrowTransaction $escrowTransaction)
    {
        if (!$escrowTransaction->isPending()) {
            return back()->with('error', 'Transaksi ini tidak bisa ditolak.');
        }

        $escrowService = app(EscrowService::class);
        $success = $escrowService->rejectCredit($escrowTransaction, auth()->user());

        if ($success) {
            return back()->with('success', 'Transaksi ditolak. Buyer telah diberi notifikasi.');
        }

        return back()->with('error', 'Gagal menolak transaksi.');
    }
}
