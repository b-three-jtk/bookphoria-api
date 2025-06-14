<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserBookRequest;

class UserBookController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserBookRequest $request)
    {
        $userBook = UserBook::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'page_count' => $request->page_count,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'finish_date' => $request->finish_date,
        ]);

        return response()->json([
            'message' => 'Book added to personal collection',
            'data' => $userBook
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserBook  $userBook
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $userBook = UserBook::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->firstOrFail();

        $userBook->delete();

        return response()->json([
            'message' => 'Book removed from personal collection'
        ], 204);
    }

    public function getAllUserBooks()
    {
        $user = auth()->user();
        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $userBooks = UserBook::with(['book', 'book.authors', 'book.genres'])
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($userBook) {
                $book = $userBook->book;
                $bookArray = $book->toArray();
                $bookArray['cover'] = $book->cover ? asset($book->cover) : null;

                return [
                    'user_book_id' => $userBook->id,
                    'status' => $userBook->status,
                    'page_count' => $userBook->page_count,
                    'start_date' => $userBook->start_date,
                    'finish_date' => $userBook->finish_date,
                    'book' => $bookArray
                ];
            });

        return response()->json([
            'data' => $userBooks
        ], 200);
    }

    public function getUserBooksByStatus($status = 'owned')
    {
        $user = auth()->user();
        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $userBooks = UserBook::with(['book', 'book.authors', 'book.genres'])
            ->where('user_id', $user->id)
            ->where('status', $status)
            ->get()
            ->map(function ($userBook) {
                $book = $userBook->book;
                $bookArray = $book->toArray();
                $bookArray['cover'] = $book->cover ? asset($book->cover) : null;

                return [
                    'user_book_id' => $userBook->id,
                    'status' => $userBook->status,
                    'page_count' => $userBook->page_count,
                    'start_date' => $userBook->start_date,
                    'finish_date' => $userBook->finish_date,
                    'book' => $bookArray
                ];
            });

        return response()->json([
            'data' => $userBooks
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $userBook = UserBook::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->firstOrFail();

        $userBook->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Book status updated',
            'data' => $userBook
        ], 200);
    }

    public function getUserBookById($id)
    {
        $user = auth()->user();
        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $userBook = UserBook::with(['book', 'book.authors', 'book.genres'])
            ->where('user_id', $user->id)
            ->where('book_id', $id)
            ->firstOrFail();

        $book = $userBook->book;
        $bookArray = $book->toArray();

        return response()->json([
            'data' => [
                'user_book_id' => $userBook->id,
                'status' => $userBook->status,
                'page_count' => $userBook->page_count,
                'start_date' => $userBook->start_date,
                'finish_date' => $userBook->finish_date,
                'book' => $bookArray
            ]
        ], 200);
    }

    public function deleteUserBookById($id)
    {
        $user = auth()->user();
        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $userBook = UserBook::where('user_id', $user->id)
            ->where('book_id', $id)
            ->firstOrFail();

        $userBook->delete();

        return response()->json([
            'message' => 'User book deleted successfully'
        ], 204);
    }

}
