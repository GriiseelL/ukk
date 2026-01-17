<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posts extends Model
{
    protected $fillable = [
        'user_id',
        'caption',
        'is_flipside', // optional
    ];

    // 👤 pemilik post
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ❤️ likes
    public function likes()
    {
        return $this->hasMany(Likes::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id', 'id');
    }


    // 🖼️🎥 MEDIA (foto / video)
    public function media()
    {
        return $this->hasMany(PostMedia::class, 'post_id', 'id');
    }


    // helper: hanya foto
    public function images()
    {
        return $this->hasMany(PostMedia::class)->where('type', 'image');
    }

    // helper: hanya video
    public function videos()
    {
        return $this->hasMany(PostMedia::class)->where('type', 'video');
    }
}
