<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posts extends Model
{
    protected $fillable = [
        'user_id',
        'caption',
        'image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(\App\Models\Likes::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id');
    }

}