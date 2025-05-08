<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function detail(Book $book)
    {
        $book->load('genres');

        return view('book.index', compact('book'));
    }

    public function reviews(Request $request, Book $book)
    {
        $book->load('genres');

        $reviewsQuery = $book->reviews()->with('user');

        // Filter rating
        if ($request->filled('rating')) {
            $reviewsQuery->where('rating', $request->rating);
        }

        // Filter yang ada teks review
        if ($request->filled('text')) {
            if ($request->text === 'yes') {
                $reviewsQuery->whereNotNull('review')->where('review', '!=', '');
            } elseif ($request->text === 'no') {
                $reviewsQuery->whereNull('review')->orWhere('review', '');
            }
        }

        // Sorting
        switch ($request->sort) {
            case 'oldest':
                $reviewsQuery->orderBy('updated_at', 'asc');
                break;
            case 'highest':
                $reviewsQuery->orderBy('rating', 'desc');
                break;
            case 'lowest':
                $reviewsQuery->orderBy('rating', 'asc');
                break;
            default:
                $reviewsQuery->orderBy('updated_at', 'desc');
                break;
        }

        $reviews = $reviewsQuery->paginate(5)->withQueryString();

        $userReview = $book->reviews->firstWhere('user_id', auth()->id());

        return view('book.reviews', compact('book', 'reviews', 'userReview'));
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
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Harus memberi rating.',
            'review.max' => 'Ulasan tidak boleh lebih dari 1000 karakter.',
        ]);

        $review->update($validated);

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
                // TODO
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                // default sorting kalau nggak ada
                $query->orderBy('title', 'asc');
                break;
        }

        // Pagination
        $books = $query->paginate(12)->withQueryString();

        return view('book.collection', [
            'books' => $books,
            'searchQuery' => $request->q,
        ]);
    }

    public function genreCollection(Request $request, $slug)
    {
        $genreModel = Genre::where('slug', $slug)->firstOrFail();

        // Query awal: semua buku dengan genre itu
        $booksQuery = $genreModel->books()->with('genres');

        // Sorting
        switch ($request->sort) {
            case 'az':
                $booksQuery->orderBy('title', 'asc');
                break;
            case 'popular':
                // TODO
                break;
            case 'rating':
                $booksQuery->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'newest':
                $booksQuery->latest();
                break;
            default:
                // default sorting kalau nggak ada
                $booksQuery->orderBy('title', 'asc');
                break;
        }

        // Paginate hasilnya
        $books = $booksQuery->paginate(12)->withQueryString();

        // Kirim ke view
        return view('book.genre', [
            'books' => $books,
            'genre' => $genreModel,
        ]);
    }

    public function bookmark(Book $book)
    {
        $user = auth()->user();

        if ($user->savedBooks()->where('book_id', $book->id)->exists()) {
            $user->savedBooks()->detach($book->id); // unbookmark
        } else {
            $user->savedBooks()->attach($book->id); // bookmark
        }

        return back();
    }

    public function read(Book $book)
    {
        if (!$book->epub_path || !Storage::exists('public/' . $book->epub_path)) {
            abort(404, 'Ebook Tidak Ditemukan.');
        }

        $epubUrl = Storage::url($book->epub_path); // URL public ke file .epub
        return view('book.read', compact('book', 'epubUrl'));
    }
}
