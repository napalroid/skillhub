<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Validation\ValidationException;
use Midtrans\Config;
use Midtrans\CoreApi;

class MidtransService
{
    public function createQrisCharge(Order $order): array
    {
        if (blank(config('midtrans.server_key'))) {
            throw ValidationException::withMessages(['payment' => 'MIDTRANS_SERVER_KEY Sandbox belum dikonfigurasi.']);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $midtransOrderId = $order->midtrans_order_id ?: 'SKILLHUB-' . $order->id . '-' . now()->format('YmdHis');
        $response = CoreApi::charge([
            'payment_type' => 'qris',
            'transaction_details' => ['order_id' => $midtransOrderId, 'gross_amount' => (int) round((float) $order->final_price)],
            'item_details' => [['id' => (string) $order->service_id, 'price' => (int) round((float) $order->final_price), 'quantity' => 1, 'name' => str($order->service->title)->limit(50)->toString()]],
            'qris' => ['acquirer' => config('midtrans.qris_acquirer')],
        ]);

        return json_decode(json_encode($response), true);
    }

    public function isValidSignature(array $payload): bool
    {
        if (blank(config('midtrans.server_key')) || empty($payload['order_id']) || empty($payload['status_code']) || ! array_key_exists('gross_amount', $payload) || empty($payload['signature_key'])) {
            return false;
        }

        $signature = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('midtrans.server_key'));

        return hash_equals($signature, $payload['signature_key']);
    }
}
