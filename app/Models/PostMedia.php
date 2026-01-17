<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $fillable = [
        'post_id',
        'file_path',
        'type', // image | video
    ];

    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id', 'id');
    }

    // biar gampang di blade
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
