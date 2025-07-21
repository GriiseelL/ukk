<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/homepage', [PostController::class, 'index'])->name('homepage');

Route::get('/', function () {
    return view('login');
})->name('login');