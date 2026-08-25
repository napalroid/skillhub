<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Validation\ValidationException;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

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
        Config::$overrideNotifUrl = config('midtrans.notification_url');

        // The PHP installation on Windows does not include a CA bundle by
        // default. Keep TLS verification enabled and explicitly provide the
        // Mozilla CA bundle used by the Midtrans SDK.
        $caBundle = config('midtrans.ca_bundle');
        if (is_string($caBundle) && $caBundle !== '' && is_file($caBundle)) {
            Config::$curlOptions = [
                CURLOPT_CAINFO => $caBundle,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER => [],
            ];
        } else {
            Config::$curlOptions = [];
        }

        $midtransOrderId = $order->midtrans_order_id ?: 'SKILLHUB-' . $order->id . '-' . now()->format('YmdHis');
        $response = CoreApi::charge([
            'payment_type' => 'qris',
            'transaction_details' => ['order_id' => $midtransOrderId, 'gross_amount' => (int) round((float) $order->final_price)],
            'item_details' => [['id' => (string) $order->service_id, 'price' => (int) round((float) $order->final_price), 'quantity' => 1, 'name' => str($order->service->title)->limit(50)->toString()]],
            'qris' => ['acquirer' => config('midtrans.qris_acquirer')],
        ]);

        return json_decode(json_encode($response), true);
    }

    /**
     * Cek status transaksi langsung ke Midtrans. Digunakan sebagai fallback
     * ketika webhook (server-to-server) gagal menjangkau server (misal tunnel
     * ngrok terputus). Mengembalikan array respons Midtrans apa adanya.
     */
    public function getStatus(string $orderId): ?array
    {
        if (blank(config('midtrans.server_key')) || blank($orderId)) {
            return null;
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $caBundle = config('midtrans.ca_bundle');
        if (is_string($caBundle) && $caBundle !== '' && is_file($caBundle)) {
            Config::$curlOptions = [
                CURLOPT_CAINFO => $caBundle,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER => [],
            ];
        } else {
            Config::$curlOptions = [];
        }

        try {
            $response = Transaction::status($orderId);
        } catch (\Throwable $exception) {
            report($exception);

            return null;
        }

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
