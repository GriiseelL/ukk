<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoryHide extends Model
{
    use HasFactory;

    protected $table = 'story_hidden_users';

    protected $fillable = [
        'story_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the story that is hidden
     */
    public function story()
    {
        return $this->belongsTo(Stories::class);
    }

    /**
     * Get the user who hid the story
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if story is hidden by user
     */
    public static function isHidden($storyId, $userId)
    {
        return self::where('story_id', $storyId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get all hidden story IDs for a user
     */
    public static function getHiddenStoryIds($userId)
    {
        return self::where('user_id', $userId)
            ->pluck('story_id')
            ->toArray();
    }

    
}