<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlipsideLike extends Model
{
    use HasFactory;

    // Jika nama kolom berbeda, specify di sini
    protected $fillable = ['user_id', 'flipside_post_id']; // ← Ubah ini

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Update relasi jika perlu
    public function post()
    {
        return $this->belongsTo(Posts::class, 'flipside_post_id'); // ← Tambah parameter kedua
    }
}