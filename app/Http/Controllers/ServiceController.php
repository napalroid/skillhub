<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Subcategory;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'approved')
            ->with(['seller', 'subcategory'])
            ->latest()
            ->paginate(12);

        return view('services.index', compact('services'));
    }

    public function create()
{
    $subcategories = Subcategory::with('category')->get();
    return view('services.create', compact('subcategories'));
}

    public function pending()
    {
    $services = Service::where('status', 'pending')
        ->with(['seller', 'subcategory'])
        ->latest()
        ->paginate(10);

    return view('admin.services.pending', compact('services'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'subcategory_id' => 'required|exists:subcategories,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Jasa berhasil diajukan, menunggu approval admin.');
    }

}
