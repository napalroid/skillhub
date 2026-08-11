<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'subcategory_name' => 'nullable|string|max:255',
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

            if (!empty($validated['subcategory_name'])) {
                Subcategory::create([
                    'name' => $validated['subcategory_name'],
                    'category_id' => $category->id,
                ]);
            }
        });
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
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
        // Cek apakah ada subkategori yang terikat
        if ($category->subcategories()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki subkategori.');
        }
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        if ($category->iconIsFile()) {
            Storage::disk('public')->delete($category->icon);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
