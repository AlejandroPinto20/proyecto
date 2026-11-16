<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        // Get notifications from database (assuming notifications table exists)
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($notification) {
                $data = json_decode($notification->data, true);
                return [
                    'id' => $notification->id,
                    'type' => $data['type'] ?? 'general',
                    'title' => $data['title'] ?? 'Notificación',
                    'message' => $data['message'] ?? $notification->data,
                    'read' => $notification->read_at !== null,
                    'created_at' => $notification->created_at,
                ];
            });

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notificación marcada como leída');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $user = Auth::user();

        $count = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Show all notifications page
     */
    public function show()
    {
        $user = Auth::user();

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $notifications->getCollection()->transform(function ($notification) {
            $data = json_decode($notification->data, true);
            $notification->parsed_data = [
                'type' => $data['type'] ?? 'general',
                'title' => $data['title'] ?? 'Notificación',
                'message' => $data['message'] ?? $notification->data,
            ];
            return $notification;
        });

        return view('notifications.index', compact('notifications'));
    }
}
