<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(Service $service)
    {
        abort_unless($service->status === 'approved', 404, 'Jasa tidak tersedia.');

        // Only get available slots if time slots are enabled
        $availableSlots = [];
        if ($service->time_slots_enabled) {
            $availableSlots = \App\Models\ServiceTimeSlot::where('service_id', $service->id)
                ->whereDate('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date')
                ->orderBy('time_start')
                ->get()
                ->map(function ($slot) {
                    $bookedCount = $slot->orders()
                        ->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])
                        ->count();
                    
                    return [
                        'id' => $slot->id,
                        'date' => $slot->date->format('Y-m-d'),
                        'time_start' => $slot->time_start,
                        'time_end' => $slot->time_end,
                        'max_bookings' => $slot->max_bookings,
                        'available_count' => $slot->max_bookings - $bookedCount,
                    ];
                })->toArray();
        }

        return view('orders.create', compact('service', 'availableSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'message' => 'nullable|string|max:1000',
            'booking_data' => 'nullable|array',
            'time_slot_id' => 'nullable|exists:service_time_slots,id',
        ]);

        $service = Service::approved()->findOrFail($validated['service_id']);

        // Validate time slot only if time slots enabled on service
        if ($service->time_slots_enabled && !empty($validated['time_slot_id'])) {
            
            $slot = \App\Models\ServiceTimeSlot::find($validated['time_slot_id']);
            
            if ($slot->service_id !== $service->id) {
                return back()->withErrors(['time_slot_id' => 'Slot tidak valid untuk jasa ini']);
            }
            
            $bookedCount = $slot->orders()
                ->whereIn('status', ['menunggu_konfirmasi', 'dikonfirmasi', 'dikerjakan', 'menunggu_persetujuan', 'selesai'])
                ->count();
            
            if ($bookedCount >= $slot->max_bookings) {
                return back()->withErrors(['time_slot_id' => 'Slot waktu sudah penuh']);
            }
        }

        if ($service->user_id === auth()->id()) {
            abort(403, 'Anda tidak bisa memesan jasa milik sendiri.');
        }

        // Validate booking data if booking is enabled
        if ($service->booking_config && isset($service->booking_config['enabled']) && $service->booking_config['enabled']) {
            $bookingFields = $service->booking_config['fields'] ?? [];
            
            foreach ($bookingFields as $field) {
                if ($field['required'] ?? false) {
                    $fieldName = $field['name'];
                    if (empty($validated['booking_data'][$fieldName])) {
                        return back()->withErrors([
                            "booking_data.{$fieldName}" => "Field {$field['label']} wajib diisi"
                        ])->withInput();
                    }
                }
            }
        }

        $order = null;

        if (! empty($validated['message'])) {
            $order = Order::query()
                ->where('service_id', $service->id)
                ->where('buyer_id', auth()->id())
                ->whereNotIn('status', ['selesai'])
                ->latest()
                ->first();
        }

        $orderData = [
            'service_id' => $service->id,
            'buyer_id' => auth()->id(),
            'status' => 'menunggu_konfirmasi_harga',
            'estimated_price' => $service->price,
            'final_price' => null,
        ];

        // Add booking data if exists
        if (!empty($validated['booking_data'])) {
            $orderData['booking_data'] = $validated['booking_data'];
        }

        // Add time slot if exists
        if (!empty($validated['time_slot_id'])) {
            $orderData['time_slot_id'] = $validated['time_slot_id'];
        }

        $order ??= Order::create($orderData);

        // Notify seller about new order waiting for price confirmation
        \App\Services\NotificationService::createAndDispatch(
            userId: $service->user_id,
            type: 'new_order',
            title: 'Pesanan Baru Menunggu Konfirmasi',
            message: "Pesanan baru #{$order->id} menunggu Anda menetapkan harga.",
            extraData: [
                'order_id' => $order->id,
                'buyer_name' => auth()->user()->name,
            ]
        );

        // Notify buyer about selected slot
        if (!empty($validated['time_slot_id'])) {
            $slot = \App\Models\ServiceTimeSlot::find($validated['time_slot_id']);
            // Set flash message to show slot info
            session()->flash('booking_slot_info', [
                'date' => $slot->date->format('d M Y'),
                'time' => $slot->time_start . ' - ' . $slot->time_end,
            ]);
        }

        if (! empty($validated['message'])) {
            $order->messages()->create([
                'sender_id' => auth()->id(),
                'message' => $validated['message'],
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', ! empty($validated['message'])
                ? 'Pesanmu sudah dikirim ke penyedia jasa.'
                : 'Pesanan dibuat. Seller akan menetapkan harga untuk pesanan Anda.');
    }

    public function setPrice(Request $request, Order $order)
    {
        abort_unless($order->service->user_id === auth()->id(), 403, 'Hanya seller yang bisa menetapkan harga.');
        
        $validated = $request->validate([
            'final_price' => 'required|numeric|min:1000',
            'seller_price_note' => 'nullable|string|max:500',
        ]);

        $oldPrice = $order->final_price;
        $newPrice = $validated['final_price'];

        DB::transaction(function () use ($order, $oldPrice, $newPrice, $validated) {
            $order->update([
                'final_price' => $newPrice,
                'seller_price_note' => $validated['seller_price_note'],
                'status' => 'menunggu_pembayaran',
            ]);

            \App\Models\OrderPriceHistory::create([
                'order_id' => $order->id,
                'changed_by' => auth()->id(),
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'note' => $validated['seller_price_note'],
            ]);
        });

        $order->loadMissing('service', 'buyer');

        if ($order->buyer_id) {
            event(new \App\Events\SellerPriceSet($order));

            \App\Services\NotificationService::createAndDispatch(
                userId: $order->buyer_id,
                type: 'price_set',
                title: 'Harga Pesanan DitETAPkan',
                message: "Seller telah menetapkan harga Rp" . number_format($newPrice, 0, ',', '.') . 
                         " untuk pesanan #{$order->id}. Silakan lakukan pembayaran.",
                extraData: [
                    'order_id' => $order->id,
                    'final_price' => $newPrice,
                ]
            );
        }

        return back()->with('success', 'Harga berhasil ditetapkan: Rp' . number_format($newPrice, 0, ',', '.'));
    }

    public function show(Order $order)
    {
        // Hanya buyer, seller (pemilik jasa), atau admin yang boleh lihat
        $isBuyer = $order->buyer_id === auth()->id();
        $isSeller = $order->service->user_id === auth()->id();
        if (!$isBuyer && !$isSeller && !auth()->user()->isAdmin()) {
            abort(403);
        }

        try {
            $order->load([
                'service.seller',
                'service.timeSlots',
                'buyer',
                'negotiations.sender',
                'messages.sender',
                'files',
                'payment',
                'timeSlot',
                'priceHistories'
            ]);
            
            return view('orders.show', compact('order', 'isBuyer', 'isSeller'));
        } catch (\Exception $e) {
            \Log::error('Error in OrderController@show: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $statusFilter = $request->query('status', 'all');
        $role = $request->query('role', 'all'); // all | buyer | seller

        $roleScope = function ($query) use ($userId, $role) {
            if ($role === 'buyer') {
                $query->where('buyer_id', $userId);
            } elseif ($role === 'seller') {
                $query->whereHas('service', fn ($service) => $service->where('user_id', $userId));
            } else {
                $query->where('buyer_id', $userId)
                      ->orWhereHas('service', fn ($service) => $service->where('user_id', $userId));
            }
        };

        $statusMap = [
            'pending' => ['menunggu_pembayaran', 'menunggu_verifikasi', 'menunggu_konfirmasi'],
            'processing' => ['dikonfirmasi', 'dikerjakan'],
            'in_progress' => ['dikonfirmasi', 'dikerjakan', 'menunggu_persetujuan'],
            'completed' => ['selesai'],
            'cancelled' => ['dibatalkan'],
        ];

        $totalOrders = Order::where($roleScope)->count();
        $completedCount = Order::where($roleScope)->whereIn('status', $statusMap['completed'])->count();
        $inProgressCount = Order::where($roleScope)->whereIn('status', $statusMap['in_progress'])->count();
        $pendingCount = Order::where($roleScope)->whereIn('status', $statusMap['pending'])->count();

        $orders = Order::where($roleScope)
            ->with(['service.seller', 'buyer'])
            ->when($statusFilter !== 'all' && isset($statusMap[$statusFilter]), fn ($query) => $query->whereIn('status', $statusMap[$statusFilter]))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Pre-determine seller status for orders to avoid N+1 queries
        $orders->getCollection()->transform(function ($order) {
            $order->is_seller = $order->service->user_id === auth()->id();
            return $order;
        });

        return view('orders.index', compact(
            'orders',
            'totalOrders',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'statusFilter',
            'role'
        ));
    }

    public function conversation(Order $order)
    {
        $isBuyer = $order->buyer_id === auth()->id();
        $isSeller = $order->service->user_id === auth()->id();
        
        if (!$isBuyer && !$isSeller && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $conversation = \App\Models\Conversation::firstOrCreate([
            'service_id' => $order->service_id,
            'buyer_id' => $order->buyer_id,
            'seller_id' => $order->service->user_id,
        ]);

        return redirect()->route('conversations.show', $conversation);
    }

    /**
     * Reschedule booking slot for an order
     */
    public function reschedule(Request $request, Order $order)
    {
        // Only seller can reschedule
        if ($order->service->user_id !== auth()->id()) {
            abort(403, 'Hanya seller yang bisa mengubah jadwal');
        }

        $request->validate([
            'time_slot_id' => 'required|exists:service_time_slots,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldSlotId = $order->time_slot_id;
        $newSlotId = $request->time_slot_id;

        if ($oldSlotId == $newSlotId) {
            return back()->with('error', 'Slot yang dipilih sama dengan slot saat ini');
        }

        $newSlot = \App\Models\ServiceTimeSlot::find($newSlotId);

        // Check if new slot is available
        $bookedCount = $newSlot->orders()
            ->whereIn('status', ['menunggu_konfirmasi', 'dikonfirmasi', 'dikerjakan', 'menunggu_persetujuan', 'selesai'])
            ->count();

        if ($bookedCount >= $newSlot->max_bookings) {
            return back()->with('error', 'Slot waktu sudah penuh');
        }

        DB::transaction(function () use ($order, $oldSlotId, $newSlotId, $request) {
            // Update order
            $order->update(['time_slot_id' => $newSlotId]);

            // Insert history
            \App\Models\TimeSlotHistory::create([
                'order_id' => $order->id,
                'old_time_slot_id' => $oldSlotId,
                'new_time_slot_id' => $newSlotId,
                'changed_by' => 'seller',
                'notes' => $request->notes ?? 'Seller mengubah jadwal booking',
            ]);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Jadwal booking berhasil diubah');
    }

    public function destroy(Order $order, Request $request)
    {
        $isSeller = $order->seller_id === auth()->id();
        $isBuyer = $order->buyer_id === auth()->id();

        if (!$isSeller && !$isBuyer) {
            abort(403);
        }

        // Order manual (buyer = seller) bisa dihapus kapan saja
        $isManualOrder = $order->buyer_id === $order->seller_id;
        
        if (!$isManualOrder && $order->status !== 'dibatalkan' && $order->status !== 'menunggu_pembayaran') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Tidak bisa menghapus pesanan yang sudah diproses'], 400);
            }
            return back()->with('error', 'Tidak bisa menghapus pesanan yang sudah diproses');
        }

        $order->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Pesanan berhasil dihapus']);
        }

        return back()->with('success', 'Pesanan berhasil dihapus');
    }
}
