<?php

namespace App\Http\Controllers;

use App\Models\PayoutRequest;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $requests = PayoutRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('wallet.index', [
            'balance' => $user->balance,
            'requests' => $requests,
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
                'status' => PayoutRequest::STATUS_COMPLETED,
                'processed_by' => $user->id,
                'processed_at' => now(),
            ]);

            $method = PayoutRequest::METHOD_LABELS[$data['method_type']] ?? strtoupper($data['method_type']);

            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'payout_completed',
                'title' => 'Pencairan berhasil',
                'message' => 'Penarikan saldo Rp'.number_format($data['amount'], 0, ',', '.').' ke '.$method.' ('.$data['account_identifier'].') telah diproses dan dananya dikirim.',
                'is_read' => false,
            ]);

            $ok = true;
        });

        if (! $ok) {
            return back()->withInput()->with('error', 'Saldo tidak mencukupi untuk penarikan tersebut.');
        }

        return redirect()->route('wallet.index')
            ->with('success', 'Saldo Rp'.number_format($data['amount'], 0, ',', '.').' berhasil dicairkan ke '.($data['method_type']).'.');
    }
}
