<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Menampilkan landing page untuk pengguna yang telah masuk.
     */
    public function index()
    {
        $featuredServices = Service::approved()
            ->with(['seller', 'subcategory.category'])
            ->latest()
            ->take(6)
            ->get();

        $mediaUrl = static function (?string $path): ?string {
            if (! $path || ! Storage::disk('public')->exists($path)) {
                return null;
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $featuredServiceCards = $featuredServices->map(function (Service $service) use ($mediaUrl) {
            return [
                'title' => $service->title,
                'category' => $service->subcategory?->name ?? 'Jasa siswa',
                'seller' => $service->seller?->name ?? 'Siswa SkillHub',
                'price' => 'Rp' . number_format($service->price, 0, ',', '.'),
                'url' => route('services.show', $service),
                'image' => $mediaUrl($service->image) ?? asset('images/skillhub-hero.png'),
                'portfolios' => collect($service->portfolio_images ?? [])
                    ->map($mediaUrl)
                    ->filter()
                    ->values()
                    ->all(),
            ];
        })->values();

        $categories = Category::with(['subcategories'])
            ->orderBy('name')
            ->take(8)
            ->get();

        return view('dashboard', compact('featuredServices', 'featuredServiceCards', 'categories'));
    }
}
