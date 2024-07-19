<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', HomeController::class);

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('posts.show');

    Route::middleware([
        'admin',
    ])->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('adminpage', [AdminController::class, 'page'])->name('adminpage');
    });
});
