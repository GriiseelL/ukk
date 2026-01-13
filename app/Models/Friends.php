<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    protected $fillable = [
        'user_id',
        'user_following',
        'status'
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'user_following');
    }

    public function followerUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
