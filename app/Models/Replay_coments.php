<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replay_coments extends Model
{
    protected $fillable = [
         'user_id',
         'coments_id',
         'content'
    ];
}