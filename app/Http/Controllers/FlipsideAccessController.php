<?php

namespace App\Http\Controllers;

use App\Models\FlipAccess;
use App\Models\Friends;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FlipsideAccessController extends Controller
{
    /**
     * Ambil semua followers dari akun utama dan daftar access flipside.
     * Endpoint ini dipakai oleh /api/followers dan /api/flipside-followers.
     */
    // Di FlipsideController atau ProfileController
    public function getFollowers(Request $request)
    {
        try {
            $userId = auth()->id();

            // Ambil semua followers menggunakan Model Friend
            $followers = Friends::where('user_following', $userId)
                ->where('status', 1)
                ->with(['user' => function ($query) {
                    $query->select('id', 'name', 'username', 'avatar');
                }])
                ->get()
                ->filter(function ($friend) {
                    return $friend->user !== null;
                })
                ->map(function ($friend) {
                    return [
                        'id' => $friend->user->id,
                        'name' => $friend->user->name,
                        'username' => $friend->user->username,
                        'avatar' => $friend->user->avatar,
                    ];
                })
                ->values();

            // Ambil user IDs yang sudah diberi akses flipside
            $flipsideAccessIds = FlipAccess::where('owner_id', $userId)
                ->where('has_access', true)
                ->pluck('user_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $flipsideAccessIds,
                'followers' => $followers
            ]);
        } catch (\Exception $e) {
            \Log::error('Flipside Followers Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading flipside followers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * API yang dipanggil dari toggle switch (grant/revoke access)
     */
    public function toggleAccess(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'grant_access' => 'required|boolean'
            ]);

            $ownerId = auth()->id();
            $userId = $request->user_id;
            $grantAccess = $request->grant_access;

            // Cek apakah user adalah follower
            $isFollower = DB::table('friends')
                ->where('user_id', $userId)
                ->where('user_following', $ownerId)
                // ->where('status', 1)
                ->exists();

            if (!$isFollower) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not your follower'
                ], 403);
            }

            // Update atau create flipside access
            $access = FlipAccess::updateOrCreate(
                [
                    'owner_id' => $ownerId,
                    'user_id' => $userId
                ],
                [
                    'has_access' => $grantAccess
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $grantAccess ? 'Access granted' : 'Access revoked',
                'data' => $access
            ]);
        } catch (\Exception $e) {
            \Log::error('Toggle Flipside Access Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating access',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
