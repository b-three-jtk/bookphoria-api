<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;

class AuthorController extends Controller
{
    //
    public function index()
    {
        $authors = Author::with('books')->latest()->paginate(10);

        return view('admin.authors.index', compact('authors'));
    }

    public function store(StoreAuthorRequest $request) 
    {
        Author::create($request->validated());
        
        return redirect()->route('admin.authors.index')->with('success', 'Penulis berhasil ditambahkan!');
    }

    public function update(StoreAuthorRequest $request, $id) 
    {
        $author = Author::find($id);
        $author->update($request->validated());

        return redirect()->route('admin.authors.index')->with('success', 'Penulis berhasil ditambahkan!');
    }

    public function destroy($id) 
    {
        $author = Author::find($id);
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Penulis berhasil dihapus!');
    }
}
