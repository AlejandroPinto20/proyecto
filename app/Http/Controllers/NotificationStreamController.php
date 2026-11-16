<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Notification;

class NotificationStreamController extends Controller
{
    public function stream(Request $request)
    {
        $callback = function () use ($request) {
            // Get recent notifications for the user
            $notifications = Notification::where('user_id', auth()->id())
                ->where('created_at', '>=', now()->subMinutes(5))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($notifications as $notification) {
                echo "data: " . json_encode([
                    'id' => $notification->id,
                    'type' => $notification->type ?? 'info',
                    'title' => $notification->data['title'] ?? 'Notificación',
                    'message' => $notification->data['message'] ?? '',
                    'created_at' => $notification->created_at->toISOString()
                ]) . "\n\n";
                
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
                
                // Small delay between notifications
                usleep(100000); // 0.1 seconds
            }
        };

        return new StreamedResponse($callback, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function latest(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subHour())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type ?? 'info',
                    'title' => $notification->data['title'] ?? 'Notificación',
                    'message' => $notification->data['message'] ?? '',
                    'created_at' => $notification->created_at->toISOString()
                ];
            });

        return response()->json($notifications);
    }
}
