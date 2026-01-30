<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('activity_log')) {
    function activity_log(string $action, string $description)
    {
        if (!Auth::check()) return;

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,        // ⬅️ INI WAJIB
            'description' => $description,
        ]);
    }
}
