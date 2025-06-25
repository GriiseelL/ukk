<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use App\Models\Posts;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store($postId)
    {

        $user = auth()->user();

        $post = Posts::findOrFail($postId);

        $alreadyLiked = Likes::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->exists();

        if ($alreadyLiked) {
            return response()->json([
                'message' => 'You already liked this post',
                'status'=> 409
            ], 409);
        }

        Likes::create([
            'user_id' => $user->id,
            'post_id' => $postId
        ]);

        return response()->json([
            'message' => 'succes',
            'status' => 200
        ], 200);
    }

    public function destroy($postId)
    {

        $user = auth()->user();

        $deleted = Likes::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'unlike success',
                'status' => 200
            ]);
        }

        return response()->json(['message' => 'not found', 409]);

    }

    public function cost($postId)
    {

        $count = Likes::where('post_id', $postId)->count();

        return response()->json([
            'data' => $count,
            'message' => 'berhasil hitung',
            'status' => 200
        ]);
    }
}