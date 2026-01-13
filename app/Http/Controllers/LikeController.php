<?php

namespace App\Http\Controllers;

use App\Models\FlipPosts;
use App\Models\Likes;
use App\Models\Posts;
use App\Models\FlipsidePosts;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store($postId, $type)
    {

        $user = auth()->user();

        // gunakan $type dari URL tanpa override
        $alreadyLiked = Likes::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->where('type', $type)
            ->exists();

        if ($alreadyLiked) {
            return response()->json([
                'message' => 'already liked',
                'status' => 409
            ]);
        }

        Likes::create([
            'user_id' => $user->id,
            'post_id' => $postId,
            'type'    => $type
        ]);


        return response()->json([
            'message' => 'success',
            'status' => 200
        ]);
    }

    public function destroy($postId, $type)
    {
        Likes::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->where('type', $type)
            ->delete();

        return response()->json([
            'message' => 'success',
            'status' => 200
        ]);
    }

    public function count($postId, $type)
    {
        $count = Likes::where('post_id', $postId)
            ->where('type', $type)
            ->count();

        return response()->json([
            'data' => $count,
            'message' => 'count success',
            'status' => 200
        ]);
    }
}
