<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookshelfController extends Controller
{
    public function index()
    {
        $books = auth()->user()->savedBooks()->latest()->get();

        return view('bookshelf.index', compact('books'));
    }

    public function continue()
    {
        return view('bookshelf.continue');
    }

    public function reviews()
    {
        $reviews = auth()->user()
            ->reviews()
            ->with('book') // penting biar bisa akses data buku
            ->latest('updated_at')
            ->get();

        return view('bookshelf.reviews', compact('reviews'));
    }
}
