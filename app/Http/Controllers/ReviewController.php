<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function addReview(StoreReviewRequest $request)
    {
        $review = Review::create([
            'user_id' => $request->user()->id,
            'book_id' => $request->book_id,
            'desc' => $request->desc,
            'rate' => $request->rate,
        ]);

        return response()->json(['message' => 'Review added successfully', 'review' => $review], 201);
    }

    public function showAllReviews($bookId)
    {
        $reviews = Review::with('user')->where('book_id', $bookId)->get();
        return response()->json($reviews);
    }
}
