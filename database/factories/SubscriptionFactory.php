<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['bulanan', 'tahunan']);
        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify($type === 'bulanan' ? '+1 month' : '+1 year');

        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => now()->between($startDate, $endDate),
            'created_at' => $startDate,
            'updated_at' => $startDate,
        ];
    }
}
