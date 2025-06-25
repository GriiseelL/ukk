<?php

namespace App\Http\Controllers;

use App\Models\Friends;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store($followingId)
    {

        $user = auth()->user();

        if ($user->id == $followingId) {
            return response()->json(['error' => 'tidak bisa follow diri sendiri']);
        }

        $following = Friends::where('user_id', $user->id)
            ->where('user_following', $followingId)
            ->first();

        if ($following) {
            return response()->json(['message' => 'sudah follow']);
        }

        Friends::create([
            'user_id' => $user->id,
            'user_following' => $followingId
        ]);

        return response()->json([
            'message' => 'berhasil follow',
            'status' => 200
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