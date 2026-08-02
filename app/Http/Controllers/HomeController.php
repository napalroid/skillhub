<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Service;


class HomeController extends Controller
{
    public function index()
    {
        $featuredServices = Service::approved()
            ->with(['seller', 'subcategory.category'])
            ->latest()
            ->take(6)
            ->get();
        $categories = Category::with(['subcategories'])
            ->orderBy('name')
            ->take(8)
            ->get();
        return view('welcome', compact('featuredServices', 'categories'));
    }
}