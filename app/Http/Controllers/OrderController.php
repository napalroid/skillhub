<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\JokiMlOrder;
use App\Models\BarberOrder;
use App\Services\MlRankCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function create(Service $service)
    {
        abort_unless($service->status === 'approved', 404, 'Jasa tidak tersedia.');

        $service->refresh();
        $service->load(['subcategory.serviceType', 'activeAddons', 'seller']);

        // Check if service has custom type (joki_ml, barber, etc.)
        $serviceType = $service->subcategory->serviceType ?? null;

        if ($serviceType && $serviceType->code === 'joki_ml') {
            // Load joki_ml_service relation
            $service->load('jokiMlService');

            // Return joki_ml order form
            return view('orders.create-joki-ml', compact('service', 'serviceType'));
        }

        // Specialized data stays with the service, while the checkout shell remains
        // shared. This prevents each service type from drifting into a different UI.
        if ($serviceType && $serviceType->code === 'barber') {
            $service->load('barberService');
        }

        // Standard booking flow for other services
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

        return view('orders.create', compact('service', 'serviceType', 'availableSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'message' => 'nullable|string|max:1000',
            'booking_data' => 'nullable|array',
            'time_slot_id' => 'nullable|exists:service_time_slots,id',
            'addon_ids' => 'nullable|array|max:20',
            'addon_ids.*' => 'integer|distinct',
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

        $selectedAddonIds = $validated['addon_ids'] ?? [];
        $order = null;

        if (! empty($validated['message']) && empty($selectedAddonIds)) {
            $order = Order::query()
                ->where('service_id', $service->id)
                ->where('buyer_id', auth()->id())
                ->whereNotIn('status', ['selesai'])
                ->latest()
                ->first();
        }

        if (! $order) {
            $order = DB::transaction(function () use ($service, $validated, $selectedAddonIds) {
                $addons = $service->activeAddons()
                    ->whereIn('id', $selectedAddonIds)
                    ->lockForUpdate()
                    ->get();

                if ($addons->count() !== count($selectedAddonIds)) {
                    throw ValidationException::withMessages([
                        'addon_ids' => 'Satu atau beberapa layanan tambahan sudah tidak tersedia. Silakan pilih ulang.',
                    ]);
                }

                $orderData = [
                    'service_id' => $service->id,
                    'buyer_id' => auth()->id(),
                    'status' => Order::STATUS_MENUNGGU_PEMBAYARAN,
                    'estimated_price' => $service->price + $addons->sum('price'),
                    'final_price' => $service->price + $addons->sum('price'),
                ];

                if (!empty($validated['booking_data'])) {
                    $orderData['booking_data'] = $validated['booking_data'];
                }

                if (!empty($validated['time_slot_id'])) {
                    $orderData['time_slot_id'] = $validated['time_slot_id'];
                }

                $order = Order::create($orderData);

                $order->addons()->createMany($addons->map(fn ($addon) => [
                    'service_addon_id' => $addon->id,
                    'name' => $addon->name,
                    'description' => $addon->description,
                    'price' => $addon->price,
                ])->all());

                return $order;
            });
        }

        // An unpaid order is only a checkout draft. The seller is notified by
        // the payment flow after the buyer has actually paid.

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
                : 'Pesanan dibuat. Silakan lanjutkan ke pembayaran.');
    }

    public function storeJokiMl(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'current_rank' => 'required|string',
            'current_division' => 'required|integer|min:1',
            'current_stars' => 'required|integer|min:0',
            'target_rank' => 'required|string',
            'target_division' => 'required|integer|min:1',
            'target_stars' => 'required|integer|min:0',
            'ml_username' => 'required|string|max:100',
            'ml_password' => 'required|string|min:6',
            'hero_notes' => 'nullable|string|max:500',
            'additional_notes' => 'nullable|string|max:500',
            'addon_ids' => 'nullable|array|max:20',
            'addon_ids.*' => 'integer|distinct',
        ]);

        $service = Service::approved()->with(['subcategory.serviceType', 'jokiMlService'])->findOrFail($validated['service_id']);

        if ($service->user_id === auth()->id()) {
            abort(403, 'Anda tidak bisa memesan jasa milik sendiri.');
        }

        $jokiMlService = $service->jokiMlService;
        if (!$jokiMlService) {
            abort(404, 'Joki ML service tidak ditemukan.');
        }

        $calculator = new MlRankCalculator();

        if (!$calculator->validateRank($validated['current_rank'], $validated['current_division'], $validated['current_stars'])) {
            return back()->withErrors(['current_rank' => 'Rank saat ini tidak valid.'])->withInput();
        }

        if (!$calculator->validateRank($validated['target_rank'], $validated['target_division'], $validated['target_stars'])) {
            return back()->withErrors(['target_rank' => 'Rank tujuan tidak valid.'])->withInput();
        }

        $currentPos = $calculator->toAbsolutePosition($validated['current_rank'], $validated['current_division'], $validated['current_stars']);
        $targetPos = $calculator->toAbsolutePosition($validated['target_rank'], $validated['target_division'], $validated['target_stars']);

        if ($targetPos <= $currentPos) {
            return back()->withErrors(['target_rank' => 'Rank tujuan harus lebih tinggi dari rank saat ini.'])->withInput();
        }

        $totalStars = $calculator->calculateStarDifference(
            $validated['current_rank'], $validated['current_division'], $validated['current_stars'],
            $validated['target_rank'], $validated['target_division'], $validated['target_stars']
        );

        if (empty($jokiMlService->price_per_star_config)) {
            return back()->withInput()->withErrors([
                'service_id' => 'Tarif per bintang untuk jasa ini belum lengkap. Silakan hubungi seller.',
            ]);
        }

        $result = $calculator->calculatePriceWithBreakdown(
            $validated['current_rank'], $validated['current_division'], $validated['current_stars'],
            $validated['target_rank'], $validated['target_division'], $validated['target_stars'],
            $jokiMlService->price_per_star_config
        );

        $autoCalculatedPrice = $result['total_price'];
        $priceBreakdown = $result['breakdown'];
        $approvalStatus = 'approved';
        $finalPrice = $autoCalculatedPrice;

        $selectedAddonIds = $validated['addon_ids'] ?? [];

        $order = DB::transaction(function () use ($service, $jokiMlService, $validated, $totalStars, $autoCalculatedPrice, $priceBreakdown, $approvalStatus, $finalPrice, $selectedAddonIds) {
            $addons = $service->activeAddons()
                ->whereIn('id', $selectedAddonIds)
                ->lockForUpdate()
                ->get();

            if ($addons->count() !== count($selectedAddonIds)) {
                throw ValidationException::withMessages([
                    'addon_ids' => 'Satu atau beberapa layanan tambahan sudah tidak tersedia. Silakan pilih ulang.',
                ]);
            }

            $addonTotal = $addons->sum('price');
            $order = Order::create([
                'service_id' => $service->id,
                'buyer_id' => auth()->id(),
                'status' => Order::STATUS_MENUNGGU_PEMBAYARAN,
                'approval_status' => $approvalStatus,
                'estimated_price' => $finalPrice + $addonTotal,
                'final_price' => $finalPrice + $addonTotal,
            ]);

            JokiMlOrder::create([
                'order_id' => $order->id,
                'joki_ml_service_id' => $jokiMlService->id,
                'current_rank' => $validated['current_rank'],
                'current_division' => $validated['current_division'],
                'current_stars' => $validated['current_stars'],
                'target_rank' => $validated['target_rank'],
                'target_division' => $validated['target_division'],
                'target_stars' => $validated['target_stars'],
                'ml_username' => $validated['ml_username'],
                'ml_password_encrypted' => Crypt::encryptString($validated['ml_password']),
                'hero_notes' => $validated['hero_notes'] ?? null,
                'additional_notes' => $validated['additional_notes'] ?? null,
                'total_stars_needed' => $totalStars,
                'auto_calculated_price' => $autoCalculatedPrice,
                'price_breakdown' => $priceBreakdown,
            ]);

            $order->update([
                'orderable_type' => JokiMlOrder::class,
                'orderable_id' => $order->id,
            ]);

            $order->addons()->createMany($addons->map(fn ($addon) => [
                'service_addon_id' => $addon->id,
                'name' => $addon->name,
                'description' => $addon->description,
                'price' => $addon->price,
            ])->all());

            return $order;
        });

        // Keep this order private while its payment is still pending. Seller
        // notification begins only after payment is received/confirmed.

        return redirect()->route('orders.payment.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function setPrice(Request $request, Order $order)
    {
        abort_unless($order->service->user_id === auth()->id(), 403, 'Hanya seller yang bisa menetapkan harga.');

        if ($order->approval_status !== 'pending') {
            return back()->with('error', 'Harga pesanan ini sudah tidak menunggu persetujuan seller.');
        }
        
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
                'approval_status' => 'approved',
                'seller_approved_at' => now(),
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
                'service.subcategory.serviceType',
                'service.timeSlots',
                'buyer',
                'negotiations.sender',
                'messages.sender',
                'files',
                'payment',
                'timeSlot',
                'priceHistories',
                'addons',
                'jokiMlOrder',
                'barberOrder',
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

        $visibleOrders = fn () => Order::where($roleScope)
            ->where('status', '!=', Order::STATUS_MENUNGGU_PEMBAYARAN);

        $totalOrders = $visibleOrders()->count();
        $completedCount = $visibleOrders()->whereIn('status', $statusMap['completed'])->count();
        $inProgressCount = $visibleOrders()->whereIn('status', $statusMap['in_progress'])->count();
        $pendingCount = $visibleOrders()->whereIn('status', $statusMap['pending'])->count();

        $orders = $visibleOrders()
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

    public function cancel(Order $order)
    {
        $isBuyer = $order->buyer_id === auth()->id();

        if (!$isBuyer) {
            abort(403, 'Hanya buyer yang bisa membatalkan pesanan');
        }

        // Buyer hanya bisa membatalkan jika belum ada pembayaran
        $allowedStatuses = [Order::STATUS_MENUNGGU_PEMBAYARAN];

        if (!in_array($order->status, $allowedStatuses)) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses atau sudah dibayar');
        }

        $order->update(['status' => 'dibatalkan']);

        // Notify seller
        \App\Services\NotificationService::createAndDispatch(
            userId: $order->service->user_id,
            type: 'order_cancelled',
            title: 'Pesanan Dibatalkan',
            message: "Pesanan #{$order->id} telah dibatalkan oleh buyer.",
            extraData: [
                'order_id' => $order->id,
            ]
        );

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan');
    }

    public function destroy(Order $order, Request $request)
    {
        $order->loadMissing('service');

        $isSeller = $order->service->user_id === auth()->id();
        $isBuyer = $order->buyer_id === auth()->id();

        if (!$isSeller && !$isBuyer) {
            abort(403);
        }

        if ($order->status !== 'dibatalkan' && $order->status !== 'menunggu_pembayaran') {
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
