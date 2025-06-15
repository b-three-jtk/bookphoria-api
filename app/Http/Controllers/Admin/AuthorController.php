<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    //
    public function index()
    {
        $authors = Author::with('books')->get();

        return view('admin.authors.index', compact('authors'));
    }
}