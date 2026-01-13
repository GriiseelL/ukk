<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    protected $fillable = [
        "caption",
        "media",
        "type",
        "user_id",
        "text_content",
        'background',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hiddenUsers()
    {
        return $this->hasMany(
            \App\Models\StoryHide::class,
            'story_id'
        );
    }
}
