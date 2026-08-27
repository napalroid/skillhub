<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get balance from user
        $balance = $user->balance;

        // Get wallet transactions with eager loading
        $filter = $request->query('filter', 'all');
        $query = WalletTransaction::where('user_id', $user->id)->with(['order', 'payoutRequest']);

        switch ($filter) {
            case 'income':
                $query->where('type', 'credit');
                break;
            case 'expense':
                $query->where('type', 'debit');
                break;
            default:
                // all - include both credit and debit
                break;
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Get withdrawal requests (for pending/processing withdrawals)
        $withdrawals = PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'completed'])
            ->latest()
            ->limit(3)
            ->get();

        return view('wallet.index', [
            'balance' => $balance,
            'transactions' => $transactions,
            'withdrawals' => $withdrawals,
            'filter' => $filter,
            'methods' => PayoutRequest::METHOD_LABELS,
            'default' => [
                'type' => $user->payout_type,
                'account' => $user->payout_account,
                'name' => $user->payout_account_name,
            ],
        ]);
    }

    public function withdrawCreate(Request $request): View
    {
        $user = $request->user();

        return view('wallet.withdraw', [
            'balance' => $user->balance,
            'methods' => PayoutRequest::METHOD_LABELS,
            'default' => [
                'type' => $user->payout_type,
                'account' => $user->payout_account,
                'name' => $user->payout_account_name,
            ],
        ]);
    }

    public function withdrawStore(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
            'method_type' => ['required', 'string', 'in:dana,gopay,ovo,shopeepay,bank'],
            'account_identifier' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
        ], [
            'amount.min' => 'Minimal penarikan Rp10.000.',
        ]);

        $ok = false;
        DB::transaction(function () use ($user, $data, &$ok) {
            $fresh = User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            if ($fresh->balance < $data['amount']) {
                return;
            }

            $oldBalance = $fresh->balance;

            $fresh->decrement('balance', $data['amount']);

            $fresh->update([
                'payout_type' => $data['method_type'],
                'payout_account' => $data['account_identifier'],
                'payout_account_name' => $data['account_name'],
            ]);

            PayoutRequest::create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'method_type' => $data['method_type'],
                'account_identifier' => $data['account_identifier'],
                'account_name' => $data['account_name'],
                'status' => PayoutRequest::STATUS_PENDING,
            ]);

            // Create wallet transaction for withdrawal
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $data['amount'],
                'balance_before' => $oldBalance,
                'balance_after' => $fresh->balance,
                'reference_type' => 'payout_request',
                'reference_id' => PayoutRequest::orderByDesc('id')->first()->id,
                'description' => 'Penarikan saldo ke '.$data['method_type'].' '.$data['account_identifier'],
                'status' => 'pending',
            ]);

            $ok = true;
        });

        if (! $ok) {
            return back()->withInput()->with('error', 'Saldo tidak mencukupi untuk penarikan tersebut.');
        }

        return redirect()->route('wallet.index')
            ->with('success', 'Penarikan saldo berhasil diajukan. Permintaan sedang diproses.');
    }
}
