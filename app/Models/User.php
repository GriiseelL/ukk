<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Posts;
use App\Models\Stories;
use App\Models\Comment;
use App\Models\ReplayComment;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    // app/Models/User.php
    protected $fillable = [
        'name',
        'flipside_name',
        'username',
        'email',
        'password',
        'avatar',
        'cover',
        'flipside_avatar',
        'bio',
        'flipside_cover',

        // 🔥 WAJIB TAMBAH INI 🔥
        'role',
        'is_suspended',
        'suspended_until',
        'suspended_at',
        'suspend_reason',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'suspended_until' => 'datetime',
        ];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /** ===================== RELASI ===================== */

    public function likedPosts()
    {
        return $this->belongsToMany(Posts::class, 'likes', 'user_id', 'post_id');
    }

    // public function stories()
    // {
    //     return $this->hasMany(Stories::class);
    // }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function replayComments()
    {
        return $this->hasMany(replayComments::class);
    }

    /** ===================== ACCESSOR ===================== */

    public function getActiveAvatarAttribute()
    {
        // Jika mode flipside → pakai avatar flipside
        if (request()->is('flipside*') && $this->flipside_avatar) {
            return $this->flipside_avatar;
        }

        // Default pakai avatar biasa
        return $this->avatar;
    }

    public function flipsideFollowers()
    {
        return $this->hasMany(FlipAccess::class, 'user_id')
            ->where('is_approved', true)
            ->with('follower');
    }

    // Orang-orang yang user INI berikan akses flipside (izin yang dia kasih)
    public function flipsideAccessGranted()
    {
        return $this->hasMany(FlipAccess::class, 'user_id');
    }

    // Orang-orang yang user INI punya izin untuk melihat flipside mereka
    public function flipsideAccessTo()
    {
        return $this->hasMany(FlipAccess::class, 'follower_id')
            ->where('is_approved', true)
            ->with('user');
    }

    // app/Models/User.php
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id')
            ->withTimestamps();
    }

    public function blockedBy()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_id', 'blocker_id')
            ->withTimestamps();
    }

    public function hasBlocked($userId)
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
    }

    public function isBlockedBy($userId)
    {
        return $this->blockedBy()->where('blocker_id', $userId)->exists();
    }

    // ================= FOLLOWING SYSTEM =================
    // ================= FOLLOWING SYSTEM =================
    public function following()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'user_following')
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_following', 'user_id')
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->withTimestamps();
    }

    public function closeFriends()
    {
        return $this->belongsToMany(
            User::class,
            'close_friend_lists',
            'user_id',
            'friend_id'
        )
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->withTimestamps();
    }

    /**
     * Get all stories created by user
     */
    public function stories()
    {
        return $this->hasMany(Stories::class, 'user_id');
    }
}
