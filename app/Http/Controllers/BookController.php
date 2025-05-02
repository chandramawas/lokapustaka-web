<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function detail(Book $book)
    {
        $book->load(['category', 'genres']);

        return view('book.index', compact('book'));
    }
}
