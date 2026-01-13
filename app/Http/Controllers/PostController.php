<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\FlipPosts;
use App\Models\Friends;
use App\Models\Posts;
use App\Models\Stories;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Di Controller Anda (misalnya HomeController atau PostController)
    // Menggunakan HANYA tabel story_hidden_users (TIDAK PERLU tabel baru)

    public function index()
    {
        $authUserId = Auth::id();

        // Query posts seperti biasa
        $posts = Posts::with(['user.followers', 'likes'])
            ->whereHas('user.followers', function ($q) use ($authUserId) {
                $q->where('user_id', '=', $authUserId);
            })
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get()
            ->map(function ($post) use ($authUserId) {
                $post->is_liked_by_auth_user = $post->likes()
                    ->where('user_id', $authUserId)
                    ->exists();
                return $post;
            });
        if (count($posts) < 1) {
            $posts = Posts::with(['user', 'likes'])
                ->withCount(['likes', 'comments'])
                ->inRandomOrder()
                ->latest()
                ->limit(2)
                ->get()
                ->map(function ($post) use ($authUserId) {
                    $post->is_liked_by_auth_user = $post->likes()
                        ->where('user_id', $authUserId)
                        ->exists();
                    return $post;
                });
        }

        // Ambil ID stories yang di-hide dari user saat ini
        $hiddenStoryIds = DB::table('story_hidden_users')
            ->where('user_id', $authUserId)
            ->pluck('story_id')
            ->toArray();

        // Ambil ID users yang difollow
        $followedUserIds = DB::table('friends')
            ->where('user_id', $authUserId)
            ->pluck('user_following')
            ->toArray();

        // Gabungkan dengan ID user sendiri
        $allowedUserIds = array_merge([$authUserId], $followedUserIds);

        // Query users dengan stories
        $usersWithStories = User::whereHas('stories', function ($query) use ($authUserId, $hiddenStoryIds) {
            $query->where('expires_at', '>', now())
                ->where(function ($q) use ($authUserId, $hiddenStoryIds) {
                    // Story dari user sendiri (semua privacy)
                    $q->where('user_id', $authUserId)
                        // ATAU story dengan privacy everyone/close-friends yang TIDAK di-hide
                        ->orWhere(function ($sq) use ($authUserId, $hiddenStoryIds) {
                            $sq->whereIn('privacy', ['everyone', 'close-friends'])
                                ->whereNotIn('id', $hiddenStoryIds);
                        });
                    // Story dengan privacy 'private' hanya tampil jika user sendiri (sudah di-handle di atas)
                });
        })
            ->with(['stories' => function ($query) use ($authUserId, $hiddenStoryIds) {
                $query->where('expires_at', '>', now())
                    ->where(function ($q) use ($authUserId, $hiddenStoryIds) {
                        $q->where('user_id', $authUserId)
                            ->orWhere(function ($sq) use ($authUserId, $hiddenStoryIds) {
                                $sq->whereIn('privacy', ['everyone', 'close-friends'])
                                    ->whereNotIn('id', $hiddenStoryIds);
                            });
                    })
                    ->orderBy('created_at', 'desc');
            }])
            ->whereIn('id', $allowedUserIds) // Hanya yang difollow + user sendiri
            ->get();

        return view('homepage', compact('posts', 'usersWithStories'));
    }

    // ============================================
    // PENJELASAN LOGIKA SEDERHANA:
    // ============================================
    // 
    // PRIVACY = "everyone"
    // - Semua follower bisa lihat
    // - Kecuali yang ada di story_hidden_users
    // 
    // PRIVACY = "close-friends"
    // - Hanya follower yang TIDAK ada di story_hidden_users bisa lihat
    // - Yang di-hide = follower yang TIDAK dipilih saat buat story
    // 
    // PRIVACY = "private"
    // - Hanya user sendiri yang bisa lihat
    // - Tidak perlu cek story_hidden_users
    // 
    // KEUNTUNGAN:
    // ✅ Tidak perlu tabel baru (story_close_friends)
    // ✅ Menggunakan tabel yang sudah ada (story_hidden_users)
    // ✅ Logika lebih sederhana
    // ✅ Query lebih efisien
    // ============================================

    // ============================================
    // PENJELASAN TABEL story_hidden_users:
    // ============================================
    // - id: Primary key
    // - story_id: ID dari user yang story-nya mau disembunyikan
    // - user_id: ID user yang menyembunyikan story tersebut
    // - created_at & updated_at: Timestamp
    //
    // Contoh:
    // Jika user dengan ID 3 menyembunyikan story dari user ID 5
    // Maka insert: story_id = 5, user_id = 3
    // ============================================

    public function store(StorePostRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        } else {
            $data['image'] = null;
        }

        $data['user_id'] = $user->id;

        $post = Posts::create($data);

        // Cek apakah request dari AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dibuat',
                'post' => $post
            ]);
        }

        return redirect()->route('homepage')->with('success', 'Post berhasil dibuat');
    }

    public function update(StorePostRequest $request, Posts $post)
    {
        $validatedData = $request->validated();

        $post->update([
            'caption' => $validatedData['caption'],
        ]);

        return response()->json([
            'success' => true,
            'post' => $post
        ]);
    }

    public function destroy($id)
    {
        // 1. Coba cari di posts biasa
        $post = Posts::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        // 2. Kalau tidak ada, coba cari di flipside_posts
        if (!$post) {
            $post = FlipPosts::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();
        }

        // 3. Kalau tetap tidak ketemu → error
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found or unauthorized'
            ], 404);
        }

        // 4. Hapus media kalau ada
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // 5. Hapus record
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    public function show($id)
    {
        $post = Posts::with(['user', 'comments.user', 'likes'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);

        $isLiked = $post->likes()->where('user_id', auth()->id())->exists();

        return response()->json([
            'post' => $post,
            'comments' => $post->comments,
            'isLiked' => $isLiked
        ]);
    }
}
