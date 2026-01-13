<?php

// app/Http/Controllers/BlockController.php
namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function store(Request $request, $userId)
    {
        $blocker = Auth::user();
        $blocked = User::findOrFail($userId);

        // Validasi tidak bisa block diri sendiri
        if ($blocker->id === $blocked->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot block yourself'
            ], 400);
        }

        // Check jika sudah di-block
        $existingBlock = BlockedUser::where('blocker_id', $blocker->id)
            ->where('blocked_id', $blocked->id)
            ->first();

        if ($existingBlock) {
            // Unblock
            $existingBlock->delete();

            return response()->json([
                'success' => true,
                'blocked' => false,
                'message' => 'User unblocked successfully'
            ]);
        } else {
            // Block
            BlockedUser::create([
                'blocker_id' => $blocker->id,
                'blocked_id' => $blocked->id,
                'reason' => $request->input('reason')
            ]);

            // Hapus follow relationship jika ada
            $blocker->following()->detach($blocked->id);
            $blocked->following()->detach($blocker->id);

            return response()->json([
                'success' => true,
                'blocked' => true,
                'message' => 'User blocked successfully'
            ]);
        }
    }

    public function index()
    {
        $blockedUsers = Auth::user()->blockedUsers()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $blockedUsers
        ]);
    }

    public function destroy($userId)
    {
        $deleted = BlockedUser::where('blocker_id', Auth::id())
            ->where('blocked_id', $userId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'User unblocked successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Block relationship not found'
        ], 404);
    }
}
