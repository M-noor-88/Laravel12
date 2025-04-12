<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    protected $model = \App\Models\Donation::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'campaign_id' => \App\Models\Campaign::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'payment_intent_id' => 'pi_' . Str::random(16),
            'status' => 'succeeded',
        ];
    }
}
