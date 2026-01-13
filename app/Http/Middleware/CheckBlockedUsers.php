<?php

// app/Http/Middleware/CheckBlockedUsers.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBlockedUsers
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = $request->route('userId') ?? $request->input('user_id');
            
            if ($userId) {
                $user = Auth::user();
                
                // Check jika user ini di-block atau mem-block
                if ($user->hasBlocked($userId) || $user->isBlockedBy($userId)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
                }
            }
        }
        
        return $next($request);
    }
}