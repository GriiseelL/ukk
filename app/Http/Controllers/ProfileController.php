<?php

namespace App\Http\Controllers;

use App\Models\FlipAccess;
use App\Models\FlipPosts;
use App\Models\Friends;
use App\Models\Likes;
use App\Models\PostMedia;
use App\Models\Posts;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // 🟢 Halaman profil utama
    public function index()
    {
        $user = auth()->user();

        // MAIN POSTS
        $posts = Posts::where('user_id', $user->id)
            ->with(['media', 'user'])
            ->withCount(['likes as likes_count' => function ($q) {
                $q->where('type', 'main');
            }])
            ->latest()
            ->get();

        // Followers
        $followers = Friends::where('user_following', $user->id)
            ->with('follower')
            ->get();

        // Following
        $following = Friends::where('user_id', $user->id)
            ->with('following')
            ->get();

        $followersCount = $followers->count();
        $followingCount = $following->count();

        // TOTAL LIKES PROFILE
        $likesCount = Likes::whereHas('post', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('type', 'main')->count();

        // POST YANG DISUKAI USER
        $likedPosts = Posts::with('media')
            ->whereHas('likes', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('type', 'main');
            })
            ->latest()
            ->get();

        $followingIds = $following->pluck('following.id')->toArray();

        return view('myAccount', compact(
            'user',
            'posts',
            'followers',
            'following',
            'followersCount',
            'followingCount',
            'likesCount',
            'likedPosts',
            'followingIds'
        ));
    }


    // 🟣 Halaman Flipside
    public function flipside()
    {
        $user = auth()->user();

        // 🟣 Ambil semua flipside posts user ini
        $flipsidePosts = FlipPosts::where('user_id', $user->id)
            ->where('is_flipside', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // 🟣 Followers yang diizinkan melihat flipside user ini
        $flipsideFollowers = FlipAccess::where('owner_id', $user->id)
            ->where('has_access', true)
            ->with('user')
            ->get();

        // 🟣 Akun flipside yang user ini diizinkan lihat
        $flipsideFollowing = FlipAccess::where('user_id', $user->id)
            ->where('has_access', true)
            ->with('owner')
            ->get();

        $followersCount = $flipsideFollowers->count();
        $followingCount = $flipsideFollowing->count();

        // 🟣 Hitung total likes dari semua flipside posts user
        $likesCount = Likes::whereIn('post_id', $flipsidePosts->pluck('id'))->count();

        // 🟣 Format followers agar sesuai tampilan lama
        $followers = $flipsideFollowers->map(function ($access) {
            return (object)[
                'follower' => $access->user
            ];
        });

        $following = $flipsideFollowing->map(function ($access) {
            return (object)[
                'following' => $access->owner
            ];
        });

        return view('myAccount', compact(
            'user',
            'flipsidePosts',
            'followersCount',
            'followingCount',
            'likesCount',
            'followers',
            'following'
        ))->with('isFlipside', true);
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:500',
        ]);

        try {
            $user->name = $request->name;
            $user->username = $request->username;

            if ($request->has('bio')) {
                $user->bio = $request->bio;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => [
                    'name' => $user->name,
                    'username' => $user->username,
                    'bio' => $user->bio
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateBio(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'bio' => 'required|string|max:500',
        ]);

        try {
            $user->bio = $request->bio;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Bio updated successfully!',
                'bio' => $user->bio
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        try {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Optional: Resize image using Intervention Image (if installed)
            if (class_exists('Intervention\Image\Facades\Image')) {
                $fullPath = storage_path('app/public/' . $avatarPath);
                Image::make($fullPath)
                    ->fit(300, 300) // Resize to 300x300
                    ->save($fullPath, 85); // 85% quality
            }

            $user->avatar = $avatarPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => asset('storage/' . $avatarPath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCover(Request $request)
    {
        $request->validate([
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB
        ]);

        $user = Auth::user();

        // Hapus cover lama jika ada
        if ($user->cover_image && Storage::disk('public')->exists($user->cover)) {
            Storage::disk('public')->delete($user->cover);
        }

        // Upload cover baru
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $user->cover = $coverPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cover berhasil diupdate',
                'cover_url' => asset('storage/' . $coverPath)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal upload cover'
        ], 400);
    }

    // ProfileController.php
    public function updateFlipsideAvatar(Request $request)
    {
        $request->validate([
            'flipside_avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = auth()->user();

        // Hapus avatar flipside lama jika ada
        if ($user->flipside_avatar && Storage::disk('public')->exists($user->flipside_avatar)) {
            Storage::disk('public')->delete($user->flipside_avatar);
        }

        // Upload avatar flipside baru
        $path = $request->file('flipside_avatar')->store('flipside_avatars', 'public');

        // ✅ UPDATE DATABASE
        $user->flipside_avatar = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Flipside avatar berhasil diupdate',
            'avatar_url' => asset('storage/' . $path)
        ]);
    }

    // ProfileController.php

    public function updateFlipsideName(Request $request)
    {
        $request->validate([
            'flipside_name' => 'required|string|max:100'
        ]);

        $user = auth()->user();
        $user->flipside_name = $request->flipside_name;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Flipside name berhasil diupdate',
            'flipside_name' => $user->flipside_name
        ]);
    }
    /**
     * Store a new post (regular or flipside)
     */
    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:280',
            'media' => 'nullable|array|max:4',
            'media.*' => 'file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime|max:51200',
            'is_flipside' => 'required|boolean',
        ]);
    

        DB::beginTransaction();

        try {
            $post = FlipPosts::create([
                'user_id' => auth()->id(),
                'caption' => $request->caption,
                'is_flipside' => $request->is_flipside ? 1 : 0,
                'status' => 'active',
            ]);

            // ⬇️ POLYMORPHIC CREATE (WAJIB BEGINI)
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $path = $file->store('flipside/media', 'public');

                    $post->media()->create([
                        'file_path' => $path,
                    ]);
                }
            }



            DB::commit();

            $post->load(['user', 'media']);

            return response()->json([
                'success' => true,
                'data' => $post
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Flipside post error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create flipside post'
            ], 500);
        }
    }



    /**
     * Get all flipside posts for current user
     */
    public function getFlipsidePosts()
    {
        try {
            $posts = FlipPosts::where('user_id', Auth::id())
                ->where('is_flipside', 1)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->with('user')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch flipside posts'
            ], 500);
        }
    }



// Tambahkan method ini di ProfileController.php

    /**
     * Update Flipside Cover Image
     */
    public function updateFlipsideCover(Request $request)
    {
        $request->validate([
            'flipside_cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        try {
            $user = auth()->user();

            // Delete old flipside cover if exists
            if ($user->flipside_cover && Storage::disk('public')->exists($user->flipside_cover)) {
                Storage::disk('public')->delete($user->flipside_cover);
            }

            // Store new flipside cover
            $path = $request->file('flipside_cover')->store('flipside_covers', 'public');

            $user->update(['flipside_cover' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Flipside cover updated successfully',
                'flipside_cover' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update flipside cover: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove Flipside Cover Image
     */
    public function removeFlipsideCover()
    {
        try {
            $user = auth()->user();

            if ($user->flipside_cover && Storage::disk('public')->exists($user->flipside_cover)) {
                Storage::disk('public')->delete($user->flipside_cover);
            }

            $user->update(['flipside_cover' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Flipside cover removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove flipside cover: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a post (soft delete)
     */
}
