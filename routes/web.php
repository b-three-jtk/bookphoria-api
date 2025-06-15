<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\AuthorController;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\UserAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'login'])->name('signin');
Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');
Route::get('/register', [AuthController::class, 'register'])->name('signup');
Route::post('/register', [AuthController::class, 'doRegister'])->name('doSignup');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/authors', [AuthorController::class, 'index'])->name('admin.authors.index');
    Route::get('/genres', [GenreController::class, 'index'])->name('admin.genres.index');
    Route::get('/books', [BookController::class, 'index'])->name('admin.books.index');
    Route::get('/profile', [AuthController::class, 'index'])->name('profile');    
});


Route::get('/test-mail', function() {
    try {
        Mail::raw('Ini isi email plain text', function($message) {
            $message->to('test@example.com')
                ->subject('Test Email dari Laravel');
        });
        
        return "Email berhasil dikirim! Cek Mailtrap Inbox";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-reset-password', function() {
    $user = \App\Models\User::first();
    if ($user) {
        $user->notify(new \App\Notifications\ResetPassword('test-token'));
        return "Email reset password terkirim!";
    }
    return "User tidak ditemukan!";
});

Route::get('/reset-password', [UserAuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [UserAuthController::class, 'resetPassword'])->name('reset-password');
Route::get('/reset-password-success', function () {
    return view('auth.reset-success');
})->name('password.reset.success');