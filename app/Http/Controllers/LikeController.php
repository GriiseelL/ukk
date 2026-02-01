<?php

namespace App\Http\Controllers;

use App\Models\FlipPosts;
use App\Models\FlipsideLike;
use App\Models\Likes;
use App\Models\Notification;
use App\Models\Posts;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    // Function untuk like post biasa
    public function store(Request $request, $postId)
    {
        $user = auth()->user();
        $post = Posts::findOrFail($postId);

        // cek user udah like belum
        $existing = Likes::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already liked'
            ], 409);
        }

        Likes::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // 👉 TAMBAH NOTIFIKASI
        if ($user->id != $post->user_id) {
            Notification::create([
                'sender_id' => $user->id,
                'receiver_id' => $post->user_id,
                'type' => 'like',
                'reference_id' => $post->id,
                'post_id' => $post->id, // ⚠️ BARIS INI HARUS ADA!
                'message' => $user->username . ' menyukai postinganmu'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Liked successfully',
            'likes_count' => $post->likes()->count()
        ]);
    }


    // Function untuk unlike post biasa
    public function destroy(Request $request, $postId)
    {
        $user = auth()->user();

        $like = Likes::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not found'
            ], 404);
        }

        $like->delete();

        $post = Posts::findOrFail($postId);

        return response()->json([
            'success' => true,
            'message' => 'Unliked successfully',
            'likes_count' => $post->likes()->count()
        ]);
    }

    // Function untuk like flipside (sudah ada, tapi perlu update)
    // Flipside - store
    public function storeFlip(Request $request, $postId)
    {
        $user = auth()->user();

        // Gunakan FlipPosts model
        $flipsidePost = FlipPosts::findOrFail($postId);

        // ✅ Pastikan nama kolom benar
        $existing = FlipsideLike::where('user_id', $user->id)
            ->where('flipside_post_id', $flipsidePost->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already liked'
            ], 409);
        }

        // ✅ Pastikan flipside_post_id ada di sini
        FlipsideLike::create([
            'user_id' => $user->id,
            'flipside_post_id' => $flipsidePost->id, // ← PENTING: Harus ada
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Liked successfully',
            'likes_count' => $flipsidePost->likes()->count()
        ]);
    }

    // Flipside - destroy
    public function flipsideDelete(Request $request, $postId)
    {
        $user = auth()->user();

        // ✅ Pastikan nama kolom benar
        $like = FlipsideLike::where('user_id', $user->id)
            ->where('flipside_post_id', $postId)
            ->first();

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not found'
            ], 404);
        }

        $like->delete();

        $flipsidePost = FlipPosts::findOrFail($postId);

        return response()->json([
            'success' => true,
            'message' => 'Unliked successfully',
            'likes_count' => $flipsidePost->likes()->count()
        ]);
    }

}
