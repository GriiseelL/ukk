<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\CloseFriendController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FlipsideAccessController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\JelajahController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoriesController;
use App\Http\Middleware\loginMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Route::get('/homepage', [PostController::class, 'index'])->name('homepage');
// Route::get('/profile', [FollowController::class, 'index'])->name('profile');


// AUTH
Route::middleware('web')->group(function () {
    Route::prefix('auth')->middleware(loginMiddleware::class)->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('auth.login.form');
    });

    Route::post('register', [AuthController::class, 'regis'])->name('auth.regis');
    Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
});

// POSTS (hanya bisa diakses user yang login)
Route::prefix('posts')->name('posts.')->group(function () {
    Route::post('store', [PostController::class, 'store'])->name('store');
    Route::put('update/{posts}', [PostController::class, 'update'])->name('update');
    Route::delete('destroy/{posts}', [PostController::class, 'destroy'])->name('destroy');
    Route::get('show/{id}', [PostController::class, 'show'])->name('show');
});

// LIKE
Route::prefix('like')->middleware('auth')->group(function () {
    Route::post('/store/{postId}/{type}', [LikeController::class, 'store']);
    Route::delete('/destroy/{postId}/{type}', [LikeController::class, 'destroy']);
    Route::get('/like/count/{postId}/{type}', [LikeController::class, 'count']);
    Route::post('flipsideStore/{postId}', [LikeController::class, 'storeFlip']);
});


// FOLLOW
Route::prefix('follow')->group(function () {
    Route::post('store/{id}', [FollowController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [FollowController::class, 'destroy'])->name('destroy');
    Route::delete('/remove/{id}', [FollowController::class, 'removeFollower']);
});
Route::middleware('auth:sanctum')->get('/followers', [FollowController::class, 'getFollowers']);


// COMMENT
// Comment routes dengan type parameter
Route::middleware('auth')->group(function () {
    Route::get('/comment/index/{postId}/{type?}', [CommentController::class, 'index']);
    Route::post('/comment/store', [CommentController::class, 'store']);
    Route::delete('/comment/destroy/{id}', [CommentController::class, 'destroy']);
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
Route::post('/profile/update-flipside-cover', [ProfileController::class, 'updateFlipsideCover']);
Route::delete('/profile/remove-flipside-cover', [ProfileController::class, 'removeFlipsideCover']);
Route::post('/profile/update-flipside-avatar', [ProfileController::class, 'updateFlipsideAvatar'])
    ->middleware('auth')
    ->name('profile.updateFlipsideAvatar');
// web.php
Route::post('/profile/update-flipside-name', [ProfileController::class, 'updateFlipsideName'])
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {

    // Create flipside post
    Route::post('/post/store', [ProfileController::class, 'store'])->name('flipside.store');

    // Get flipside posts
    Route::get('/post/flipside', [ProfileController::class, 'getFlipsidePosts'])->name('flipside.index');

    // Delete flipside post
    // Route::delete('/post/destroy/{id}', [ProfileController::class, 'destroy'])->name('flipside.destroy');

    // Restore flipside post
    Route::put('/post/{id}/restore', [ProfileController::class, 'restore'])->name('flipside.restore');
});


Route::get('/stories/create', [StoriesController::class, 'create'])->name('stories.create');
Route::post('stories/store', [StoriesController::class, 'store'])->name('stories.store');
Route::get('/stories', [StoriesController::class, 'show'])->name('stories'); // ← Ganti ke show()
Route::delete('stories/destroy', [StoriesController::class, 'destroy'])->name('destroy');



Route::get('/notifications', [NotifikasiController::class, 'index'])->name('notifications.index')
    ->middleware('auth');

Route::post('/notifications/read', [NotifikasiController::class, 'markAsRead'])
    ->middleware('auth');

// Route::get('/flipside', function () {
//     return view('flipside');
// })->name('flipside');

Route::get('/flipside', [ProfileController::class, 'flipside'])->name('flipside');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/flipside-followers', [FlipsideAccessController::class, 'getFollowers']); // data flipside
    Route::post('/flipside-access/toggle', [FlipsideAccessController::class, 'toggleAccess']); // toggle izin
});
// Route::middleware(['userauth'])->group(function () {
//     Route::get('/homepage', fn() => view('homepage'))->name('homepage');
//     Route::get('/profile', fn() => view('accountPrib'))->name('profile');
// });

// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::post('/block/store/{userId}', [BlockController::class, 'store'])->name('block.store');
    Route::get('/block/index', [BlockController::class, 'index'])->name('block.index');
    Route::delete('/block/destroy/{userId}', [BlockController::class, 'destroy'])->name('block.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/close-friends', [CloseFriendController::class, 'index']);
    Route::post('/close-friends/update', [CloseFriendController::class, 'update']);
});


Route::get('/email/verify', function () {
    return view('verify-email');
})->name('verification.notice');

// link dari email (TANPA LOGIN)
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {

    $user = User::findOrFail($id);

    if (! hash_equals(sha1($user->email), $hash)) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('login')
        ->with('success', 'Email berhasil diverifikasi. Silakan login.');
})->middleware('signed')->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {

    $user = auth()->user();

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login');
    }

    $user->sendEmailVerificationNotification();

    return back()->with('success', 'Email verifikasi dikirim ulang.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::middleware(['auth'])->group(function () {

    // Settings Page
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    // Update Avatar
    Route::post('/profile/update-avatar', [SettingController::class, 'updateAvatar'])->name('profile.updateAvatar');

    // Change Password
    Route::post('/profile/change-password', [SettingController::class, 'changePassword'])->name('profile.changePassword');
});

Route::get('/', function () {
    return view('login');
})->name('login')->middleware(loginMiddleware::class);
