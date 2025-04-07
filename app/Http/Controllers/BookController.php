<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\UserBook;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $books = Book::all();
    
        return response()->json([
            "message" => "Books retrieved",
            "books" => $books
        ], 200);
    }

    /**
     * Search for books using Google Books API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getBookByISBN(Request $request)
    {
        $isbn = $request->input('q');

        if (!$isbn) {
            return response()->json([
                "message" => "ISBN is required"
            ], 400);
        }

        $books = Book::where('isbn', $isbn)->get();

        if ($books->isEmpty()) {
            $books = $this->fetchFromGoogleBooksByISBN($isbn);
        }

        if (!$books) {
            return response()->json([
                "message" => "No books found"
            ], 404);
        }

        return response()->json([
            "message" => "Books retrieved",
            "books" => $books
        ], 200);
    }

    private function fetchFromGoogleBooksByISBN($isbn)
    {
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'isbn' => "$isbn",
            'maxResults' => 1
        ]);

        $data = $response->json();

        if (!isset($data['items'])) {
            return [];
        }

        $books = [];

        foreach ($data['items'] as $item) {
            $volumeInfo = $item['volumeInfo'];

            $bookIsbn = null;
            if (isset($volumeInfo['industryIdentifiers'])) {
                foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                    if (in_array($identifier['type'], ['ISBN_13', 'ISBN_10'])) {
                        $bookIsbn = $identifier['identifier'];
                        break;
                    }
                }
            }

            // Fallback ke parameter utama jika tidak ditemukan di data
            $bookIsbn = $bookIsbn ?? $isbn;

            if (!Book::where('isbn', $bookIsbn)->exists()) {
                $book = Book::create([
                    'isbn' => $bookIsbn,
                    'title' => $volumeInfo['title'] ?? 'Unknown Title',
                    'publisher' => $volumeInfo['publisher'] ?? 'Unknown Publisher',
                    'published_date' => $volumeInfo['publishedDate'] ?? '',
                    'synopsis' => $volumeInfo['description'] ?? '',
                    'pages' => $volumeInfo['pageCount'] ?? 0,
                    'cover' => $volumeInfo['imageLinks']['thumbnail'] ?? '',
                ]);

                // Authors
                if (isset($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                    foreach ($volumeInfo['authors'] as $authorName) {
                        $author = Author::firstOrCreate(
                            ['name' => $authorName],
                            ['id' => Str::uuid()]
                        );
                        $book->authors()->attach($author->id);
                    }
                } else {
                    $author = Author::firstOrCreate(
                        ['name' => 'Unknown Author'],
                        ['id' => Str::uuid()]
                    );
                    $book->authors()->attach($author->id);
                }

                // Genres / Categories
                if (isset($volumeInfo['categories']) && is_array($volumeInfo['categories'])) {
                    foreach ($volumeInfo['categories'] as $genreName) {
                        $genre = Genre::firstOrCreate(
                            ['name' => $genreName],
                            ['id' => Str::uuid()]
                        );
                        $book->genres()->attach($genre->id);
                    }
                }

                $books[] = $book;
            }
        }

        return $books;
    }

    /**
     * Search for books using Google Books API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getBooksByTitleOrISBN(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json([
                "message" => "Query is required"
            ], 400);
        }

        $books = Book::query();

        if ($query) {
            $books = $books->where(function ($q) use ($query) {
                $q->where('isbn', 'LIKE', "%{$query}%")
                    ->orWhere('title', 'LIKE', "%{$query}%");
            });
        }
        
        $books = $books->get();
        
        if ($books->isEmpty()) {
            $books = $this->fetchFromGoogleBooks($query);
        }

        if (empty($books)) {
            return response()->json([
                "message" => "No books found"
            ], 404);
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
            if (isset($volumeInfo['industryIdentifiers'])) {
                foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                    if ($identifier['type'] === 'ISBN_10') {
                        $isbn = $identifier['identifier'];
                        break;
                    }
                }
            
                if (count($volumeInfo['industryIdentifiers']) > 0) {
                    $isbn = $volumeInfo['industryIdentifiers'][0]['identifier'];
                }
            }

            if (!Book::where('isbn', $isbn)->exists()) {
                $title = $volumeInfo['title'] ?? 'Unknown Title';
                $publisher = $volumeInfo['publisher'] ?? 'Unknown Publisher';
                $publishedDate = $volumeInfo['publishedDate'] ?? '';
                $synopsis = $volumeInfo['description'] ?? '';
                $pages = $volumeInfo['pageCount'] ?? 0;
                $cover = $volumeInfo['imageLinks']['thumbnail'] ?? '';
            
                $book = Book::create([
                    'isbn' => $isbn,
                    'title' => $title,
                    'publisher' => $publisher,
                    'published_date' => $publishedDate,
                    'synopsis' => $synopsis,
                    'pages' => $pages,
                    'cover' => $cover,
                ]);
            
                if (isset($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                    foreach ($volumeInfo['authors'] as $authorName) {
                        $author = Author::firstOrCreate(
                            ['name' => $authorName],
                            ['id' => Str::uuid()]
                        );
                        $book->authors()->attach($author->id);
                    }
                } else {
                    $author = Author::firstOrCreate(
                        ['name' => 'Unknown Author'],
                        ['id' => Str::uuid()]
                    );
                    $book->authors()->attach($author->id);
                }

                if (isset($volumeInfo['categories']) && is_array($volumeInfo['categories'])) {
                    foreach ($volumeInfo['categories'] as $genreName) {
                        $genre = Genre::firstOrCreate(
                            ['name' => $genreName],
                            ['id' => Str::uuid()]
                        );
                        $book->genres()->attach($genre->id);
                    }
                }

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
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $book = Book::create([
                'id' => Str::uuid(),
                'title' => $validated['title'],
                'publisher' => $validated['publisher'],
                'published_date' => $validated['published_date'],
                'synopsis' => $validated['synopsis'],
                'isbn' => $validated['isbn'],
                'pages' => $validated['pages'],
                'cover' => $validated['cover']
            ]);

            foreach ($validated['authors'] as $authorName) {
                $author = Author::firstOrCreate(
                    ['name' => $authorName],
                    ['id' => Str::uuid()]
                );
                $book->authors()->attach($author->id);
            }

            foreach ($validated['genres'] as $genreName) {
                $genre = Genre::firstOrCreate(
                    ['name' => $genreName],
                    ['id' => Str::uuid()]
                );
                $book->genres()->attach($genre->id);
            }

            $user = auth()->user();
            $userBook = UserBook::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'book_id' => $book->id,
                'status' => $validated['user_status'],
                'page_count' => $validated['user_page_count'],
            ]);

            DB::commit();
            return response()->json([
                "message" => "Book added successfully",
                "book" => $book,
                "authors" => $book->authors,
                "genres" => $book->genres,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating book: ' . $e->getMessage());
            return response()->json([
                "message" => "Failed to add book",
                "error" => $e->getMessage()
            ], 500);
        }
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
