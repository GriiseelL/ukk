<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlipPosts extends Model
{
    protected $table = 'flipside_posts';

    // Laravel auto handle created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'caption',
        'image',
        'likes_count',
        'is_flipside',
        'deleted_at'
    ];

    protected $casts = [
        'is_flipside' => 'boolean',
        'likes_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $appends = ['image_url'];

    /**
     * Get the user that owns the post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_back) {
            return asset('storage/' . $this->image_back);
        }
        return null;
    }

    /**
     * Scope for active posts only (not deleted)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope for flipside posts only
     */
    public function scopeFlipside($query)
    {
        return $query->where('is_flipside', 1);
    }

    public function flipsideLikes()
    {
        return $this->hasMany(\App\Models\Likes::class, 'post_id')
            ->where('type', 'flipside');
    }


    public function flipsideComments()
    {
        return $this->hasMany(Comments::class, 'post_id')->where('type', 'flipside');
    }
}
