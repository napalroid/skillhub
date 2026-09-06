<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceTimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceTimeSlotController extends Controller
{
    public function manage(Service $service)
    {
        if ($service->user_id !== auth()->id()) {
            abort(403);
        }

        return view('services.manage-slots', compact('service'));
    }

    public function getSlotsForDate(Service $service, Request $request)
    {
        if ($service->user_id !== auth()->id()) {
            abort(403);
        }

        $date = $request->input('date');
        
        if (!$date) {
            return response()->json(['slots' => []]);
        }

        $slots = ServiceTimeSlot::where('service_id', $service->id)
            ->whereDate('date', $date)
            ->orderBy('time_start')
            ->with(['orders' => function($q) {
                $q->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])
                  ->with('buyer:id,name');
            }])
            ->get();

        return response()->json([
            'slots' => $slots->map(function($slot) {
                $platformBookings = $slot->orders->map(fn($order) => [
                    'buyer_name' => $order->buyer->name ?? 'N/A',
                    'status' => $order->status,
                ]);
                
                return [
                    'id' => $slot->id,
                    'time_start' => $slot->time_start,
                    'time_end' => $slot->time_end,
                    'max_bookings' => $slot->max_bookings,
                    'platform_bookings' => $platformBookings,
                    'available' => $slot->getAvailableCountAttribute(),
                ];
            })
        ]);
    }

    public function getOrders(Service $service, Request $request)
    {
        if ($service->user_id !== auth()->id()) {
            abort(403);
        }

        $date = $request->input('date');
        $slotId = $request->input('slot_id');

        if (!$date) {
            return response()->json(['orders' => []]);
        }

        $slotsQuery = ServiceTimeSlot::where('service_id', $service->id)
            ->whereDate('date', $date);

        if ($slotId) {
            $slotsQuery->where('id', $slotId);
        }

        $slots = $slotsQuery->get();
        $slotIds = $slots->pluck('id');

        $orders = \App\Models\Order::whereIn('time_slot_id', $slotIds)
            ->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])
            ->orWhere(function($query) use ($slotIds) {
                $query->whereIn('time_slot_id', $slotIds)
                      ->where('buyer_id', auth()->id())
                      ->where('seller_id', auth()->id());
            })
            ->with(['buyer:id,name', 'timeSlot', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();

        $statusLabels = [
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'dikerjakan' => 'Sedang Dikerjakan',
            'menunggu_persetujuan' => 'Menunggu Persetujuan',
            'selesai' => 'Selesai',
        ];

        return response()->json([
            'orders' => $orders->map(function($order) use ($statusLabels) {
                $isManual = $order->buyer_id === $order->service->user_id;
                return [
                    'id' => $order->id,
                    'buyer_name' => $order->buyer->name ?? 'N/A',
                    'status' => $order->status,
                    'status_label' => $statusLabels[$order->status] ?? ucfirst($order->status),
                    'slot_time_start' => $order->timeSlot->time_start ?? '-',
                    'slot_time_end' => $order->timeSlot->time_end ?? '-',
                    'price_formatted' => 'Rp' . number_format($order->final_price ?? $order->price, 0, ',', '.'),
                    'message' => $order->message,
                    'is_manual' => $isManual ?? false,
                ];
            })
        ]);
    }
    public function toggleManual(Service $service, ServiceTimeSlot $slot)
    {
        abort(404);
    }
}
