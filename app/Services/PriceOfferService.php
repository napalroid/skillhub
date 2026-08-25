<?php

namespace App\Services;

use App\Enums\PriceOfferStatus;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\PriceOffer;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PriceOfferService
{
    public function create(Conversation $conversation, User $seller, float $offerPrice, ?string $note): PriceOffer
    {
        if ($conversation->seller_id !== $seller->id) {
            throw new AuthorizationException('Hanya penyedia jasa yang dapat membuat penawaran harga.');
        }

        $conversation->loadMissing('service');
        $service = $conversation->service;

        if ($service->user_id !== $seller->id || $service->status !== 'approved') {
            throw ValidationException::withMessages([
                'offer_price' => 'Jasa ini tidak tersedia untuk dibuatkan penawaran.',
            ]);
        }

        $originalPrice = round((float) $service->price, 2);
        $offerPrice = round($offerPrice, 2);

        if ($offerPrice <= 0) {
            throw ValidationException::withMessages([
                'offer_price' => 'Harga kesepakatan harus lebih dari 0.',
            ]);
        }

        $expiresAt = now()->addHours(24);

        return DB::transaction(function () use ($conversation, $seller, $originalPrice, $offerPrice, $note, $expiresAt) {
            $conversation->priceOffers()
                ->where('status', PriceOfferStatus::Pending->value)
                ->whereNotNull('expires_at')
                ->where('expires_at', '<=', now())
                ->update(['status' => PriceOfferStatus::Expired->value]);

            $conversation->priceOffers()
                ->where('status', PriceOfferStatus::Pending->value)
                ->update(['status' => PriceOfferStatus::Cancelled->value]);

            return PriceOffer::create([
                'conversation_id' => $conversation->id,
                'service_id' => $conversation->service_id,
                'seller_id' => $seller->id,
                'buyer_id' => $conversation->buyer_id,
                'original_price' => $originalPrice,
                'offer_price' => $offerPrice,
                'note' => $note,
                'status' => PriceOfferStatus::Pending,
                'expires_at' => $expiresAt,
            ]);
        });
    }

    public function accept(PriceOffer $priceOffer, User $buyer): Order
    {
        return DB::transaction(function () use ($priceOffer, $buyer) {
            $offer = PriceOffer::query()->lockForUpdate()->with('service', 'conversation')->findOrFail($priceOffer->id);

            $conversation = $offer->conversation;

            // Hanya buyer yang bisa accept (seller yang buat penawaran)
            if ($conversation->buyer_id !== $buyer->id) {
                throw new AuthorizationException('Hanya buyer yang dapat menerima penawaran ini.');
            }

            if (! $offer->isPending() || $offer->isExpired()) {
                if ($offer->isPending() && $offer->isExpired()) {
                    $offer->update(['status' => PriceOfferStatus::Expired, 'expires_at' => $offer->expires_at]);
                }

                throw ValidationException::withMessages(['offer' => 'Penawaran ini sudah tidak dapat diterima.']);
            }

            if ($offer->service->status !== 'approved' || $offer->service->user_id !== $offer->seller_id) {
                throw ValidationException::withMessages(['offer' => 'Jasa pada penawaran ini sudah tidak tersedia.']);
            }

            $existingOrder = Order::query()->where('price_offer_id', $offer->id)->first();
            if ($existingOrder) {
                return $existingOrder;
            }

            $order = Order::create([
                'service_id' => $offer->service_id,
                'buyer_id' => $buyer->id,
                'price_offer_id' => $offer->id,
                'status' => 'menunggu_pembayaran',
                'payment_status' => 'pending',
                'final_price' => $offer->offer_price,
            ]);

            $offer->update(['status' => PriceOfferStatus::Accepted, 'accepted_at' => now()]);

            return $order;
        });
    }

    public function reject(PriceOffer $priceOffer, User $buyer): PriceOffer
    {
        return DB::transaction(function () use ($priceOffer, $buyer) {
            $offer = PriceOffer::query()->lockForUpdate()->with('conversation')->findOrFail($priceOffer->id);

            $conversation = $offer->conversation;

            // Hanya buyer yang bisa reject
            if ($conversation->buyer_id !== $buyer->id) {
                throw new AuthorizationException('Hanya buyer yang dapat menolak penawaran ini.');
            }

            if (! $offer->isPending() || $offer->isExpired()) {
                throw ValidationException::withMessages(['offer' => 'Penawaran ini sudah tidak dapat ditolak.']);
            }

            $offer->update(['status' => PriceOfferStatus::Rejected, 'rejected_at' => now()]);

            return $offer->fresh();
        });
    }
}
