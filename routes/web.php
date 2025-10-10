<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

});

// Rute khusus untuk memicu notifikasi
Route::post('/trigger-notification', function (Request $request) {
    // Ambil tipe dan pesan dari request, berikan nilai default jika tidak ada
    $type = $request->input('type', 'info');
    $message = $request->input('message', 'This is a test notification.');

    return back()->with('notification', [
        'type' => $type,
        'message' => $message,
    ]);
})->name('notification.trigger');

Route::get('/verify-otp', [VerificationController::class, 'show'])->name('otp.show');
Route::post('/verify-otp', [VerificationController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [VerificationController::class, 'resend'])->name('otp.resend')->middleware('throttle:resend-otp');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/setting', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/setting/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    Route::get('/post/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/post/create', [PostController::class, 'store'])->name('posts.store');
    Route::get('/p/{post}', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/post/{post}', [PostController::class, 'update'])->name('posts.update');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');
    Route::get('/explore/posts', [ExploreController::class, 'posts'])->name('explore.posts');

    Route::post('/profile/{user:username}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::post('/profile/{user:username}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');

    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('post.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('post.unlike');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::get('/{user:username}', [ProfileController::class, 'show'])->name('profile.show');

});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UsersController::class, 'users'])->name('users.index');

    Route::patch('/users/{user}/verify', [UsersController::class, 'verifyUser'])->name('users.verify');
    Route::delete('/users/{user}', [UsersController::class, 'deleteUser'])->name('users.destroy');

    Route::get('/posts', [AdminPostController::class, 'posts'])->name('posts.index');
    Route::delete('/post/{post}', [AdminPostController::class, 'deletePost'])->name('posts.destroy');
});
