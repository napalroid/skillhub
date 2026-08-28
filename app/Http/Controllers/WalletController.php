<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\WalletTransaction;
use App\Services\PayoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    protected $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

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
            ->whereIn('status', [
                PayoutRequest::STATUS_PENDING,
                PayoutRequest::STATUS_PROCESSING,
                PayoutRequest::STATUS_COMPLETED,
                PayoutRequest::STATUS_FAILED
            ])
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
            'minAmount' => config('payout.min_amount', 5000),
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
            'minAmount' => config('payout.min_amount', 5000),
        ]);
    }

    public function withdrawVerify(Request $request, PayoutRequest $payoutRequest): View
    {
        $user = $request->user();

        if ($payoutRequest->user_id !== $user->id) {
            abort(403);
        }

        return view('wallet.withdraw-verify', [
            'payoutRequest' => $payoutRequest,
            'methods' => PayoutRequest::METHOD_LABELS,
        ]);
    }

    public function withdrawConfirm(Request $request, PayoutRequest $payoutRequest): View
    {
        $user = $request->user();

        if ($payoutRequest->user_id !== $user->id) {
            abort(403);
        }

        return view('wallet.withdraw-confirm', [
            'payoutRequest' => $payoutRequest,
            'methods' => PayoutRequest::METHOD_LABELS,
            'processingDelay' => config('payout.processing_delay_seconds', 10),
            'balance' => $user->balance,
        ]);
    }

    public function withdrawProcess(Request $request, PayoutRequest $payoutRequest): JsonResponse
    {
        $user = $request->user();

        if ($payoutRequest->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($payoutRequest->status !== PayoutRequest::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Payout request already processed',
            ], 400);
        }

        $result = $this->payoutService->processAutomaticPayout($payoutRequest);

        if ($result['success']) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'payout_completed',
                'title' => 'Transfer Berhasil',
                'message' => 'Penarikan Rp'.number_format($payoutRequest->amount, 0, ',', '.').' ke '.$payoutRequest->methodLabel().' ('.$payoutRequest->account_identifier.') telah berhasil.',
                'is_read' => false,
            ]);
        } else {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'payout_failed',
                'title' => 'Transfer Gagal',
                'message' => $result['message'],
                'is_read' => false,
            ]);
        }

        return response()->json($result);
    }

    public function withdrawStore(Request $request): RedirectResponse
    {
        $user = $request->user();

        $minAmount = config('payout.min_amount', 50000);
        
        $data = $request->validate([
                'amount' => ['required', 'numeric', 'min:'.$minAmount,
                function ($attribute, $value, $fail) use ($user) {
                    if ($value > $user->balance) {
                        $fail('Saldo tidak mencukupi untuk penarikan tersebut.');
                    }
                },
            ],
            'method_type' => ['required', 'string', 'in:dana,gopay,ovo,shopeepay,bank'],
            'account_identifier' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
        ], [
            'amount.min' => 'Minimal penarikan Rp'.number_format($minAmount, 0, ',', '.'),
        ]);

        $payoutRequest = null;
        $insufficientBalance = false;
        $needVerification = $this->payoutService->shouldVerifyAccount(
            $user,
            $data['method_type'],
            $data['account_identifier']
        );

        try {
            DB::transaction(function () use ($user, $data, &$payoutRequest, &$needVerification, &$insufficientBalance) {
                $fresh = User::whereKey($user->id)->lockForUpdate()->firstOrFail();

                if ($fresh->balance < $data['amount']) {
                    $insufficientBalance = true;
                    throw new \Exception('Saldo tidak mencukupi');
                }

                $oldBalance = $fresh->balance;

                // TIDAK POTONG SALDO DULU - hanya simpan info
                // $fresh->decrement('balance', $data['amount']);

                $fresh->update([
                    'payout_type' => $data['method_type'],
                    'payout_account' => $data['account_identifier'],
                    'payout_account_name' => $data['account_name'],
                ]);

                $payoutRequest = PayoutRequest::create([
                    'user_id' => $user->id,
                    'amount' => $data['amount'],
                    'method_type' => $data['method_type'],
                    'account_identifier' => $data['account_identifier'],
                    'account_name' => $data['account_name'],
                    'status' => PayoutRequest::STATUS_PENDING,
                ]);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => WalletTransaction::TYPE_DEBIT,
                    'amount' => $data['amount'],
                    'balance_before' => $oldBalance,
                    'balance_after' => $oldBalance,  // Belum dipotong, masih sama
                    'reference_type' => 'payout_request',
                    'reference_id' => $payoutRequest->id,
                    'description' => 'Penarikan saldo ke '.$data['method_type'].' '.$data['account_identifier'],
                    'status' => WalletTransaction::STATUS_PENDING,
                ]);
            });
        } catch (\Exception $e) {
            if ($insufficientBalance) {
                return back()->withInput()->with('error', 'Saldo tidak mencukupi untuk penarikan tersebut.');
            }
            throw $e;
        }

        if ($payoutRequest === null) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses penarikan.');
        }

        if ($needVerification) {
            return redirect()->route('wallet.withdraw.verify', [
                'payoutRequest' => $payoutRequest->id,
            ])->with('success', 'Verifikasi nomor rekening diperlukan.');
        }

        return redirect()->route('wallet.withdraw.confirm', [
            'payoutRequest' => $payoutRequest->id,
        ]);
    }

    public function withdrawCancel(Request $request, PayoutRequest $payoutRequest): RedirectResponse
    {
        $user = $request->user();

        if ($payoutRequest->user_id !== $user->id) {
            abort(403);
        }

        if ($payoutRequest->status !== PayoutRequest::STATUS_PENDING) {
            return redirect()->route('wallet.index')->with('error', 'Penarikan ini sudah diproses atau dibatalkan.');
        }

        DB::transaction(function () use ($user, $payoutRequest) {
            // TIDAK PERLU KEMBALIKAN SALDO karena belum dipotong
            // Hanya update status saja
            
            // Update status payout request
            $payoutRequest->update([
                'status' => PayoutRequest::STATUS_REJECTED,
                'failure_reason' => 'Dibatalkan oleh user',
            ]);

            // Update wallet transaction
            WalletTransaction::where('reference_id', $payoutRequest->id)
                ->where('reference_type', 'payout_request')
                ->where('type', WalletTransaction::TYPE_DEBIT)
                ->update([
                    'status' => WalletTransaction::STATUS_FAILED,
                    'description' => 'Penarikan dibatalkan oleh user #WD-'.$payoutRequest->id,
                ]);
        });

        return redirect()->route('wallet.index')->with('success', 'Penarikan telah dibatalkan.');
    }
}
