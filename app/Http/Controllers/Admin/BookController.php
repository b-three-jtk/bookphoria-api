<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateBookAdminRequest;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBookAdminRequest;

class BookController extends Controller
{
    //
    public function index()
    {
        $books = Book::with(['authors', 'genres'])
            ->latest()
            ->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::all();
        $genres = Genre::all();

        return view('admin.books.create', compact('authors', 'genres'));
    }

    public function store(StoreBookAdminRequest $request)
    {
        $data = $request->validated();

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Simpan buku
        $book = Book::create([
            'title' => $data['title'],
            'publisher' => $data['publisher'] ?? null,
            'published_date' => $data['published_date'],
            'synopsis' => $data['synopsis'],
            'isbn' => $data['isbn'] ?? null,
            'pages' => $data['pages'],
            'cover' => $data['cover'] ?? null,
        ]);

        // Relasikan authors (dalam bentuk id, pastikan authors sudah ada di DB)
        $book->authors()->sync($data['authors']);

        // Relasikan genres
        $book->genres()->sync($data['genres']);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id); // pakai fail-safe
        $authors = Author::all();
        $genres = Genre::all();

        return view('admin.books.edit', compact('book', 'authors', 'genres'));
    }

    public function update(UpdateBookAdminRequest $request, $id)
    {
        $book = Book::findOrFail($id);
        $data = $request->validated();

        // Handle cover jika di-update
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($book->cover && \Storage::disk('public')->exists($book->cover)) {
                \Storage::disk('public')->delete($book->cover);
            }

            // Upload cover baru
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update([
            'title' => $data['title'],
            'publisher' => $data['publisher'] ?? null,
            'published_date' => $data['published_date'],
            'synopsis' => $data['synopsis'],
            'isbn' => $data['isbn'] ?? null,
            'pages' => $data['pages'],
            'cover' => $data['cover'] ?? $book->cover,
        ]);

        // Update relasi authors & genres
        $book->authors()->sync($data['authors']);
        $book->genres()->sync($data['genres']);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
