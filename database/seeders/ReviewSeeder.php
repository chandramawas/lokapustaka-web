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

        foreach ($books as $book) {
            // Setiap buku dikasih 0-7 review random
            $numReviews = rand(0, 7);
            $reviewSent = rand(70, 200);

            for ($i = 0; $i < $numReviews; $i++) {
                Review::create([
                    'book_id' => $book->id,
                    'user_id' => $users->random()->id,
                    'rating' => rand(1, 5),
                    'review' => fake()->boolean(60) ? fake()->sentence($reviewSent) : null, // 70% ada review
                ]);
            }
        }
    }
}
