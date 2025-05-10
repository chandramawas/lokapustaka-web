<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->isbn13(),
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'publisher' => $this->faker->company(),
            'year' => $this->faker->year(),
            'pages' => $this->faker->numberBetween(100, 700),
            'language' => $this->faker->randomElement(['Bahasa Indonesia', 'Inggris']),
            'description' => $this->faker->paragraphs(3, true),
            'epub_path' => $this->faker->randomElement(['epubs/dummy.epub', 'epubs/dummy2.epub']),
        ];
    }
}
