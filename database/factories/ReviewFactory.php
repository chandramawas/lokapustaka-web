<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = fake()->numberBetween(1, 5);

        $reviewText = match ($rating) {
            1, 2 => fake()->optional(0.7)->sentence() . ' Buku ini membosankan dan sulit dipahami. Tidak sesuai ekspektasi.',
            3 => fake()->optional(0.6)->sentence() . ' Cukup menarik tapi ada beberapa bagian yang kurang jelas.',
            4 => fake()->optional(0.7)->sentence() . ' Alur ceritanya bagus dan karakter-karakternya menarik.',
            5 => fake()->optional(0.8)->sentence() . ' Buku yang sangat inspiratif dan mendalam. Sangat direkomendasikan!',
            default => null,
        };

        $review_at = fake()->dateTimeBetween('-1 years', 'now');

        return [
            'rating' => $rating,
            'review' => $reviewText,
            'created_at' => $review_at,
            'updated_at' => fake()->dateTimeBetween($review_at, 'now'),
        ];
    }
}
