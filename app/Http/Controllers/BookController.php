<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = request()->query('search');
        Log::info("Received search query: " . $query);
    
        if (auth()->check()) {
            Log::info("User is authenticated");
        } else {
            Log::warning("User is not authenticated");
        }
    
        $books = Book::where('isbn', 'LIKE', "%{$query}%")->get();
    
        if ($books->isEmpty() && $query) {
            $booksFromApi = $this->fetchFromGoogleBooks($query);
    
            if (!empty($booksFromApi)) {
                Log::info("Masuk sini fetch from google books");
                $books = collect($booksFromApi);
            }
        }
    
        if ($books->isEmpty()) {
            $books = Book::all();
        }
    
        return response()->json([
            "message" => "Books retrieved",
            "books" => $books
        ], 200);
    }

    /**
     * Search Google Books API
     *
     * @param string $query
     * @return array
     */
    private function fetchFromGoogleBooks($query)
    {
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => $query,
            'maxResults' => 10
        ]);

        $data = $response->json();

        if (!isset($data['items'])) {
            return [];
        }

        $books = [];
        foreach ($data['items'] as $item) {
            $volumeInfo = $item['volumeInfo'];
            $isbn = $volumeInfo['industryIdentifiers'][0]['identifier'] ?? null;

            if (!Book::where('isbn', $isbn)->exists()) {
                $authorName = $volumeInfo['authors'][0] ?? 'Unknown Author';
                $author = Author::firstOrCreate(['name' => $authorName]);

                $book = Book::firstOrCreate(
                    ['isbn' => $isbn],
                    [
                        'title' => $volumeInfo['title'] ?? 'Unknown Title',
                        'publisher' => $volumeInfo['publisher'] ?? 'Unknown Publisher',
                        'published_date' => $volumeInfo['publishedDate'] ?? '',
                        'synopsis' => $volumeInfo['description'] ?? '',
                        'pages' => $volumeInfo['pageCount'] ?? 0,
                        'cover' => $volumeInfo['imageLinks']['thumbnail'] ?? '',
                        'author_id' => $author->id
                    ]
                );

                $books[] = $book;
            }
        }

        return $books;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        //
        $book = Book::create($request->validated());

        return response()->json([
            "message" => "New Book added",
            "book" => $book
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
        $book->update($request->all());

        return response()->json([
            "message" => "Book updated",
            "book" => $book
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            "message" => "Book deleted"
        ], 204);
    }
}
