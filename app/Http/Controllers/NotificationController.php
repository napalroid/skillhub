<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()
            ->with(['service', 'conversation', 'order', 'payment'])
            ->latest()
            ->paginate(15);

        if ($request->query('json') === '1') {
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

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
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
}
