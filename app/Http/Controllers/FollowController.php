<?php

namespace App\Http\Controllers;

use App\Models\Friends;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    // public function index()
    // {
    //     // $data = User::get();

    //     // view()->share([
    //     //     'user' => $data
    //     // ]);

    //     return view('akunPrib');
    // }

    public function store($followingId)
    {
        $user = auth()->user();

        if ($user->id == $followingId) {
            return response()->json(['error' => 'tidak bisa follow diri sendiri'], 400);
        }

        $existing = Friends::where('user_id', $user->id)
            ->where('user_following', $followingId)
            ->first();

        if ($existing) {
            // kalau sudah follow → Unfollow
            $existing->delete();

            $followersCount = Friends::where('user_following', $followingId)->count();

            return response()->json([
                'message' => 'unfollow success',
                'following' => false,
                'followers_count' => $followersCount
            ]);
        }

        // kalau belum follow → Follow
        Friends::create([
            'user_id' => $user->id,
            'user_following' => $followingId
        ]);

        $followersCount = Friends::where('user_following', $followingId)->count();

        return response()->json([
            'message' => 'berhasil follow',
            'following' => true,
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
            return response()->json([
                'message' => 'unfoll success',
                'data' => null,
                'status' => 200
            ]);
        }

        return response()->json([
            'message' => 'not found',
            'status' => 409
        ], 409);
    }
}