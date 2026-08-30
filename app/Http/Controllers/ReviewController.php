<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Service $service)
    {
        $user = auth()->user();
        
        if ($service->reviews()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Anda sudah memberi review untuk jasa ini.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $isVerifiedBuyer = Order::where('service_id', $service->id)
            ->where('buyer_id', $user->id)
            ->where('status', 'selesai')
            ->exists();
        
        $review = $service->reviews()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_verified_buyer' => $isVerifiedBuyer,
        ]);
        
        NotificationService::createAndDispatch(
            userId: $service->user_id,
            type: 'new_review',
            title: 'Review Baru',
            message: $user->name . ' memberi rating ' . $validated['rating'] . '/5 untuk jasa "' . $service->title . '"',
            extraData: [
                'service_id' => $service->id,
                'review_id' => $review->id,
            ]
        );
        
        return back()->with('success', 'Terima kasih atas review Anda!');
    }
}
