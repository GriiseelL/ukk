<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // === AUTO UNSUSPEND ===
        if (
            $user->is_suspended
            && $user->suspended_until
            && now()->greaterThan($user->suspended_until)
        ) {

            $user->update([
                'is_suspended' => 0,
                'suspended_until' => null,
                'suspended_at' => null,
                'suspend_reason' => null,
            ]);
        }

        // === CEK SUSPENDED ===
        if ($user->is_suspended) {
            return redirect()->route('suspended.page');
        }

        // Cegah buka login/register
        if ($request->is('auth/login') || $request->is('auth/register')) {
            return redirect()->route('homepage');
        }

        return $next($request);
    }
}
