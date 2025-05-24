<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $created_at = fake()->dateTimeBetween('-1 years', 'now');
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->optional()->dateTimeBetween($created_at, 'now'),
            'role' => 'user',
            'password' => Hash::make('Lokapustaka2025'),
            'gender' => fake()->optional()->randomElement(['Laki-Laki', 'Perempuan', 'Lainnya']),
            'birthdate' => fake()->optional()->date(),
            'is_banned' => fake()->boolean(5),
            'created_at' => $created_at,
            'updated_at' => fake()->dateTimeBetween($created_at, 'now'),
        ];
    }
}
