<?php

namespace App\Http\Controllers;

use App\Models\FlipAccess;
use App\Models\Friends;
use App\Models\Notification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function getFollowers()
    {
        $user = auth()->user();

        // Ambil semua followers (orang yang follow user ini)
        $followers = Friends::where('user_following', $user->id)
            ->join('users', 'friends.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $followers
        ]);
    }

    public function store($followingId)
    {
        $user = auth()->user();

        if ($user->id == $followingId) {
            return response()->json([
                'success' => false,
                'error' => 'tidak bisa follow diri sendiri'
            ], 400);
        }

        $existing = Friends::where('user_id', $user->id)
            ->where('user_following', $followingId)
            ->first();

        if ($existing) {
            // UNFOLLOW
            $existing->delete();

            $followersCount = Friends::where('user_following', $followingId)->count();

            return response()->json([
                'success' => true,
                'message' => 'unfollow success',
                'following' => false, // ✅ JavaScript butuh ini
                'followers_count' => $followersCount
            ]);
        }

        // FOLLOW
        Friends::create([
            'user_id' => $user->id,
            'user_following' => $followingId
        ]);

        // 👉 TAMBAH NOTIFIKASI
        Notification::create([
            'sender_id' => $user->id,
            'receiver_id' => $followingId,
            'type' => 'follow',
            'reference_id' => $followingId,
            'message' => $user->username . ' mulai mengikutimu',
            'is_read' => 0 // ✅ Tambahkan ini
        ]);

        $followersCount = Friends::where('user_following', $followingId)->count();

        return response()->json([
            'success' => true, // ✅ Tambahkan ini
            'message' => 'berhasil follow',
            'following' => true, // ✅ JavaScript butuh ini
            'followers_count' => $followersCount
        ]);
    }

    public function destroy($followingId)
    {
        $user = auth()->user();

        $deleted = Friends::where('user_id', $user->id)
            ->where('user_following', $followingId)
            ->delete();

        if ($deleted) {
            $followersCount = Friends::where('user_following', $followingId)->count();

            return response()->json([
                'success' => true, // ✅ Tambahkan ini
                'message' => 'unfoll success',
                'following' => false, // ✅ JavaScript butuh ini
                'followers_count' => $followersCount,
                'status' => 200
            ]);
        }

        return response()->json([
            'success' => false, // ✅ Tambahkan ini
            'message' => 'not found',
            'status' => 409
        ], 409);
    }

    public function removeFollower($id)
    {
        $remove = Friends::where('user_id', $id)
            ->where('user_following', auth()->id())
            ->first();

        if (!$remove) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], 409);
        }

        // Hapus follower
        $remove->delete();

        // Hapus juga akses flipside jika ada
        FlipAccess::where('user_id', $id)
            ->where('owner_id', auth()->id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Follower & akses flipside berhasil dihapus'
        ], 200);
    }
}
