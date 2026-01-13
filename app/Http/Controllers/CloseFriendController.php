<?php

// ============================================
// CONTROLLER: CloseFriendController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\CloseFriendList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CloseFriendController extends Controller
{
    /**
     * Tampilkan halaman manage close friends
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get semua teman user (bisa dari followers/following atau teman sistem kamu)
        $allFriends = User::where('id', '!=', $userId)
            ->select('id', 'name', 'username', 'avatar')
            ->orderBy('name')
            ->get();

        // Get current close friends
        $closeFriendIds = CloseFriendList::where('user_id', $userId)
            ->pluck('friend_id')
            ->toArray();

        return view('close-friends.index', [
            'allFriends' => $allFriends,
            'closeFriendIds' => $closeFriendIds
        ]);
    }

    /**
     * Get list close friends (API)
     */
    public function getList()
    {
        $userId = Auth::id();

        $friends = CloseFriendList::where('user_id', $userId)
            ->with('friend:id,name,username,avatar')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->friend->id,
                    'name' => $item->friend->name,
                    'username' => $item->friend->username ?? '',
                    'avatar' => $item->friend->avatar ? asset('storage/' . $item->friend->avatar) : asset('default-avatar.png'),
                ];
            });

        return response()->json([
            'success' => true,
            'friends' => $friends
        ]);
    }

    /**
     * Add user ke close friends
     */
    public function add(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::id();
        $friendId = $request->friend_id;

        // Validasi tidak bisa add diri sendiri
        if ($friendId == $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menambahkan diri sendiri ke close friends'
            ], 400);
        }

        // Check jika sudah ada
        $exists = CloseFriendList::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah ada di close friends'
            ], 400);
        }

        // Add ke close friends
        CloseFriendList::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan ke close friends'
        ]);
    }

    /**
     * Remove user dari close friends
     */
    public function remove(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::id();
        $friendId = $request->friend_id;

        $deleted = CloseFriendList::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus dari close friends'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan di close friends'
        ], 404);
    }

    /**
     * Toggle close friend status (add/remove)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::id();
        $friendId = $request->friend_id;

        // Validasi tidak bisa add diri sendiri
        if ($friendId == $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menambahkan diri sendiri'
            ], 400);
        }

        $closeFriend = CloseFriendList::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->first();

        if ($closeFriend) {
            // Remove
            $closeFriend->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Dihapus dari close friends'
            ]);
        } else {
            // Add
            CloseFriendList::create([
                'user_id' => $userId,
                'friend_id' => $friendId,
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Ditambahkan ke close friends'
            ]);
        }
    }

    /**
     * Bulk update close friends (replace all)
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'close_friends' => 'required|array',
            'close_friends.*' => 'exists:users,id'
        ]);

        $userId = Auth::id();
        $friendIds = $request->close_friends;

        // Filter out diri sendiri
        $friendIds = array_filter($friendIds, function($id) use ($userId) {
            return $id != $userId;
        });

        // Remove duplicates
        $friendIds = array_unique($friendIds);

        DB::beginTransaction();
        try {
            // Hapus semua close friends lama
            CloseFriendList::where('user_id', $userId)->delete();

            // Insert close friends baru
            if (!empty($friendIds)) {
                $data = [];
                foreach ($friendIds as $friendId) {
                    $data[] = [
                        'user_id' => $userId,
                        'friend_id' => $friendId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                CloseFriendList::insert($data);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Close friends berhasil diperbarui',
                'count' => count($friendIds)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui close friends: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user is in close friends
     */
    public function check(Request $request, $friendId)
    {
        $userId = Auth::id();

        $isCloseFriend = CloseFriendList::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->exists();

        return response()->json([
            'success' => true,
            'is_close_friend' => $isCloseFriend
        ]);
    }
}


// ============================================
// CONTROLLER: StoryHideController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\Stories;
use App\Models\StoryHide;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryHideController extends Controller
{
    /**
     * Get all hidden stories untuk user
     */
    public function index()
    {
        $userId = Auth::id();

        $hiddenStories = StoryHide::where('user_id', $userId)
            ->with(['story.user:id,name,username,avatar'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($hide) {
                return [
                    'id' => $hide->id,
                    'story_id' => $hide->story_id,
                    'hidden_at' => $hide->created_at->diffForHumans(),
                    'story' => [
                        'id' => $hide->story->id,
                        'caption' => $hide->story->caption,
                        'media_url' => $hide->story->media_url,
                        'user' => [
                            'id' => $hide->story->user->id,
                            'name' => $hide->story->user->name,
                            'username' => $hide->story->user->username ?? '',
                            'avatar' => $hide->story->user->avatar ? asset('storage/' . $hide->story->user->avatar) : asset('default-avatar.png'),
                        ]
                    ]
                ];
            });

        return response()->json([
            'success' => true,
            'hidden_stories' => $hiddenStories
        ]);
    }

    /**
     * Hide story dari feed user
     */
    public function store(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id'
        ]);

        $userId = Auth::id();
        $storyId = $request->story_id;

        // Get story untuk validasi
        $story = Stories::findOrFail($storyId);

        // Tidak bisa hide story sendiri
        if ($story->user_id === $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menyembunyikan story sendiri'
            ], 400);
        }

        // Check if already hidden
        $exists = StoryHide::where('story_id', $storyId)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Story sudah disembunyikan sebelumnya'
            ], 400);
        }

        // Hide story
        StoryHide::create([
            'story_id' => $storyId,
            'user_id' => $userId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Story berhasil disembunyikan dari feed Anda'
        ]);
    }

    /**
     * Unhide story (tampilkan kembali)
     */
    public function destroy($storyId)
    {
        $userId = Auth::id();

        $deleted = StoryHide::where('story_id', $storyId)
            ->where('user_id', $userId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Story berhasil ditampilkan kembali'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Story tidak ditemukan di daftar hidden'
        ], 404);
    }

    /**
     * Hide all stories dari user tertentu
     */
    public function hideAllFromUser(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::id();
        $targetUserId = $request->target_user_id;

        // Tidak bisa hide story sendiri
        if ($targetUserId === $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menyembunyikan story sendiri'
            ], 400);
        }

        // Get all active stories dari target user yang belum di-hide
        $stories = Stories::where('user_id', $targetUserId)
            ->where('created_at', '>=', now()->subHours(24))
            ->whereDoesntHave('storyHides', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();

        if ($stories->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada story aktif untuk disembunyikan'
            ], 404);
        }

        // Hide semua stories
        $hideData = [];
        foreach ($stories as $story) {
            $hideData[] = [
                'story_id' => $story->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        StoryHide::insert($hideData);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menyembunyikan {$stories->count()} story",
            'count' => $stories->count()
        ]);
    }

    /**
     * Unhide all stories (clear hidden list)
     */
    public function clearAll()
    {
        $userId = Auth::id();

        $count = StoryHide::where('user_id', $userId)->count();
        
        StoryHide::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => "Berhasil menampilkan kembali {$count} story",
            'count' => $count
        ]);
    }

    /**
     * Check if story is hidden
     */
    public function check($storyId)
    {
        $userId = Auth::id();

        $isHidden = StoryHide::where('story_id', $storyId)
            ->where('user_id', $userId)
            ->exists();

        return response()->json([
            'success' => true,
            'is_hidden' => $isHidden
        ]);
    }
}