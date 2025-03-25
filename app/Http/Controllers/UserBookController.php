<?php

namespace App\Http\Controllers;

use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserBookRequest;

class UserBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
     * Display the specified resource.
     *
     * @param  \App\Models\UserBook  $userBook
     * @return \Illuminate\Http\Response
     */
    public function show(UserBook $userBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserBook  $userBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserBook $userBook)
    {
        //
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
}
