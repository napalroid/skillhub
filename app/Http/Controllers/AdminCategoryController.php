<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['subcategories' => function ($q) {
            $q->withCount('services')->orderBy('name');
        }])->withCount('subcategories')->orderBy('name')->get();

        // Services count per category (via subcategories)
        $categories->each(function ($cat) {
            $cat->services_count = $cat->subcategories->sum('services_count');
            $cat->image_url = $cat->image ? asset('storage/' . $cat->image) : null;
            $cat->icon_url = $cat->icon ? asset('storage/' . $cat->icon) : null;
            $cat->icon_is_file = $cat->iconIsFile();
            $cat->display_icon = $cat->displayIcon();
        });

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'subcategories' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $imagePath = $request->hasFile('image')
                ? $request->file('image')->store('category-images', 'public')
                : null;

            $iconPath = $request->hasFile('icon')
                ? $request->file('icon')->store('category-icons', 'public')
                : null;

            $category = Category::create([
                'name' => $validated['name'],
                'image' => $imagePath,
                'icon' => $iconPath,
            ]);

            if (!empty($validated['subcategories'])) {
                $subNames = array_map('trim', explode(',', $validated['subcategories']));
                $subNames = array_filter($subNames);
                foreach ($subNames as $subName) {
                    Subcategory::create([
                        'name' => $subName,
                        'category_id' => $category->id,
                    ]);
                }
            }
        });
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $category->load('subcategories');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = ['name' => $validated['name']];
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('category-images', 'public');
        }
        if ($request->hasFile('icon')) {
            if ($category->iconIsFile()) {
                Storage::disk('public')->delete($category->icon);
            }
            $data['icon'] = $request->file('icon')->store('category-icons', 'public');
        }
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Force delete: hapus subkategori dan jasa terkait
        $subcategoryIds = $category->subcategories->pluck('id')->toArray();
        
        if (!empty($subcategoryIds)) {
            // Hapus file jasa yang terkait
            $services = Service::whereIn('subcategory_id', $subcategoryIds)->get();
            foreach ($services as $service) {
                if ($service->image) Storage::disk('public')->delete($service->image);
                if ($service->portfolio_images) {
                    foreach ($service->portfolio_images as $img) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }
            Service::whereIn('subcategory_id', $subcategoryIds)->delete();
            Subcategory::whereIn('id', $subcategoryIds)->delete();
        }

        if ($category->image) Storage::disk('public')->delete($category->image);
        if ($category->iconIsFile()) Storage::disk('public')->delete($category->icon);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dan semua datanya berhasil dihapus.');
    }

    public function data()
    {
        $categories = Category::with(['subcategories' => function ($q) {
            $q->withCount('services')->orderBy('name');
        }])->withCount('subcategories')->orderBy('name')->get();

        $categories->each(function ($cat) {
            $cat->services_count = $cat->subcategories->sum('services_count');
            $cat->subcategories->each(function ($sub) {
                $sub->services_count = $sub->services_count;
            });
            $cat->image_url = $cat->image ? asset('storage/' . $cat->image) : null;
            $cat->icon_url = $cat->icon ? asset('storage/' . $cat->icon) : null;
            $cat->icon_is_file = $cat->iconIsFile();
            $cat->display_icon = $cat->displayIcon();
        });

        return response()->json(['categories' => $categories]);
    }
}
