<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use App\Models\Review;

class BookshelfController extends Controller
{
    public function index()
    {
        $books = auth()->user()->savedBooks()->orderBy('pivot_created_at', 'desc')->get();

        foreach ($books as $book) {
            $book->progress = $book->getReadingProgress(auth()->user());
        }

        return view('bookshelf.index', compact('books'));
    }

    public function history()
    {
        // Ambil buku-buku yang sedang dibaca oleh user
        $books = ReadingProgress::booksBeingRead(auth()->user());

        foreach ($books as $book) {
            $book->progress = $book->getReadingProgress(auth()->user());
        }

        return view('bookshelf.history', compact('books'));
    }

    public function reviews(Request $request)
    {
        $query = Review::where('user_id', auth()->id())->with('book');

        // Filter rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter text
        if ($request->filled('text')) {
            if ($request->text === 'yes') {
                $query->whereNotNull('review')->where('review', '!=', '');
            } elseif ($request->text === 'no') {
                $query->whereNull('review')->orWhere('review', '');
            }
        }

        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('updated_at', 'asc');
                break;
            case 'highest':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest':
                $query->orderBy('rating', 'asc');
                break;
            default: // 'latest'
                $query->orderBy('updated_at', 'desc');
                break;
        }

        $reviews = $query->paginate(5)->withQueryString();

        return view('bookshelf.reviews', compact('reviews'));
    }
}
