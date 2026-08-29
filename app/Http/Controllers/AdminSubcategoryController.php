<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Service;
use Illuminate\Http\Request;
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

        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
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

}