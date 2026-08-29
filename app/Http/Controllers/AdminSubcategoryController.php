<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminSubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with(['category', 'services' => function($q) {
            $q->select('id', 'subcategory_id');
        }])->withCount('services')->orderBy('name')->paginate(15);

        $categories = Category::orderBy('name')->get();

        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);
        Subcategory::create($request->only(['name', 'category_id']));
        return redirect()->route('admin.subcategories.index')->with('success', 'Subkategori berhasil ditambahkan.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        
        $services = $subcategory->services()
            ->with(['seller', 'orders', 'reviews'])
            ->get()
            ->map(function($service) {
                $service->orders_count = $service->orders()->count();
                $service->avg_rating = $service->reviews()->avg('rating') ?? 0;
                return $service;
            });
        
        return view('admin.subcategories.edit', compact('subcategory', 'categories', 'services'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);
        $subcategory->update($request->only(['name', 'category_id']));
        return redirect()->route('admin.subcategories.index')->with('success', 'Subkategori berhasil diperbarui.');
    }

    public function destroy(Subcategory $subcategory)
    {
        // Force delete: hapus jasa yang menggunakan subkategori ini
        $services = Service::where('subcategory_id', $subcategory->id)->get();
        foreach ($services as $service) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            if ($service->portfolio_images) {
                foreach ($service->portfolio_images as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
        Service::where('subcategory_id', $subcategory->id)->delete();
        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')->with('success', 'Subkategori dan jasa terkait berhasil dihapus.');
    }

    public function data()
    {
        $subcategories = Subcategory::with(['category', 'services' => function($q) {
            $q->select('id', 'subcategory_id');
        }])->withCount('services')->orderBy('name')->get();

        $categories = Category::orderBy('name')->get();

        return response()->json([
            'subcategories' => $subcategories,
            'categories' => $categories
        ]);
    }

    public function availableServices(Request $request, Subcategory $subcategory)
    {
        $search = $request->get('search', '');
        
        $services = Service::with('seller')
            ->where('status', 'approved')
            ->where('subcategory_id', '!=', $subcategory->id)
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('seller', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('title')
            ->limit(20)
            ->get();
        
        return response()->json(['services' => $services]);
    }

    public function addService(Subcategory $subcategory, Service $service)
    {
        // Update service subcategory
        $service->update(['subcategory_id' => $subcategory->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Jasa berhasil ditambahkan ke subkategori'
        ]);
    }

    public function removeService(Request $request, Subcategory $subcategory, Service $service)
    {
        try {
            $request->validate([
                'reason' => 'required|string',
                'reason_detail' => 'nullable|string|max:500'
            ]);

            $reason = $request->reason;
            $reasonDetail = $request->reason_detail;
            
            // If reason is "other", use the detail
            $finalReason = $reason === 'other' ? $reasonDetail : $reason;
            
            // Store service data before any operation
            $serviceTitle = $service->title;
            $serviceUserId = $service->user_id;
            $subcategoryName = $subcategory->name;
            
            DB::transaction(function () use ($service, $reason, $finalReason, $serviceUserId, $serviceTitle, $subcategoryName, &$refundCount) {
                // Step 1: Refund active orders
                $activeOrders = \App\Models\Order::where('service_id', $service->id)
                    ->whereIn('status', ['dibayar', 'dikerjakan', 'menunggu_persetujuan'])
                    ->with('payment', 'buyer')
                    ->get();
                
                $refundCount = 0;
                
                foreach ($activeOrders as $order) {
                    if ($order->payment && in_array($order->payment->status, ['verified', 'paid'])) {
                        try {
                            // Update payment status to refunded
                            $order->payment->update([
                                'status' => 'refunded',
                                'released_at' => now(),
                                'released_by' => auth()->id()
                            ]);
                            
                            // Update order status
                            $order->update(['status' => \App\Models\Order::STATUS_DIBATALKAN]);
                            
                            // Return balance to buyer
                            if ($order->buyer) {
                                $oldBalance = $order->buyer->balance;
                                $order->buyer->increment('balance', $order->payment->amount);
                                
                                // Create wallet transaction
                                \App\Models\WalletTransaction::create([
                                    'user_id' => $order->buyer->id,
                                    'type' => 'credit',
                                    'amount' => $order->payment->amount,
                                    'balance_before' => $oldBalance,
                                    'balance_after' => $order->buyer->fresh()->balance,
                                    'reference_type' => 'order',
                                    'reference_id' => $order->id,
                                    'description' => 'Refund otomatis - Jasa dikeluarkan dari subkategori',
                                    'status' => 'completed'
                                ]);
                                
                                // Notify buyer
                                \App\Models\UserNotification::create([
                                    'user_id' => $order->buyer->id,
                                    'order_id' => $order->id,
                                    'payment_id' => $order->payment->id,
                                    'type' => 'order_refunded',
                                    'title' => 'Refund otomatis - Jasa dihapus',
                                    'message' => "Pesanan #{$order->id} dibatalkan karena jasa dikeluarkan dari subkategori. Dana Rp" . number_format($order->payment->amount, 0, ',', '.') . " telah dikembalikan ke dompet Anda.",
                                    'is_read' => false
                                ]);
                                
                                $refundCount++;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Refund failed for order ' . $order->id . ': ' . $e->getMessage());
                        }
                    }
                }
                
                // Step 2: Delete all orders for this service (to allow service deletion)
                \App\Models\Order::where('service_id', $service->id)->delete();
                
                // Step 3: Delete service
                $service->delete();
                
                // Step 4: Delete service images
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                }
                if ($service->portfolio_images) {
                    foreach ($service->portfolio_images as $img) {
                        if ($img && Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                }
                
                // Step 5: Send notification to service owner (INSIDE transaction)
                \App\Models\UserNotification::create([
                    'user_id' => $serviceUserId,
                    'type' => 'service_removed_from_subcategory',
                    'title' => 'Jasa dikeluarkan dari subkategori',
                    'message' => "Jasa \"{$serviceTitle}\" telah dikeluarkan dari subkategori \"{$subcategoryName}\". Alasan: {$finalReason}" . ($refundCount > 0 ? " | {$refundCount} pesanan aktif telah direfund otomatis." : ""),
                    'is_read' => false
                ]);
                
                \Log::info('Notification created for user ' . $serviceUserId . ' for service ' . $serviceTitle);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Jasa berhasil dikeluarkan dari subkategori' . ($refundCount > 0 ? " dan {$refundCount} pesanan aktif telah direfund otomatis" : ''),
                'refund_count' => $refundCount
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to remove service from subcategory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jasa: ' . $e->getMessage()
            ], 500);
        }
    }
}