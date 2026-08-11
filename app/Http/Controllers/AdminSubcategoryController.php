<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class AdminSubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
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
        $categories = Category::all();
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
        // Cek apakah ada jasa yang menggunakan subkategori ini
        if ($subcategory->services()->count() > 0) {
            return back()->with('error', 'Subkategori tidak bisa dihapus karena masih digunakan oleh jasa.');
        }
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')->with('success', 'Subkategori berhasil dihapus.');
    }
}