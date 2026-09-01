<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Services\NotificationDeliveryService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $notifications = auth()->user()->notifications()
            ->with(['service', 'conversation', 'order', 'payment'])
            ->latest()
            ->paginate(15);

        if ($request->query('json') === '1' || $request->expectsJson()) {
            return response()->json([
                'notifications' => auth()->user()->notifications()
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn ($n) => [
                        'id' => $n->id,
                        'title' => $n->title,
                        'message' => $n->message,
                        'type' => $n->type,
                        'is_read' => $n->is_read,
                        'time' => $n->created_at->diffForHumans(),
                    ])
            ]);
        }

        auth()->user()->unreadNotifications()->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'count' => 0,
                'authenticated' => false
            ], 401);
        }

        if (!$request->expectsJson() && !$request->ajax() && !$request->wantsJson()) {
            return redirect()->route('notifications.index');
        }

        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications()->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function read(UserNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['is_read' => true]);

        return redirect()->back();
    }

    public function open(UserNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        if ($notification->type === 'message' && $notification->conversation) {
            abort_unless($notification->conversation->hasParticipant(auth()->user()), 403);

            return redirect()->route('conversations.show', $notification->conversation);
        }

        if (in_array($notification->type, ['payment_paid', 'escrow_ready', 'order_escrow', 'order_confirmed'], true) && $notification->order) {
            return redirect()->route('orders.show', $notification->order);
        }

        return redirect()->route('notifications.index');
    }

    public function ack(UserNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        NotificationDeliveryService::markDelivered($notification);

        return response()->json([
            'success' => true,
            'message' => 'Notification acknowledged',
        ]);
    }

    public function pending(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'notifications' => [],
                'authenticated' => false,
            ], 401);
        }

        $notifications = NotificationDeliveryService::getUndeliveredNotifications(auth()->id());

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'created_at' => $n->created_at->format('M d, Y h:i A'),
                    'is_read' => (bool) $n->is_read,
                ];
            }),
        ]);
    }
}
