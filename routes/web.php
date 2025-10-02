<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/register', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy']);

Route::middleware('auth')->group(function () {

    Route::get('/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/post/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/post/create', [PostController::class, 'store'])->name('posts.store');
    Route::get('/p/{post}', [PostController::class, 'show'])->name('post.show');

    Route::post('/profile/{user:username}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::post('/profile/{user:username}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');

    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('post.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('post.unlike');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

});
