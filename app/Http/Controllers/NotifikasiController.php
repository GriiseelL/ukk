<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    function index()
    {
        $notifications = Notification::with(['sender', 'post'])
            ->where('receiver_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'post_id' => $notification->post_id, // Pastikan ini ada
                    'sender' => [
                        'id' => $notification->sender->id,
                        'username' => $notification->sender->username,
                    ],
                    'post' => $notification->post ? [
                        'id' => $notification->post->id,
                        'caption' => $notification->post->caption,
                    ] : null,
                    'data' => $notification->data,
                ];
            });

        return view('notifikasi', compact('notifications'));
    }

    // ... rest of the methods


    // Mark single notification as read
    public function markAsReadSingle($id)
    {
        $notification = Notification::where('id', $id)
            ->where('receiver_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->is_read = 1;
            $notification->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Notification::where('receiver_id', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }
}
