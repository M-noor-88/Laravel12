<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = \App\Models\Campaign::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'goal_amount' => $this->faker->randomFloat(2, 1000, 5000),
            'stripe_account_id' => 'acct_' . $this->faker->bothify('##########'),
        ];
    }
}

