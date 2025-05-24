<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subscription = \App\Models\Subscription::inRandomOrder()->first();
        $status = fake()->randomElement(['completed', 'pending', 'failed']);
        $method = fake()->randomElement(['manual', 'qris']);

        return [
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->type === 'bulanan' ? 15000 : 150000,
            'status' => $status,
            'method' => $method,
            'paid_at' => $status === 'completed' ? $subscription->created_at : null,
            'created_at' => $subscription->created_at,
            'updated_at' => $subscription->created_at,
        ];
    }
}
