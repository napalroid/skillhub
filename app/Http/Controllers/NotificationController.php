<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->with(['service', 'conversation'])
            ->latest()
            ->paginate(15);

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

        return redirect()->route('notifications.index');
    }
}
