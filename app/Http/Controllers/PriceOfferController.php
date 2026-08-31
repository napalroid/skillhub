<?php

namespace App\Http\Controllers;

use App\Events\PriceOfferCreated;
use App\Events\PriceOfferStatusChanged;
use App\Http\Requests\StorePriceOfferRequest;
use App\Models\Conversation;
use App\Models\PriceOffer;
use App\Services\PriceOfferService;
use Illuminate\Broadcasting\BroadcastException;

class PriceOfferController extends Controller
{
    public function store(StorePriceOfferRequest $request, Conversation $conversation, PriceOfferService $priceOfferService)
    {
        abort_unless($conversation->hasParticipant($request->user()), 403);

        $offer = $priceOfferService->create(
            $conversation,
            $request->user(),
            (float) $request->validated('offer_price'),
            $request->validated('note'),
        );

        try {
            broadcast(new PriceOfferCreated($offer))->toOthers();
        } catch (\Exception $exception) {
            \Log::warning('Broadcast PriceOffer failed but saved', ['offer_id' => $offer->id]);
        }

        if ($request->expectsJson()) {
            return response()->json(['offer' => $offer], 201);
        }

        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Penawaran harga berhasil dikirim.');
    }

    public function accept(PriceOffer $priceOffer, PriceOfferService $priceOfferService)
    {
        $order = $priceOfferService->accept($priceOffer, request()->user());
        $priceOffer->refresh();
        try { 
            broadcast(new PriceOfferStatusChanged($priceOffer))->toOthers(); 
        } catch (\Exception $exception) { 
            \Log::warning('Broadcast accept failed but processed', ['offer_id' => $priceOffer->id]); 
        }

        return redirect()->route('orders.payment.show', $order)
            ->with('success', 'Penawaran diterima. Silakan lanjutkan pembayaran.');
    }

    public function reject(PriceOffer $priceOffer, PriceOfferService $priceOfferService)
    {
        $offer = $priceOfferService->reject($priceOffer, request()->user());
        try { 
            broadcast(new PriceOfferStatusChanged($offer))->toOthers(); 
        } catch (\Exception $exception) { 
            \Log::warning('Broadcast reject failed but processed', ['offer_id' => $offer->id]); 
        }

        return redirect()->route('conversations.show', $offer->conversation_id)
            ->with('success', 'Penawaran harga ditolak.');
    }
}
