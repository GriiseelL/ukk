<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlipPosts extends Model
{
    protected $table = 'flipside_posts';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'caption',
        'likes_count',
        'is_flipside',
        'status',
        'deleted_at'
    ];

    protected $casts = [
        'is_flipside' => 'boolean',
        'likes_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /* ================= RELATIONS ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 RELASI KE post_media
    public function media()
    {
        return $this->morphMany(PostMedia::class, 'mediaable');
    }

    public function likes()
    {
        return $this->hasMany(FlipsideLike::class, 'flipside_post_id');
    }
 

    public function flipsideComments()
    {
        return $this->hasMany(Comments::class, 'post_id')
            ->where('type', 'flipside');
    }

    /* ================= SCOPES ================= */

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeFlipside($query)
    {
        return $query->where('is_flipside', 1);
    }
}
