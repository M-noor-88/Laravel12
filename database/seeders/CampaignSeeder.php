<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        Campaign::create([
            'title' => 'Clean Water for All',
            'description' => 'Help bring clean water to rural villages.',
            'goal_amount' => 10000,
            'current_amount' => 0,
            'stripe_account_id' => 'acct_1NQ9r3D7w243k374'
        ]);

        Campaign::create([
            'title' => 'School Supplies for Kids',
            'description' => 'Provide school essentials for children in need.',
            'goal_amount' => 5000,
            'current_amount' => 0,
            'stripe_account_id' => 'acct_2NQ9r3D7w243k374'
        ]);

        Campaign::create([
            'title' => 'Medical Aid Fund',
            'description' => 'Support emergency healthcare for underprivileged families.',
            'goal_amount' => 8000,
            'current_amount' => 0,
           'stripe_account_id' => 'acct_3NQ9r3D7w243k374'
        ]);
    }
}
