<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddNotificationsToView
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($response->getStatusCode() === 200 && auth()->check()) {
            $view = $response->getOriginalContent();
            
            if (isset($view->data['accountNotifications'])) {
                $view->with('accountNotifications', \App\Models\UserNotification::query()
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
            }
        }
        
        return $response;
    }
}
