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

        $trendingBook = Book::query()
            ->select('books.*')
            ->join('reading_progress', 'books.id', '=', 'reading_progress.book_id')
            ->whereMonth('reading_progress.updated_at', now()->month)
            ->groupBy('books.id')
            ->selectRaw('SUM(reading_progress.progress_percent) as total_progress')
            ->orderByDesc('total_progress')
            ->first();

        $popularBook = Book::query()
            ->select('books.*')
            ->join('reviews', 'books.id', '=', 'reviews.book_id')
            ->whereMonth('reviews.updated_at', now()->month)
            ->groupBy('books.id')
            ->selectRaw('SUM(reviews.rating) as total_rating')
            ->orderByDesc('total_rating')
            ->first();

        // Untuk Highlights
        $highlightBooks = [
            "trending" => $trendingBook,
            "newest" => Book::sort('newest')->first(),
            "recommendation" => $popularBook,
        ];

        //Lanjutkan Baca
        $notCompletedBooks = ReadingProgress::booksReadNotCompleted($user);

        //Disimpan
        $savedBooks = $user->savedBooks()->orderBy('pivot_created_at', 'desc')->limit(10)->get();

        //Per Genre
        $booksByGenre = Genre::with(['books.readingProgress'])
            ->get()
            ->filter(function ($genre) {
                return $genre->books->count() >= 5;
            })
            ->sortByDesc(function ($genre) {
                return $genre->books->sum(fn($book) => $book->readingProgress->sum('progress_percent'));
            });

        foreach ($booksByGenre as $genre) {
            $genre->setRelation('books', $genre->books()->inRandomOrder()->limit(10)->get());

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
