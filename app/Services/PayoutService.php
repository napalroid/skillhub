<?php

namespace App\Services;

use App\Models\PayoutRequest;
use App\Models\WalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    public function processAutomaticPayout(PayoutRequest $payoutRequest): array
    {
        DB::beginTransaction();
        
        try {
            $user = User::whereKey($payoutRequest->user_id)->lockForUpdate()->firstOrFail();
            
            // CEK SALDO SEBELUM PROSES
            if ($user->balance < $payoutRequest->amount) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi',
                    'status' => 'failed',
                    'failure_reason' => 'Saldo tidak mencukupi',
                ];
            }
            
            // POTONG SALDO SEKARANG (saat proses)
            $oldBalance = $user->balance;
            $user->decrement('balance', $payoutRequest->amount);
            $user->refresh();
            
            $payoutRequest->update([
                'status' => PayoutRequest::STATUS_PROCESSING,
                'auto_processed' => true,
            ]);
            
            $delay = config('payout.processing_delay_seconds', 10);
            
            $successRate = config('payout.simulation_success_rate', 60);
            $randomNumber = rand(1, 100);
            $isSuccess = $randomNumber <= $successRate;
            
            if ($isSuccess) {
                $payoutRequest->update([
                    'status' => PayoutRequest::STATUS_COMPLETED,
                    'processed_at' => now(),
                ]);
                
                // Update transaction status jadi completed
                WalletTransaction::where('reference_id', $payoutRequest->id)
                    ->where('reference_type', 'payout_request')
                    ->where('type', 'debit')
                    ->update([
                        'status' => WalletTransaction::STATUS_COMPLETED,
                        'balance_after' => $user->balance,
                    ]);
                
                DB::commit();
                
                return [
                    'success' => true,
                    'message' => 'Transfer berhasil',
                    'status' => 'completed',
                ];
            } else {
                $failureReason = $this->generateRandomFailureReason();
                
                $payoutRequest->update([
                    'status' => PayoutRequest::STATUS_FAILED,
                    'failure_reason' => $failureReason,
                    'processed_at' => now(),
                ]);
                
                // Kembalikan saldo ke user (sudah dipotong di withdrawStore)
                $oldBalance = $user->balance;
                $user->increment('balance', $payoutRequest->amount);
                $user->refresh();
                
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $payoutRequest->amount,
                    'balance_before' => $oldBalance,
                    'balance_after' => $user->balance,
                    'reference_type' => 'payout_request',
                    'reference_id' => $payoutRequest->id,
                    'description' => 'Refund penarikan gagal #WD-'.$payoutRequest->id.' - '.$failureReason,
                    'status' => WalletTransaction::STATUS_COMPLETED,
                ]);
                
                DB::commit();
                
                return [
                    'success' => false,
                    'message' => 'Transfer gagal: '.$failureReason,
                    'status' => 'failed',
                    'failure_reason' => $failureReason,
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Payout process failed', [
                'payout_request_id' => $payoutRequest->id,
                'error' => $e->getMessage(),
            ]);
            
            $payoutRequest->update([
                'status' => PayoutRequest::STATUS_FAILED,
                'failure_reason' => 'Sistem error: '.$e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Sistem error: '.$e->getMessage(),
                'status' => 'failed',
                'failure_reason' => 'Sistem error: '.$e->getMessage(),
            ];
        }
    }
    
    private function generateRandomFailureReason(): string
    {
        $reasons = [
            'Nomor rekening tidak valid',
            'E-wallet tidak dapat diakses saat ini',
            'Transaksi ditolak oleh gateway',
            'Saldo gateway tidak mencukupi',
            'Koneksi gagal dengan server pembayaran',
        ];
        
        return $reasons[array_rand($reasons)];
    }
    
    public function shouldVerifyAccount(User $user, string $methodType, string $accountIdentifier): bool
    {
        // Jika belum pernah withdraw, tidak perlu verifikasi
        if ($user->payout_account === null) {
            return false;
        }
        
        // Cek apakah metode pembayaran berubah
        if ($user->payout_type !== $methodType) {
            return true;
        }
        
        // Cek apakah nomor rekening berubah
        if ($user->payout_account !== $accountIdentifier) {
            return true;
        }
        
        return false;
    }
}
