<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\UserNotification;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('accountNotifications', UserNotification::query()
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(fn ($notification) => [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'is_read' => $notification->is_read,
                        'date' => $notification->created_at?->format('d M Y'),
                        'read_url' => route('notifications.read', $notification),
                    ])->values());
            } else {
                $view->with('accountNotifications', collect());
            }
        });
    }
}
