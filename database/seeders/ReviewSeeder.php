<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        foreach ($users as $user) {
            // Ambil beberapa buku secara acak (1-5 buku)
            $reviewedBooks = $books->random(rand(1, 40));

            foreach ($reviewedBooks as $book) {
                // Cek dulu apakah user sudah review buku ini
                $alreadyReviewed = Review::where('user_id', $user->id)
                    ->where('book_id', $book->id)
                    ->exists();

                if (!$alreadyReviewed) {
                    Review::factory()->create([
                        'user_id' => $user->id,
                        'book_id' => $book->id,
                    ]);
                }
            }
        }
    }
}
