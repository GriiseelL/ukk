<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\JelajahController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoriesController;
use App\Http\Middleware\loginMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/homepage', [PostController::class, 'index'])->name('homepage');
// Route::get('/profile', [FollowController::class, 'index'])->name('profile');


// AUTH
Route::prefix('auth')->middleware(loginMiddleware::class)->group(function () {
    Route::post('register', [AuthController::class, 'regis'])->name('auth.regis');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});
Route::delete('logout', [AuthController::class, 'logout'])->name('logout');

// POSTS (hanya bisa diakses user yang login)
Route::prefix('posts')->name('posts.')->group(function () {
    Route::post('store', [PostController::class, 'store'])->name('store');
    Route::put('update/{posts}', [PostController::class, 'update'])->name('update');
    Route::delete('destroy/{posts}', [PostController::class, 'destroy'])->name('destroy');
    Route::get('show/{id}', [PostController::class, 'show'])->name('show');
});

// LIKE
Route::prefix('like')->middleware('auth')->group(function () {
    Route::post('store/{id}', [LikeController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [LikeController::class, 'destroy'])->name('destroy');
    Route::get('count/{id}', [LikeController::class, 'count'])->name('count');
});


// FOLLOW
Route::prefix('follow')->group(function () {
    Route::post('store/{id}', [FollowController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [FollowController::class, 'destroy'])->name('destroy');
});

// COMMENT
Route::prefix('comment')->group(function () {
    Route::post('store', [CommentController::class, 'store'])->name('store');
    Route::get('index/{id}', [CommentController::class, 'index'])->name('index');
    Route::delete('destroy/{id}', [CommentController::class, 'destroy'])->name('destroy');
});

Route::middleware(UserMiddleware::class)->group(function () {
    // Route::get('/homepage', function () {
    // })->name('homepage');

    Route::get('/homepage', [PostController::class, 'index'])->name('homepage');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('jelajahi', [JelajahController::class, 'index'])->name('jelajahi');

    Route::get('/profilePage', function () {
        return view('profilePage');
    })->name('profilepPage');
});

// di routes/web.php
Route::get('/profilePage/{username}', [JelajahController::class, 'show'])->name('profilePage');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/update-bio', [ProfileController::class, 'updateBio'])->name('profile.updateBio');
Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
Route::post('/profile/update-cover', [ProfileController::class, 'updateCover'])->name('profile.updateCover');


Route::get('stories', [StoriesController::class, 'index'])->name('stories');
Route::get('/stories/create', [StoriesController::class, 'create'])->name('stories.create');
Route::post('stories/store', [StoriesController::class, 'store'])->name('stories.store');


Route::get('/notifikasi', function () {
    return view('notifikasi');
})->name('notifikasi');


// Route::middleware(['userauth'])->group(function () {
//     Route::get('/homepage', fn() => view('homepage'))->name('homepage');
//     Route::get('/profile', fn() => view('accountPrib'))->name('profile');
// });

Route::get('/', function () {
    return view('login');
})->name('login')->middleware(loginMiddleware::class);
