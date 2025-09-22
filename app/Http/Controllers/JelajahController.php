<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friends;
use App\Models\Posts;
use Illuminate\Http\Request;

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

        // Tambahkan status following untuk setiap user
        $search->each(function ($user) use ($currentUserId) {
            $isFollowing = Friends::where('user_id', $currentUserId)
                ->where('user_following', $user->id)
                ->exists();

            $user->isFollowing = $isFollowing;

            // Tambahkan data lain yang dibutuhkan
            $user->followers_count = Friends::where('user_following', $user->id)->count();
            $user->posts_count = 0; // sesuaikan dengan tabel posts Anda
        });

        return view('jelajahi', compact('search', 'keyword'));
    }

    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $currentUserId = auth()->id();

        // Hitung followers dan following
        $followersCount = Friends::where('user_following', $user->id)->count();
        $followingCount = Friends::where('user_id', $user->id)->count();

        // Cek apakah user saat ini mengikuti profil ini
        $isFollowing = false;
        if ($currentUserId && $currentUserId != $user->id) {
            $isFollowing = Friends::where('user_id', $currentUserId)
                ->where('user_following', $user->id)
                ->exists();
        }

        // Ambil semua postingan user
        $posts = Posts::where('user_id', $user->id)
            ->with(['likes', 'user']) // ikutkan relasi
            ->latest()
            ->get();

        // Hitung jumlah postingan
        $postsCount = $posts->count();

        return view('profilePage', compact(
            'user',
            'followersCount',
            'followingCount',
            'isFollowing',
            'postsCount',
            'posts' // lempar ke Blade
        ));
    }

}