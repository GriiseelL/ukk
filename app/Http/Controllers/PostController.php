<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\FlipPosts;
use App\Models\Friends;
use App\Models\PostMedia;
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
        $posts = Posts::with(['user.followers', 'likes', 'media'])
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



        $friendPosts = Posts::with(['user', 'likes', 'media'])
            ->whereIn('user_id', $followedUserIds)
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();


        $randomPosts = Posts::with(['user', 'likes', 'media'])
            ->whereNotIn('user_id', $followedUserIds)
            ->where('user_id', '!=', $authUserId)
            ->withCount(['likes', 'comments'])
            ->inRandomOrder()
            ->limit(5)
            ->get();
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

        // ============================
        // SUGGESTED USERS
        // ============================

        $suggestedUsers = User::where('id', '!=', $authUserId)
            ->where('role', 'user')
            ->whereNotIn('id', function ($q) use ($authUserId) {
                $q->select('user_following')
                    ->from('friends')
                    ->where('user_id', $authUserId);
            })
            ->inRandomOrder()
            ->limit(5)
            ->get(); // Ini akan mengambil semua kolom
        // dd($suggestedUsers);

        $posts = collect();
        $randomIndex = 0;

        foreach ($friendPosts as $index => $post) {
            // Post teman
            $post->is_liked_by_auth_user = $post->likes()
                ->where('user_id', $authUserId)
                ->exists();

            $posts->push($post);

            // Setiap 3 post teman → sisipkan 1 random
            if (($index + 1) % 3 === 0 && isset($randomPosts[$randomIndex])) {
                $random = $randomPosts[$randomIndex];
                $random->is_liked_by_auth_user = $random->likes()
                    ->where('user_id', $authUserId)
                    ->exists();

                $posts->push($random);
                $randomIndex++;
            }
        }


        return view('homepage', compact(
            'posts',
            'usersWithStories',
            'suggestedUsers',
            'usersWithStories',
            'suggestedUsers'
        ));
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

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:280',
            'media' => 'nullable|array|max:4',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov|max:51200',
        ]);

        $post = Posts::create([
            'user_id' => auth()->id(),
            'caption' => $request->caption,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts/media', 'public');

                // ⬇️ INI PENTING
                $post->media()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $post->load('media')
        ]);
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
        // 1. Cari di post biasa
        $post = Posts::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        // 2. Jika tidak ada → cari di flipside
        if (!$post) {
            $post = FlipPosts::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();
        }

        // 3. Jika tetap tidak ada
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found or unauthorized'
            ], 404);
        }

        // 4. Hapus media
        if ($post->media) {
            foreach ($post->media as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        // 5. Hapus post
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    public function show($id)
    {
        $post = Posts::with([
            'user',
            'media',
            'comments.user',
            'likes'
        ])
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
