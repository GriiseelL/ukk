<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('sender')
            ->where('receiver_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'post_id' => $n->post_id,   // ✅ TAMBAH
                    'is_read' => $n->is_read,
                    'created_at' => $n->created_at,
                    'sender' => [
                        'id' => $n->sender->id,
                        'username' => $n->sender->username
                    ]
                ];
            });

        Notification::where('receiver_id', auth()->id())
            ->update(['is_read' => 1]);

        return view('notifikasi', compact('notifications'));
    }

    // Tandai semua sudah dibaca
    public function markAsRead()
    {
        Notification::where('receiver_id', auth()->id())
            ->update(['is_read' => 1]);

        return back();
    }
}
