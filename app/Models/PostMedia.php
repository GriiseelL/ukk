<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $fillable = [
        'mediaable_id',
        'mediaable_type',
        'file_path',
    ];

    public function mediaable()
    {
        return $this->morphTo();
    }

    // helper buat blade
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
