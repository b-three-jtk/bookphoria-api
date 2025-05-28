<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BorrowController extends Controller
{
    // Store borrow request
    public function store(Request $request)
    {
        $request->validate([
            'user_book_id' => 'required|exists:user_books,id',
            'borrow_date' => 'required|date',
        ]);

        $userBook = UserBook::with('user')->findOrFail($request->user_book_id);
        $borrowerId = Auth::id();
        $ownerId = $userBook->user_id;

        // Check friendship
        if (!$userBook->user->friends->contains($borrowerId)) {
            return response()->json(['message' => 'You are not friends with the book owner.'], 403);
        }

        // Cannot borrow own book
        if ($borrowerId == $ownerId) {
            return response()->json(['message' => 'You cannot borrow your own book.'], 403);
        }

        // Prevent duplicate pending requests
        $existing = Borrow::where('user_book_id', $request->user_book_id)
            ->where('borrower_id', $borrowerId)
            ->where('is_approved', false)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Borrow request already pending.'], 409);
        }

        $borrow = Borrow::create([
            'id' => Str::uuid(),
            'user_book_id' => $request->user_book_id,
            'borrower_id' => $borrowerId,
            'borrow_date' => $request->borrow_date,
            'is_approved' => false,
        ]);

        return response()->json(['message' => 'Borrow request submitted.', 'data' => $borrow], 201);
    }

    // Approve borrow request
    public function acceptBorrowRequest($id)
    {
        $borrow = Borrow::with('userBook')->findOrFail($id);

        if ($borrow->userBook->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($borrow->is_approved) {
            return response()->json(['message' => 'Already approved.'], 409);
        }

        $borrow->is_approved = true;
        $borrow->save();

        return response()->json(['message' => 'Borrow request approved.']);
    }

    // Reject borrow request
    public function rejectBorrowRequest($id)
    {
        $borrow = Borrow::with('userBook')->findOrFail($id);

        if ($borrow->userBook->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $borrow->delete();

        return response()->json(['message' => 'Borrow request rejected.']);
    }

    // Show all pending requests received by current user
    public function showPendingRequests()
    {
        $pending = Borrow::whereHas('userBook', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('is_approved', false)
            ->with(['borrower', 'userBook.book'])
            ->get();

        return response()->json($pending);
    }

    // Show all borrow records for current user (borrowed or owned)
    public function showAllBorrows()
    {
        $userId = Auth::id();

        $borrows = Borrow::where(function ($query) use ($userId) {
                $query->where('borrower_id', $userId)
                      ->orWhereHas('userBook', function ($q) use ($userId) {
                          $q->where('user_id', $userId);
                      });
            })
            ->with(['borrower', 'userBook.book', 'userBook.user'])
            ->get();

        return response()->json($borrows);
    }

    // Mark book as returned
    public function returnBook($id)
    {
        $borrow = Borrow::findOrFail($id);

        if ($borrow->borrower_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (!$borrow->is_approved) {
            return response()->json(['message' => 'Borrow request not approved yet.'], 400);
        }

        if ($borrow->return_date !== null) {
            return response()->json(['message' => 'Book already returned.'], 409);
        }

        $borrow->return_date = now();
        $borrow->save();

        return response()->json(['message' => 'Book marked as returned.']);
    }
}