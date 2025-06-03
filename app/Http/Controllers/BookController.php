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
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
    
        return response()->json([
            "message" => "Books retrieved",
            "books" => $books
        ], 200);
    }

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

    public function getBooksByTitleOrISBN(Request $request)
    {
        $query = $request->input('q');
        $perPage = (int)$request->input('per_page', 10);
        $page = (int)$request->input('page', 1);

        if (empty($query)) {
            return response()->json(["message" => "Query is required"], 400);
        }

        // Ambil dari DB lokal
        $booksQuery = Book::where(function ($qBuilder) use ($query) {
            $qBuilder->where('isbn', 'LIKE', "%{$query}%")
                    ->orWhere('title', 'LIKE', "%{$query}%");
        });

        $total = $booksQuery->count();
        
        // If we have books in the local database
        if ($total > 0) {
            $books = $booksQuery->skip(($page - 1) * $perPage)
                            ->take($perPage)
                            ->get();
                            
            // If this specific page has no results (might happen on last pages)
            if ($books->isEmpty()) {
                return response()->json([
                    "message" => "No books found for this page",
                    "total" => $total,
                    "per_page" => $perPage,
                    "page" => $page,
                    "books" => []
                ]);
            }
            
            return response()->json([
                "message" => "Books retrieved",
                "books" => $books,
                "total" => $total,
                "per_page" => $perPage,
                "page" => $page,
            ]);
        }
        
        // If no books in local DB, fetch from Google
        $fetchedBooks = $this->fetchFromGoogleBooks($query, $perPage, $page);
        $total = count($fetchedBooks);
        
        if (empty($fetchedBooks)) {
            return response()->json([
                "message" => "No books found",
                "total" => 0,
                "per_page" => $perPage,
                "page" => $page,
                "books" => []
            ], 404);
        }
        
        return response()->json([
            "message" => "Books retrieved from Google",
            "books" => $fetchedBooks,
            "total" => $total, 
            "per_page" => $perPage,
            "page" => $page,
        ]);
    }

    private function fetchFromGoogleBooks($query, $perPage = 10, $page = 1)
    {
        // Calculate start index for Google Books API (0-based index)
        $startIndex = ($page - 1) * $perPage;
        
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => $query,
            'maxResults' => $perPage,
            'startIndex' => $startIndex
        ]);

        $data = $response->json();

        if (!isset($data['items'])) {
            return [];
        }

        $books = [];
        foreach ($data['items'] as $item) {
            $volumeInfo = $item['volumeInfo'];
            $isbn = null;
            
            if (isset($volumeInfo['industryIdentifiers'])) {
                foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                    if ($identifier['type'] === 'ISBN_10' || $identifier['type'] === 'ISBN_13') {
                        $isbn = $identifier['identifier'];
                        break;
                    }
                }
            
                if (!$isbn && count($volumeInfo['industryIdentifiers']) > 0) {
                    $isbn = $volumeInfo['industryIdentifiers'][0]['identifier'];
                }
            }
            
            // If no ISBN found, generate a unique identifier
            if (!$isbn) {
                $isbn = 'GB-' . md5($item['id'] . ($volumeInfo['title'] ?? 'unknown'));
            }

            // Check if this book already exists in the database
            $existingBook = Book::where('isbn', $isbn)->first();
            if ($existingBook) {
                $books[] = $existingBook;
                continue;
            }

            $title = $volumeInfo['title'] ?? 'Unknown Title';
            $publisher = $volumeInfo['publisher'] ?? 'Unknown Publisher';
            $publishedDate = $volumeInfo['publishedDate'] ?? '';
            $synopsis = $volumeInfo['description'] ?? '';
            $pages = $volumeInfo['pageCount'] ?? 0;
            $cover = isset($volumeInfo['imageLinks']) ? ($volumeInfo['imageLinks']['thumbnail'] ?? '') : '';
        
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

        return $books;
    }

    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        if ($request->hasFile('cover')) {
            try {
                if (!$request->file('cover')->isValid()) {
                    return response()->json([
                        'message' => 'Invalid cover file uploaded.',
                    ], 422);
                }
                $path = $request->file('cover')->store('covers', 'public');
            } catch (\Exception $e) {
                Log::error('Error uploading cover: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Cover upload failed.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

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
                'cover' => $path ?? "",
            ]);

            // Authors
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

            UserBook::create([
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

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating book: ' . $e->getMessage());
            return response()->json([
                "message" => "Failed to add book",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'publisher' => 'required|string',
            'published_date' => 'required|date',
            'synopsis' => 'required|string',
            'isbn' => 'required|string',
            'pages' => 'required|integer',
            'cover' => 'nullable|image|max:2048',
            'authors' => 'required|array',
            'authors.*' => 'string',
            'genres' => 'required|array',
            'genres.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('cover')) {
                if (!$request->file('cover')->isValid()) {
                    return response()->json(['message' => 'Invalid cover file uploaded.'], 422);
                }

                if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                }

                $path = $request->file('cover')->store('covers', 'public');
                $book->cover = $path;
            }

            $book->update([
                'title' => $validated['title'],
                'publisher' => $validated['publisher'],
                'published_date' => $validated['published_date'],
                'synopsis' => $validated['synopsis'],
                'isbn' => $validated['isbn'],
                'pages' => $validated['pages'],
            ]);

            $authorIds = [];
            foreach ($validated['authors'] as $authorName) {
                $author = Author::firstOrCreate(
                    ['name' => $authorName],
                    ['id' => Str::uuid()]
                );
                $authorIds[] = $author->id;
            }
            $book->authors()->sync($authorIds);

            $genreIds = [];
            foreach ($validated['genres'] as $genreName) {
                $genre = Genre::firstOrCreate(
                    ['name' => $genreName],
                    ['id' => Str::uuid()]
                );
                $genreIds[] = $genre->id;
            }
            $book->genres()->sync($genreIds);

            DB::commit();

            return response()->json([
                'message' => 'Book updated successfully',
                'book' => $book,
                'authors' => $book->authors,
                'genres' => $book->genres,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating book: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update book',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Book $book)
    {
        //
    }
}
