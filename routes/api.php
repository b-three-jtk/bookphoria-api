<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserBookController;
use App\Http\Controllers\UserFriendController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User routes, for registering, logging in, and logging out
Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('forgot-password', [UserAuthController::class, 'forgotPassword']);
Route::post('reset-password', [UserAuthController::class, 'resetPassword']);
Route::post('logout',[UserAuthController::class,'logout'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Book routes, for adding, deleting, and viewing books master list
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/isbn', [BookController::class, 'getBookByISBN']);
    Route::get('/books/search', [BookController::class, 'getBooksByTitleOrISBN']);
    Route::post('/books', [BookController::class, 'store']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
    
    // UserBook routes, for adding and removing books from user's personal collection
    Route::get('/user/books', [UserBookController::class, 'index']);
    Route::post('/user/books', [UserBookController::class, 'store']);
    Route::put('/user/books/{id}', [UserBookController::class, 'update']);
    Route::delete('/user/books/{id}', [UserBookController::class, 'destroy']);

    // Author routes, for adding, deleting, and viewing authors master list
    Route::get('/authors', [AuthorController::class, 'index']);
    Route::post('/authors', [AuthorController::class, 'store']);
    Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);

    // Shelf routes, for adding, deleting, and viewing shelves master list
    Route::get('/shelves', [ShelfController::class, 'index']);
    Route::post('/shelves', [ShelfController::class, 'store']);
    Route::delete('/shelves/{id}', [ShelfController::class, 'destroy']);
    Route::post('/shelves/{shelf}/books', [ShelfController::class, 'addBook']);
    Route::delete('/shelves/{shelf}/books/{book}', [ShelfController::class, 'removeBook']);

    // Genre routes, for adding, deleting, and viewing genres master list
    Route::get('/genres', [GenreController::class, 'index']);
    Route::post('/genres', [GenreController::class, 'store']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);

    // UserFriend routes, for sending, accepting, and rejecting friend requests
    Route::post('/friends/request', [UserFriendController::class, 'requestFriend']);
    Route::post('/friends/accept/{friendId}', [UserFriendController::class, 'acceptFriendRequest']);
    Route::post('/friends/reject/{friendId}', [UserFriendController::class, 'rejectFriendRequest']);
    Route::get('/friends/pending', [UserFriendController::class, 'showPendingRequests']);
    Route::get('/friends', [UserFriendController::class, 'showAllFriends']);
    Route::get('/friends/{friendId}', [UserFriendController::class, 'getFriendById']);

    // User routes, for viewing user profile and updating user profile
    Route::get('/user/{userName}', [UserAuthController::class, 'getUserByUsername']);
    Route::post('/user/profile', [UserAuthController::class, 'editProfile']);
});
    