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
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/authors', [AuthorController::class, 'index'])->name('admin.authors.index');
    Route::post('/authors', [AuthorController::class, 'store'])->name('admin.authors.store');
    Route::put('/authors/{id}', [AuthorController::class, 'update'])->name('admin.authors.update');
    Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])->name('admin.authors.destroy');

    Route::get('/genres', [GenreController::class, 'index'])->name('admin.genres.index');
    Route::post('/genres', [GenreController::class, 'store'])->name('admin.genres.store');
    Route::put('/genres/{id}', [GenreController::class, 'update'])->name('admin.genres.update');
    Route::delete('/genres/{id}', [GenreController::class, 'destroy'])->name('admin.genres.destroy');

    Route::get('/books', [BookController::class, 'index'])->name('admin.books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('admin.books.create');
    Route::post('/books', [BookController::class, 'store'])->name('admin.books.store');
    Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/books/{id}', [BookController::class, 'update'])->name('admin.books.update');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('admin.books.destroy');

    Route::get('/profile', [AuthController::class, 'index'])->name('profile');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
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
    $user = \App\Models\User::first(); // Ambil user contoh
    
    $user->notify(new ResetPassword('token-reset-contoh'));
    
    return "Email reset password terkirim!";
});
