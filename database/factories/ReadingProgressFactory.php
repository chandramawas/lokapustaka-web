<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReadingProgress>
 */
class ReadingProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $read_at = fake()->dateTimeBetween('-1 years', 'now');
        return [
            'cfi' => 'epubcfi(/6/2!/4/1:0)',
            'progress_percent' => fake()->numberBetween(0, 100),
            'created_at' => $read_at,
            'updated_at' => fake()->dateTimeBetween($read_at, 'now'),
        ];
    }
}
