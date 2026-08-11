<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            ->with(['seller', 'subcategory.category'])
            ->approved()
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

        $heroImage = asset('images/skillhub-hero.png');
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
            ->with(['seller', 'subcategory.category', 'reviews.order.buyer'])
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'portfolio_images' => 'nullable|array|max:3',
            'portfolio_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $subcategoryMatchesCategory = Subcategory::query()
            ->whereKey($validated['subcategory_id'])
            ->where('category_id', $validated['category_id'])
            ->exists();

        if (! $subcategoryMatchesCategory) {
            return back()
                ->withInput()
                ->withErrors(['subcategory_id' => 'Subkategori harus berasal dari kategori yang dipilih.']);
        }

        $data = [
            'user_id' => auth()->id(),
            'subcategory_id' => $validated['subcategory_id'],
            'title' => $validated['title'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => 'pending',
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = $path;
        }

        if ($request->hasFile('portfolio_images')) {
            $data['portfolio_images'] = collect($request->file('portfolio_images'))
                ->map(fn ($image) => $image->store('services/portfolio', 'public'))
                ->all();
        }

        $service = Service::create($data);

        UserNotification::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'type' => 'submitted',
            'title' => "Mengajukan jasa ({$service->title})",
            'message' => "Jasa kamu \"{$service->title}\" telah terkirim ke admin dan sedang menunggu persetujuan.",
            'is_read' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Jasa berhasil dikirim dan sedang menunggu persetujuan admin.')
            ->with('notification_submitted', $service->title);
    }

    public function myServices(Request $request)
    {
        $services = Service::with(['subcategory.category'])
                            ->where('user_id', auth()->id())
                            ->where('status', 'approved')
                            ->latest()
                            ->paginate(12);
        $categories = Category::with('subcategories')->get();
        $subcategories = Subcategory::all();
        return view('services.my-services', compact('services', 'categories', 'subcategories'));
    }

    public function edit($id)
    {
        $service = Service::where('user_id', auth()->id())->findOrFail($id);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
}
