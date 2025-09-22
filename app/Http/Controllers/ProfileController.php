<?php

namespace App\Http\Controllers;

use App\Models\Friends;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login dulu');
        }

        // Postingan milik user
        $posts = Posts::where('user_id', $user->id)
            ->with(['likes', 'user'])
            ->withCount(['likes', 'comments']) // ← tambahin comments
            ->latest()
            ->get();

        // Postingan yang disukai user
        $likedPosts = $user->likedPosts()
            ->with(['likes', 'user'])
            ->withCount(['likes', 'comments']) // ← tambahin comments
            ->latest()
            ->get();

        // Followers & following
        $followers = Friends::where('user_following', $user->id)->with('follower')->get();
        $following = Friends::where('user_id', $user->id)->with('following')->get();
        $followingIds = Friends::where('user_id', $user->id)->pluck('user_following')->toArray();

        // Hitung jumlah
        $followersCount = $followers->count();
        $followingCount = $following->count();
        $likesCount = $posts->sum('likes_count');

        // kalau ada ?post_id=xx di query, ambil detail post
        $activePost = null;
        if ($request->has('post_id')) {
            $activePost = Posts::with(['user', 'likes', 'comments.user'])
                ->withCount(['likes', 'comments']) // ← tambahin comments
                ->find($request->get('post_id'));
        }

        return view('myAccount', compact(
            'user',
            'posts',
            'likedPosts',
            'followers',
            'following',
            'followersCount',
            'followingCount',
            'likesCount',
            'followingIds',
            'activePost'
        ));
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
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        try {
            // Delete old cover if exists
            if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
                Storage::disk('public')->delete($user->cover_image);
            }

            // Store new cover
            $coverPath = $request->file('cover')->store('covers', 'public');

            // Optional: Resize image using Intervention Image (if installed)
            if (class_exists('Intervention\Image\Facades\Image')) {
                $fullPath = storage_path('app/public/' . $coverPath);
                Image::make($fullPath)
                    ->fit(900, 200) // Resize to 900x200
                    ->save($fullPath, 85); // 85% quality
            }

            $user->cover_image = $coverPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cover image updated successfully!',
                'cover_url' => asset('storage/' . $coverPath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cover: ' . $e->getMessage()
            ], 500);
        }
    }
}