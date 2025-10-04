<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    protected $fillable = [
        "caption",
        "media",
        "type",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
