<?php

// ============================================
// MODEL: CloseFriendList.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CloseFriendList extends Model
{
    use HasFactory;

    protected $table = 'close_friend_lists';

    protected $fillable = [
        'user_id',
        'friend_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who owns this list
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the friend user
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    /**
     * Scope untuk check if user is close friend
     */
    public static function isCloseFriend($userId, $friendId)
    {
        return self::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->exists();
    }

    /**
     * Get all close friend IDs for a user
     */
    public static function getFriendIds($userId)
    {
        return self::where('user_id', $userId)
            ->pluck('friend_id')
            ->toArray();
    }
}