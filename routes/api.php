<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentRepController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Models\Likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('comentrep')->middleware('jwt.auth')->group(function(){
    Route::post('store/{id}', [CommentRepController::class, 'store'])->name('store');
    Route::delete('destroy', [CommentRepController::class, 'destroy'])->name('destroy');
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/check', function (Request $request) {
//     $user = auth()->user();

//     if (!$user) {
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }

//     return response()->json([
//         'message' => 'Token valid',
//         'user' => $user
//     ]);
// })->middleware('auth:api');