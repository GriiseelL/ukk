<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Posts::class);
    }

    public function scopeMain($query)
    {
        return $query->where('type', 'main');
    }

    public function scopeFlipside($query)
    {
        return $query->where('type', 'flipside');
    }

}