<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\JokiMlService;
use App\Models\BarberService;
use App\Models\ServiceAddon;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    // ==================== PUBLIC ====================
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|integer|exists:categories,id',
            'subcategory' => 'nullable|integer|exists:subcategories,id',
            'sort' => 'nullable|in:latest,price_low,price_high',
        ]);

        $services = Service::query()
            ->with(['seller', 'subcategory.category', 'subcategory.serviceType'])
            ->where('status', 'approved')
            ->whereDoesntHave('subcategory.serviceType', function ($q) {
                $q->where('hidden_from_listing', true);
            })
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($validated['category'] ?? null, function ($query, $categoryId) {
                $query->whereHas('subcategory', fn ($subcategoryQuery) => $subcategoryQuery->where('category_id', $categoryId));
            })
            ->when($validated['subcategory'] ?? null, fn ($query, $subcategoryId) => $query->where('subcategory_id', $subcategoryId));

        match ($validated['sort'] ?? 'latest') {
            'price_low' => $services->orderBy('price'),
            'price_high' => $services->orderByDesc('price'),
            default => $services->latest(),
        };

        $categories = Category::with('subcategories')->orderBy('name')->get();
        $subcategories = Subcategory::with('category')
            ->when($validated['category'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy('name')
            ->get();

        $activeCategory = $validated['category'] ?? null
            ? Category::find($validated['category'])
            : null;

        $categoryImages = $categories
            ->mapWithKeys(function (Category $category) {
                $url = null;
                if ($category->image) {
                    $url = asset('storage/' . $category->image);
                } elseif ($category->iconIsFile()) {
                    $url = asset('storage/' . $category->icon);
                }
                return [$category->id => $url];
            })
            ->filter()
            ->all();

        $heroImage = asset('images/skillhub-hero.jpg');
        if ($activeCategory) {
            $heroImage = $categoryImages[$activeCategory->id] ?? $heroImage;
        }

        return view('marketplace.index', [
            'services' => $services->paginate(12)->withQueryString(),
            'categories' => $categories,
            'subcategories' => $subcategories,
            'activeCategory' => $activeCategory,
            'categoryImages' => $categoryImages,
            'heroImage' => $heroImage,
        ]);
    }

    public function show($id)
    {
        $service = Service::query()
            ->approved()
            ->with(['seller', 'subcategory.category', 'reviews.user'])
            ->withCount(['orders', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        $portfolios = collect($service->portfolio_images ?? [])->take(3);

        return view('marketplace.show', compact('service', 'portfolios'));
    }

    public function create()
    {
        $categories = Category::with('subcategories')->get();
        $subcategories = Subcategory::all();
        return view('services.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        // Validate this before resolving the relation so an incomplete form returns
        // field feedback instead of a 404 response.
        $request->validate(['subcategory_id' => 'required|exists:subcategories,id']);

        // Get subcategory with service_type
        $subcategory = Subcategory::with('serviceType')->findOrFail($request->subcategory_id);
        
        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'required|string',
            // Keep main-image support identical to portfolio uploads.
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'portfolio_images' => 'nullable|array|max:3',
            'portfolio_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'buyer_fields' => 'nullable|array|max:10',
            'buyer_fields.*.label' => 'nullable|string|max:80',
            'buyer_fields.*.type' => 'nullable|in:text,textarea,select,number,date',
            'buyer_fields.*.required' => 'nullable|boolean',
            'buyer_fields.*.placeholder' => 'nullable|string|max:150',
            'buyer_fields.*.help_text' => 'nullable|string|max:255',
            'buyer_fields.*.options' => 'nullable|string|max:500',
            'time_slots_enabled' => 'nullable|boolean',
            'addons' => 'nullable|array|max:20',
            'addons.*.name' => 'nullable|string|max:100',
            'addons.*.description' => 'nullable|string|max:255',
            'addons.*.price' => 'nullable|numeric|min:0|max:9999999999.99',
        ];
        
        // Add price validation only if NOT custom pricing
        if (!$subcategory->serviceType || !$subcategory->serviceType->has_custom_pricing) {
            $rules['price'] = 'required|numeric|min:0';
        }
        
        // Dynamic validation based on service_type
        if ($subcategory->serviceType) {
            switch($subcategory->serviceType->code) {
                case 'joki_ml':
                    $rules = array_merge($rules, [
                        'pricing_mode' => 'nullable|in:auto',
                        'price_per_star' => 'required|array',
                        'price_per_star.*' => 'required|numeric|min:1000',
                        'display_price' => 'required|numeric|min:0',
                        'estimated_completion_days' => 'nullable|integer|min:1|max:30',
                        'special_notes' => 'nullable|string|max:1000',
                    ]);
                    break;
                case 'barber':
                    $rules = array_merge($rules, [
                        'price' => 'required|numeric|min:0',
                        'available_haircut_types' => 'required|array|min:1',
                        'available_haircut_types.*' => 'string|max:100',
                        'estimated_duration_minutes' => 'required|integer|min:15|max:180',
                        'additional_services' => 'nullable|array',
                        'additional_services.*.name' => 'required_with:additional_services.*.price|string|max:100',
                        'additional_services.*.price' => 'required_with:additional_services.*.name|numeric|min:0',
                        'seller_notes' => 'nullable|string|max:1000',
                    ]);
                    break;
            }
        }
        
        $validated = $request->validate($rules);
        
        $subcategoryMatchesCategory = Subcategory::query()
            ->whereKey($validated['subcategory_id'])
            ->where('category_id', $validated['category_id'])
            ->exists();

        if (! $subcategoryMatchesCategory) {
            return back()
                ->withInput()
                ->withErrors(['subcategory_id' => 'Subkategori harus berasal dari kategori yang dipilih.']);
        }

        // Only persist a conservative, whitelisted field schema. The actual input
        // name is generated server-side, never trusted from the browser.
        $buyerFields = [];
        foreach ($validated['buyer_fields'] ?? [] as $index => $field) {
            $label = trim((string) ($field['label'] ?? ''));
            if ($label === '') {
                continue;
            }

            $type = $field['type'] ?? 'text';
            $options = [];
            if ($type === 'select') {
                $options = array_values(array_filter(array_map('trim', explode(',', (string) ($field['options'] ?? '')))));
                if (count($options) < 2) {
                    return back()->withInput()->withErrors([
                        "buyer_fields.{$index}.options" => 'Field pilihan membutuhkan minimal dua opsi, dipisahkan dengan koma.',
                    ]);
                }
            }

            $baseName = Str::slug($label, '_') ?: 'field';
            $name = $baseName;
            $suffix = 2;
            while (collect($buyerFields)->contains('name', $name)) {
                $name = "{$baseName}_{$suffix}";
                $suffix++;
            }

            $buyerFields[] = [
                'name' => $name,
                'label' => $label,
                'type' => $type,
                'required' => (bool) ($field['required'] ?? false),
                'placeholder' => trim((string) ($field['placeholder'] ?? '')),
                'help_text' => trim((string) ($field['help_text'] ?? '')),
                'options' => $options,
            ];
        }

        // Prepare data for Service model
        $data = [
            'user_id' => auth()->id(),
            'subcategory_id' => $validated['subcategory_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'pending',
            'booking_enabled' => count($buyerFields) > 0,
            'time_slots_enabled' => (bool) ($validated['time_slots_enabled'] ?? false),
            'booking_config' => count($buyerFields) ? ['enabled' => true, 'fields' => $buyerFields] : null,
            'last_booking_config_edit' => count($buyerFields) ? now() : null,
        ];
        
        // Set price based on service_type
        if ($subcategory->serviceType && $subcategory->serviceType->has_custom_pricing) {
            $data['price'] = $validated['display_price'] ?? 0;
        } else {
            $data['price'] = $validated['price'];
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = $path;
        }

        if ($request->hasFile('portfolio_images')) {
            $data['portfolio_images'] = collect($request->file('portfolio_images'))
                ->map(fn ($image) => $image->store('services/portfolio', 'public'))
                ->all();
        }

        // Create the service
        $addons = collect($validated['addons'] ?? [])->filter(fn ($addon) => filled($addon['name'] ?? null))->values();
        if ($addons->contains(fn ($addon) => ! array_key_exists('price', $addon) || $addon['price'] === null || $addon['price'] === '')) {
            return back()->withInput()->withErrors(['addons' => 'Setiap layanan tambahan harus memiliki harga.']);
        }

        $service = DB::transaction(function () use ($data, $subcategory, $validated, $addons) {
            $service = Service::create($data);
        
        // Create service_type specific record
        if ($subcategory->serviceType) {
            switch($subcategory->serviceType->code) {
                case 'joki_ml':
                    JokiMlService::create([
                        'service_id' => $service->id,
                        'pricing_mode' => 'auto',
                        'price_per_star_config' => $validated['price_per_star'] ?? [],
                        'estimated_completion_days' => $validated['estimated_completion_days'] ?? null,
                        'special_notes' => $validated['special_notes'] ?? null,
                    ]);
                    break;
                case 'barber':
                    BarberService::create([
                        'service_id' => $service->id,
                        'available_haircut_types' => $validated['available_haircut_types'] ?? [],
                        'estimated_duration_minutes' => $validated['estimated_duration_minutes'] ?? 30,
                        'additional_services' => $validated['additional_services'] ?? [],
                        'seller_notes' => $validated['seller_notes'] ?? null,
                    ]);
                    break;
            }
        }

            $addons->each(fn ($addon, $position) => ServiceAddon::create([
                'service_id' => $service->id, 'name' => trim($addon['name']),
                'description' => filled($addon['description'] ?? null) ? trim($addon['description']) : null,
                'price' => $addon['price'], 'is_active' => true, 'sort_order' => $position,
            ]));
            return $service;
        });

        NotificationService::createAndDispatch(
            userId: auth()->id(),
            type: 'submitted',
            title: "Mengajukan jasa ({$service->title})",
            message: "Jasa kamu \"{$service->title}\" telah terkirim ke admin dan sedang menunggu persetujuan.",
            extraData: [
                'service_id' => $service->id,
            ]
        );

        return redirect()->route('dashboard')
            ->with('success', 'Jasa berhasil dikirim dan sedang menunggu persetujuan admin.')
            ->with('notification_submitted', $service->title);
    }

    public function myServices(Request $request)
    {
        $filters = $request->validate([
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory' => ['nullable', 'integer', 'exists:subcategories,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $sellerId = auth()->id();
        $allServices = Service::query()->where('user_id', $sellerId);
        $totalServices = (clone $allServices)->count();

        $categoryCounts = (clone $allServices)
            ->join('subcategories', 'services.subcategory_id', '=', 'subcategories.id')
            ->selectRaw('subcategories.category_id, COUNT(*) as total')
            ->groupBy('subcategories.category_id')
            ->pluck('total', 'category_id');

        $subcategoryCounts = (clone $allServices)
            ->selectRaw('subcategory_id, COUNT(*) as total')
            ->groupBy('subcategory_id')
            ->pluck('total', 'subcategory_id');

        $categories = Category::query()
            ->with('subcategories')
            ->get()
            ->sortBy(fn (Category $category) => [
                -($categoryCounts[$category->id] ?? 0),
                $category->name,
            ])
            ->values();

        $categories->each(function (Category $category) use ($subcategoryCounts) {
            $category->setRelation(
                'subcategories',
                $category->subcategories
                    ->sortBy(fn (Subcategory $subcategory) => [
                        -($subcategoryCounts[$subcategory->id] ?? 0),
                        $subcategory->name,
                    ])
                    ->values()
            );
        });

        $selectedCategory = isset($filters['category'])
            ? $categories->firstWhere('id', (int) $filters['category'])
            : null;
        $selectedSubcategory = isset($filters['subcategory'])
            ? Subcategory::find($filters['subcategory'])
            : null;

        if ($selectedCategory && $selectedSubcategory && $selectedSubcategory->category_id !== $selectedCategory->id) {
            abort(404);
        }

        $query = Service::query()
            ->with(['subcategory.category'])
            ->withCount('orders')
            ->where('user_id', $sellerId);

        if ($selectedCategory) {
            $query->whereHas('subcategory', fn ($subcategory) => $subcategory->where('category_id', $selectedCategory->id));
        }

        if ($selectedSubcategory) {
            $query->where('subcategory_id', $selectedSubcategory->id);
        }

        $search = trim((string) ($filters['q'] ?? ''));
        if ($search !== '') {
            $query->where(function ($serviceQuery) use ($search) {
                $serviceQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate(12)->withQueryString();

        // Services created before the main-image field was routinely used can
        // still have seller-uploaded portfolio photos. Use the first valid one
        // for this seller-only card list, without altering the stored record.
        $services->getCollection()->each(function (Service $service) {
            $service->setAttribute('image', $service->display_image_path);
        });

        return view('services.my-services', compact(
            'services', 'categories', 'categoryCounts', 'subcategoryCounts',
            'selectedCategory', 'selectedSubcategory', 'totalServices', 'search',
        ));
    }

    public function edit($id)
    {
        $service = Service::where('user_id', auth()->id())->findOrFail($id);
        $service->refresh()->load('addons');
        $categories = Category::with('subcategories')->get();
        $subcategories = Subcategory::all();
        return view('services.edit', compact('service', 'categories', 'subcategories'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['title', 'subcategory_id', 'price', 'description']);

        if ($request->hasFile('image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = $path;
        }

        $service->update($data);
        return redirect()->route('services.my')->with('success', 'Jasa diperbarui.');
    }

    public function updateBookingConfig(Request $request, $id)
    {
        $service = Service::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'booking_enabled' => 'nullable|boolean',
            'booking_config' => 'nullable|array',
        ]);

        $updateData = [];

        if ($request->has('booking_enabled')) {
            $updateData['booking_enabled'] = $request->booking_enabled;
        }

        if ($request->has('booking_config')) {
            $updateData['booking_config'] = $request->booking_config;
            $updateData['last_booking_config_edit'] = now();
        }

        $service->update($updateData);

        return redirect()->route('services.edit', $service->id)->with('success', 'Konfigurasi booking berhasil diperbarui');
    }
}
