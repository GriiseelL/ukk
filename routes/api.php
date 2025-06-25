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

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'regis'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('posts')->middleware('jwt.auth')->group(function () {
    Route::post('store', [PostController::class, 'store'])->name('store');
    Route::put('update/{posts}', [PostController::class, 'update'])->name('update');
    Route::delete('destroy/{posts}', [PostController::class, 'destroy'])->name('destroy');
});

Route::prefix('like')->middleware('jwt.auth')->group(function() {
   Route::post('store/{id}', [LikeController::class, 'store'])->name('store'); 
   Route::delete('destroy/{id}', [LikeController::class, 'destroy'])->name('destroy');
   Route::get('cost/{id}', [LikeController::class, 'cost'])->name('cost');
});

Route::prefix('follow')->middleware('jwt.auth')->group(function() {
    Route::post('store/{id}', [FollowController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [FollowController::class, 'destroy'])->name('destroy');
});

Route::prefix('comment')->middleware('jwt.auth')->group(function() {
    Route::post('store', [CommentController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [CommentController::class, 'destroy'])->name('destroy');
});

Route::prefix('comentrep')->middleware('jwt.auth')->group(function(){
    Route::post('store/{id}', [CommentRepController::class, 'store'])->name('store');
    Route::delete('destroy', [CommentRepController::class, 'destroy'])->name('destroy');
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});