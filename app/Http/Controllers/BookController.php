<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function detail(Book $book)
    {
        $book->load(['category', 'genres']);

        return view('book.index', compact('book'));
    }

    public function reviews(Book $book)
    {
        $book->load([
            'category',
            'genres',
            'reviews' => function ($query) {
                $query->latest();
            },
            'reviews.user',
        ]);

        $userReview = $book->reviews->firstWhere('user_id', auth()->id());

        return view('book.reviews', compact('book', 'userReview'));
    }

    public function reviewStore(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Harus memberi rating.',
            'review.max' => 'Ulasan tidak boleh lebih dari 1000 karakter.',
        ]);

        $user = auth()->user();

        // Cek apakah user sudah pernah review buku ini
        $existingReview = $book->reviews->where('user_id', $user->id)->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Kamu sudah pernah menulis ulasan untuk buku ini.');
        }

        $book->reviews()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Ulasan kamu berhasil dikirim!');
    }

    public function reviewUpdate(Request $request, Book $book, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Harus memberi rating.',
            'review.max' => 'Ulasan tidak boleh lebih dari 1000 karakter.',
        ]);

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Ulasan kamu berhasil diperbarui!');
    }

    public function collection(Request $request)
    {
        $query = Book::query();

        // Keyword pencarian
        if ($request->filled('q')) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('author', 'like', '%' . $request->q . '%')
                    ->orWhere('publisher', 'like', '%' . $request->q . '%');
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'az':
                $query->orderBy('title', 'asc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc'); // TODO
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                // default sorting kalau nggak ada
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $books = $query->paginate(12)->withQueryString();

        return view('book.collection', [
            'books' => $books,
            'searchQuery' => $request->q,
        ]);
    }
}
