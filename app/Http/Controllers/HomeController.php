<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Untuk Highlights
        $highlightBooks = [
            "trending" => Book::sort('popular')->first(),
            "newest" => Book::sort('newest')->first(),
            "recommendation" => Book::sort('rating')->first(),
        ];

        //Lanjutkan Baca
        $notCompletedBooks = ReadingProgress::booksReadNotCompleted($user);

        //Disimpan
        $savedBooks = $user->savedBooks()->orderBy('pivot_created_at', 'desc')->limit(10)->get();

        //Per Genre
        $booksByGenre = Genre::get();

        foreach ($booksByGenre as $genre) {
            $genre->setRelation('books', $genre->books()->sort('popular')->limit(10)->get());

            foreach ($genre->books as $book) {
                $book->progress = $book->getReadingProgress($user);
            }
        }

        //Array Semua Buku
        $books = [
            'highlights' => $highlightBooks,
            'notCompleted' => $notCompletedBooks,
            'saved' => $savedBooks,
        ];

        foreach ($books as $bookGroup) {
            foreach ($bookGroup as $book) {
                $book->progress = $book->getReadingProgress($user);
            }
        }

        // Ambil 4 genre dengan jumlah buku terbanyak
        $topGenres = Genre::withCount('books')
            ->orderByDesc('books_count')
            ->take(4)
            ->get();

        return view('home', ['books' => $books, 'booksByGenre' => $booksByGenre, 'topGenres' => $topGenres]);
    }
}
