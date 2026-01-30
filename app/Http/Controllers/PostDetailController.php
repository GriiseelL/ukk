<?php

namespace App\Http\Controllers;

use App\Models\Posts;  // Ganti dari Post ke Posts
use App\Models\Like;
use App\Models\Comment;
use App\Models\Comments;
use App\Models\Likes;
use Illuminate\Http\Request;

class PostDetailController extends Controller
{


    /**
     * Display the specified post.
     */
    public function show($id)
    {
        $post = Posts::with(['user', 'likes', 'comments.user'])->findOrFail($id);
        
        // Cek apakah user sudah like post ini
        $isLiked = $post->likes()->where('user_id', auth()->id())->exists();
        
        // Ambil komentar terbaru
        $comments = $post->comments()->with('user')->latest()->get();
        
        return view('postDetail', compact('post', 'isLiked', 'comments'));
    }

    /**
     * Like/Unlike a post.
     */
    public function like($id)
    {
        $post = Posts::findOrFail($id);  // Ganti dari Post ke Posts
        
        $like = Likes::where('user_id', auth()->id())
                    ->where('post_id', $id)
                    ->first();
        
        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'count' => $post->likes()->count()]);
        } else {
            Likes::create([
                'user_id' => auth()->id(),
                'post_id' => $id
            ]);
            
            // Kirim notifikasi ke pemilik post
            if ($post->user_id !== auth()->id()) {
                \App\Models\Notification::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $post->user_id,
                    'post_id' => $id,
                    'type' => 'like',
                    'message' => auth()->user()->username . ' menyukai postingan Anda'
                ]);
            }
            
            return response()->json(['liked' => true, 'count' => $post->likes()->count()]);
        }
    }

    /**
     * Add a comment to a post.
     */
    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:500'
        ]);
        
        $post = Posts::findOrFail($id);  // Ganti dari Post ke Posts
        
        $comment = Comments::create([
            'user_id' => auth()->id(),
            'post_id' => $id,
            'content' => $request->content
        ]);
        
        // Kirim notifikasi ke pemilik post
        if ($post->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $post->user_id,
                'post_id' => $id,
                'type' => 'comment',
                'message' => auth()->user()->username . ' mengomentari postingan Anda',
                'data' => json_encode(['comment' => $request->content])
            ]);
        }
        
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => [
                    'username' => auth()->user()->username,
                    'avatar' => auth()->user()->avatar ?? 'default.jpg'
                ],
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }
}