<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function index($id)
    {
        $comments = Comments::with(['user:id,name,avatar'])
            ->where('post_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $comments
        ]);
    }




    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required'
        ]);

        $comment = Comments::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Komentar ditambahkan',
            'data' => $comment->load('user'),
            'status' => 201
        ], 201);
    }


    public function destroy($commentId)
    {

        $comment = Comments::find($commentId);

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
        ]);
    }
}