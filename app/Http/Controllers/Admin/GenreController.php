<?php

namespace App\Http\Controllers\Admin;

use App\Models\Genre;
use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;

class GenreController extends Controller
{
    //
    public function index()
    {
        $genres = Genre::with('books')->latest()->paginate(10);

        return view('admin.genres.index', compact('genres'));
    }

    public function store(StoreGenreRequest $request) 
    {
        Genre::create($request->validated());
        
        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil ditambahkan!');
    }

    public function update(StoreGenreRequest $request, $id) 
    {
        $genre = Genre::find($id);
        $genre->update($request->validated());

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil ditambahkan!');
    }

    public function destroy($id) 
    {
        $genre = Genre::find($id);
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil dihapus!');
    }
}
