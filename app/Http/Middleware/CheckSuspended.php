<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuspended
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // belum login → lanjut
        if (!$user) {
            return $next($request);
        }

        // auto-unsuspend
        if ($user->is_suspended && $user->suspended_until) {
            if (now()->greaterThan($user->suspended_until)) {
                $user->update([
                    'is_suspended' => 0,
                    'suspended_until' => null,
                    'suspended_at' => null,
                    'suspend_reason' => null,
                ]);
            }
        }

        // *tidak redirect apa pun*
        return $next($request);
    }
}
