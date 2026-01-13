<?php
// app/Models/FlipsideAccess.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlipAccess extends Model
{
    protected $table = 'flipside_access';

    protected $fillable = [
        'owner_id',
        'user_id',
        'has_access'
    ];

    protected $casts = [
        'has_access' => 'boolean'
    ];

    // Owner (yang memberikan akses)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // User yang diberi akses
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
