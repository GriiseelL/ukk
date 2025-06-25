<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Replay_coments;
use Illuminate\Http\Request;

class CommentRepController extends Controller
{
    public function store(Request $request, $commentId) {
        
        $user = auth()->user();

        $request->validate([
            'content' => 'required|string',
        ]);
    
        $parentComment = Comments::find($commentId);
    
        if (!$parentComment) {
            return response()->json(['error' => 'Komentar induk tidak ditemukan'], 404);
        }
    
        $reply = Replay_coments::create([
            'user_id' => $user->id,
            'post_id' => $parentComment->post_id,
            'coments_id' => $commentId,
            'content' => $request->content,
        ]);
    
        return response()->json([
            'message' => 'Berhasil membalas komentar', 
            'data' => $reply,
            'status' => 200
        ]);
    }

    public function destroy($commentId) {
        
        $comment = Replay_coments::find($commentId);

        if (!$comment) {
            return response()->json([
                'error' => 'komen tidak ditemukan',
                'status' => 404
            ], 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'error' => 'bukan komentar mu',
                'status' => 403
            ], 403);
        }

        $comment->delete();
        return response()->json([
            'message' => 'komen berhasil dihapus',
            'status' => 200
        ], 200);
    }
}