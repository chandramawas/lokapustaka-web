<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use Storage;

class ReadBookController extends Controller
{
    public function show(Book $book)
    {
        if (!$book->epub_path || !Storage::exists('public/' . $book->epub_path)) {
            abort(404, 'Ebook Tidak Ditemukan.');
        }

        $epubUrl = Storage::url($book->epub_path); // URL public ke file .epub

        $progress = ReadingProgress::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->latest()
            ->first();

        return view('book.read', ['epubUrl' => $epubUrl, 'book' => $book, 'lastCfi' => $progress->cfi ?? null]);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari frontend
        $request->validate([
            'cfi' => 'required|string',
            'progress_percent' => 'required|integer|min:0|max:100',
            'book_id' => 'required|integer'  // Pastikan book_id ada
        ]);

        // Simpan atau update progress berdasarkan user_id dan book_id
        $progress = ReadingProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $request->book_id],  // Menggunakan user yang login
            [
                'cfi' => $request->cfi,
                'progress_percent' => $request->progress_percent,
            ],
        );

        // Kirim respons JSON ke frontend
        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil disimpan',
            'data' => $progress,
        ]);
    }

}
