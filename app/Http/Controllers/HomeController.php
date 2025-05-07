<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $books = Book::all();

        // Untuk Highlights
        $highlights = [
            "newest" => $books->sortByDesc('created_at')->first(),
            "recommendation" => $books->firstWhere('id', 1),
        ];

        // Ambil 4 genre dengan jumlah buku terbanyak
        $topGenres = Genre::withCount('books')
            ->orderByDesc('books_count')
            ->take(4)
            ->get();

        return view('home', compact('highlights', 'topGenres'));
    }
}
