<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\FlipPosts;
use App\Models\Notification;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /* ============================================================
     *  GET COMMENTS (main / flipside)
     *  GET /comment/index/{postId}/{type?}
     * ============================================================*/
    public function index($postId, $type = 'main')
    {
        try {
            if (!in_array($type, ['main', 'flipside'])) {
                $type = 'main';
            }

            // cek post di tabel utama
            $post = Posts::find($postId);
            $isFlipside = false;

            // jika tidak ketemu → cek flipside_posts
            if (!$post) {
                $post = FlipPosts::find($postId);
                if ($post) {
                    $isFlipside = true;
                }
            }

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                    'post_id' => $postId
                ], 404);
            }

            // akses kontrol flipside
            if ($isFlipside && $post->user_id !== Auth::id()) {
                $hasAccess = DB::table('flipside_access')
                    ->where('owner_id', $post->user_id)      // pemilik post
                    ->where('user_id', Auth::id())      // follower
                    ->exists();

                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied to this flipside post'
                    ], 403);
                }
            }

            // ambil komentar
            $comments = Comments::where('post_id', $postId)
                ->where('type', $type)
                ->with('user:id,name,username,avatar,flipside_avatar')
                ->orderBy('created_at', 'desc')
                ->get();

            // tambah avatar aktif
            $comments = $comments->map(function ($comment) use ($type) {
                if ($comment->user) {
                    $comment->user->active_avatar =
                        $type === 'flipside'
                        ? ($comment->user->flipside_avatar ?? $comment->user->avatar)
                        : $comment->user->avatar;
                }
                return $comment;
            });

            return response()->json([
                'success'   => true,
                'comments'  => $comments,
                'type'      => $type,
                'count'     => $comments->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Comment index error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comments: ' . $e->getMessage()
            ], 500);
        }
    }


    /* ============================================================
     *  STORE COMMENT
     *  POST /comment/store
     * ============================================================*/
    public function store(Request $request)
    {
        try {
            Log::info('Comment store request:', $request->all());

            /* ---------------------- VALIDASI POST ID ---------------------- */
            if (!$request->post_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'post_id is required',
                    'errors' => ['post_id' => ['Post ID is required']]
                ], 422);
            }

            /* ---------------------- CARI POST DI 2 TABEL ---------------------- */
            $post = Posts::find($request->post_id);
            $isFlipside = false;

            if (!$post) {
                $post = FlipPosts::find($request->post_id);

                if ($post) {
                    $isFlipside = true;
                    Log::info("Post found in flipside_posts", [
                        'post_id' => $request->post_id
                    ]);
                }
            }

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                    'errors' => [
                        'post_id' => ['Selected post does not exist']
                    ],
                    'debug' => [
                        'checked_tables' => ['posts', 'flipside_posts']
                    ]
                ], 404);
            }

            /* ---------------------- VALIDASI CONTENT ---------------------- */
            if (!$request->content || trim($request->content) === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Content is required',
                    'errors' => ['content' => ['Comment cannot be empty']]
                ], 422);
            }

            if (strlen($request->content) > 1000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Content too long',
                    'errors' => ['content' => ['≤ 1000 characters']]
                ], 422);
            }

            /* ---------------------- DETEKSI TYPE ---------------------- */
            $type = $request->input('type');

            if (!$type || !in_array($type, ['main', 'flipside'])) {
                $type = $isFlipside ? 'flipside' : 'main';
            }

            /* ---------------------- FLIPSIDE ACCESS ---------------------- */
            if ($isFlipside && $post->user_id !== Auth::id()) {
                $hasAccess = DB::table('flipside_followers')
                    ->where('user_id', $post->user_id)
                    ->where('follower_id', Auth::id())
                    ->exists();

                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to comment on this flipside post'
                    ], 403);
                }
            }

            /* ---------------------- CREATE COMMENT ---------------------- */
            $comment = Comments::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'content' => trim($request->content),
                'type' => $type
            ]);

            /* ---------------------- NOTIFIKASI KOMENTAR ---------------------- */
            if (Auth::id() != $post->user_id) {
                Notification::create([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $post->user_id,
                    'type' => 'comment',
                    'reference_id' => $post->id,
                    'message' => Auth::user()->username . ' mengomentari postinganmu'
                ]);
            }


            $comment->load('user:id,name,username,avatar,flipside_avatar');

            $comment->user->active_avatar =
                $type === 'flipside'
                ? ($comment->user->flipside_avatar ?? $comment->user->avatar)
                : $comment->user->avatar;

            Log::info('Comment created:', [
                'comment_id' => $comment->id,
                'post_id'    => $post->id,
                'type'       => $type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Komentar ditambahkan',
                'data'    => $comment,
                'type'    => $type
            ], 201);
        } catch (\Exception $e) {
            Log::error('Comment store error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create comment: ' . $e->getMessage(),
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }


    /* ============================================================
     *  DELETE COMMENT
     * ============================================================*/
    public function destroy($id)
    {
        try {
            $comment = Comments::find($id);

            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment not found'
                ], 404);
            }

            if ($comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $type = $comment->type;
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted',
                'type'    => $type
            ]);
        } catch (\Exception $e) {
            Log::error('Comment delete error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }
}
