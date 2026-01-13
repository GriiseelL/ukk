<?php

namespace App\Http\Controllers;

use App\Models\FlipAccess;
use App\Models\FlipPosts;
use App\Models\Friends;
use App\Models\Likes;
use App\Models\Posts;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // 🟢 Halaman profil utama
    public function index()
    {
        $user = auth()->user();

        // Post normal (MAIN POSTS)
        $posts = Posts::where('user_id', $user->id)
            ->latest()
            ->get();

        // Followers = orang yang mengikuti user ini
        $followers = Friends::where('user_following', $user->id)
            ->with('follower')
            ->get();

        // Following = orang yang diikuti oleh user ini
        $following = Friends::where('user_id', $user->id)
            ->with('following')
            ->get();

        $followersCount = $followers->count();
        $followingCount = $following->count();

        // Hitung likes untuk MAIN POSTS saja
        $likesCount = Likes::whereIn('post_id', $posts->pluck('id'))
            ->where('type', 'main')
            ->count();

        // Postingan yang disukai user (MAIN ONLY)
        $likedPosts = Posts::whereHas('likes', function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->where('type', 'main');
        })->latest()->get();

        // Ambil daftar ID pengguna yang diikuti
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
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'caption' => 'required|string|max:5000',
                'is_flipside' => 'required|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Handle image upload
            $imageBackPath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imageBackPath = $image->storeAs('posts/backgrounds', $imageName, 'public');
            }

            // Create post
            $post = FlipPosts::create([
                'user_id' => Auth::id(),
                'caption' => $request->caption,
                'image' => $imageBackPath,
                'is_flipside' => $request->is_flipside ? 1 : 0,
                'likes_count' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Load user relation for response
            $post->load('user');

            return response()->json([
                'success' => true,
                'message' => $request->is_flipside ? 'Flipside post created successfully!' : 'Post created successfully!',
                'data' => [
                    'post' => $post
                ]
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating post: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create post. Please try again.'
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
