<?php

namespace App\Http\Controllers;

use App\Models\FlipPosts;
use App\Models\User;
use App\Models\Friends;
use App\Models\Likes;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JelajahController extends Controller // atau nama controller yang sesuai
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $currentUserId = auth()->id();

        $search = User::where('id', '!=', $currentUserId)
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('username', 'like', '%' . $keyword . '%');
                });
            })
            ->get();

        $search->each(function ($user) use ($currentUserId) {

            // Status mengikuti
            $user->isFollowing = Friends::where('user_id', $currentUserId)
                ->where('user_following', $user->id)
                ->exists();

            // Jumlah followers
            $user->followers_count = Friends::where('user_following', $user->id)->count();

            // Jumlah post MAIN
            $user->posts_count = Posts::where('user_id', $user->id)->count();

            // Jumlah post FLIPSIDE
            $user->flipside_posts_count = Flipposts::where('user_id', $user->id)->count();

            // Jumlah likes MAIN
            $user->likes_main = Likes::where('type', 'main')
                ->whereIn('post_id', Posts::where('user_id', $user->id)->pluck('id'))
                ->count();

            // Jumlah likes FLIPSIDE
            $user->likes_flipside = Likes::where('type', 'flipside')
                ->whereIn('post_id', Flipposts::where('user_id', $user->id)->pluck('id'))
                ->count();
        });

        return view('jelajahi', compact('search', 'keyword'));
    }



    // public function show($username)
    // {
    //     $user = User::where('username', $username)->firstOrFail();
    //     $currentUserId = auth()->id();

    //     // Hitung followers dan following
    //     $followersCount = Friends::where('user_following', $user->id)->count();
    //     $followingCount = Friends::where('user_id', $user->id)->count();

    //     // Cek apakah user saat ini mengikuti profil ini
    //     $isFollowing = false;
    //     if ($currentUserId && $currentUserId != $user->id) {
    //         $isFollowing = Friends::where('user_id', $currentUserId)
    //             ->where('user_following', $user->id)
    //             ->exists();
    //     }

    //     // Ambil semua postingan user
    //     $posts = Posts::where('user_id', $user->id)
    //         ->with(['likes', 'user']) // ikutkan relasi
    //         ->latest()
    //         ->get();

    //     // Hitung jumlah postingan
    //     $postsCount = $posts->count();

    //     return view('profilePage', compact(
    //         'user',
    //         'followersCount',
    //         'followingCount',
    //         'isFollowing',
    //         'postsCount',
    //         'posts' // lempar ke Blade
    //     ));
    // }

    public function show($username)
    {

          if (auth()->check() && auth()->user()->username === $username) {
        return redirect('/profile');
    }
        // Find user by username
        $user = User::where('username', $username)->firstOrFail();
        $authUser = Auth::user();

        // === BLOCK CHECK - CRITICAL SECURITY ===
        if ($authUser) {
            // Check if auth user has blocked this profile user
            $authUserBlockedProfile = DB::table('blocked_users')
                ->where('blocker_id', $authUser->id)
                ->where('blocked_id', $user->id)
                ->exists();

            // Check if profile user has blocked auth user
            $profileBlockedAuthUser = DB::table('blocked_users')
                ->where('blocker_id', $user->id)
                ->where('blocked_id', $authUser->id)
                ->exists();

            // If either block exists, show blocked page
            if ($authUserBlockedProfile || $profileBlockedAuthUser) {
                return view('block', [
                    'username' => $username,
                    'isBlocked' => $authUserBlockedProfile,
                    'isBlockedBy' => $profileBlockedAuthUser,
                    'user' => $user
                ]);
            }
        }
        // === END BLOCK CHECK ===

        // Check if viewing flipside mode
        $isFlipsideView = request()->get('view') === 'flipside';

        $ownerId = $user->id;
        $userId = $authUser ? $authUser->id : null;

        // Check if auth user has flipside access
        $hasFlipsideAccess = false;
        if ($authUser) {
            if ($authUser->id === $user->id) {
                // Owner always has access
                $hasFlipsideAccess = true;
            } else {
                $hasFlipsideAccess = DB::table('flipside_access')->where([
                    'owner_id' => $ownerId,
                    'user_id' => $userId,
                    'has_access' => true
                ])->exists();
            }
        }

        // Get posts based on view mode
        if ($isFlipsideView && $hasFlipsideAccess) {
            // Flipside posts from new table
            $posts = FlipPosts::where('user_id', $user->id)
                ->with(['user', 'flipsideLikes', 'flipsideComments'])
                ->latest()
                ->get();
            $flipsidePosts = $posts;
        } else {
            // Normal posts
            $posts = Posts::where('user_id', $user->id)
                ->with(['user', 'likes', 'comments'])
                ->latest()
                ->get();
            $flipsidePosts = [];
        }

        // Check following status
        $isFollowing = $authUser ? DB::table('friends')
            ->where('user_id', $authUser->id)
            ->where('user_following', $user->id)
            ->exists() : false;

        // Check if user is blocked by auth user
        $isUserBlocked = $authUser ? DB::table('blocked_users')
            ->where('blocker_id', $authUser->id)
            ->where('blocked_id', $user->id)
            ->exists() : false;

        // Followers & following counts
        $followersCount = DB::table('friends')
            ->where('user_following', $user->id)
            ->count();

        $followingCount = DB::table('friends')
            ->where('user_id', $user->id)
            ->count();

        // Posts count
        $postsCount = $posts->count();

        // Likes count (total likes received on all posts)
        $likesCount = 0;
        if ($isFlipsideView && $hasFlipsideAccess) {
            $likesCount = Likes::whereIn('post_id', $posts->pluck('id'))
                ->where('type', 'flipside')
                ->count();
        } else {
            $likesCount = DB::table('likes')
                ->whereIn('post_id', $posts->pluck('id'))
                ->count();
        }

        // Followers (list orang yang mengikuti user ini)
        $followers = DB::table('friends')
            ->join('users', 'friends.user_id', '=', 'users.id')
            ->where('friends.user_following', $user->id)
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->get();

        // Following (list orang yang diikuti user ini)
        $following = DB::table('friends')
            ->join('users', 'friends.user_following', '=', 'users.id')
            ->where('friends.user_id', $user->id)
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->get();


        return view('profilePage', compact(
            'user',
            'posts',
            'hasFlipsideAccess',
            'isFlipsideView',
            'flipsidePosts',
            'isFollowing',
            'isUserBlocked',
            'followers',   // 👍 NEW
            'following',
            'postsCount',
            'likesCount',
            'followersCount',
            'followingCount'
        ));
    }

    /**
     * Show list of users that current user has blocked
     */
    public function blockedUsers()
    {
        $authUser = Auth::user();

        $blockedUsers = DB::table('blocked_users')
            ->join('users', 'blocked_users.blocked_id', '=', 'users.id')
            ->where('blocked_users.blocker_id', $authUser->id)
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.avatar',
                'blocked_users.created_at as blocked_at',
                'blocked_users.reason'
            )
            ->orderBy('blocked_users.created_at', 'desc')
            ->get();

        return view('profile.blocked-list', compact('blockedUsers'));
    }
}
