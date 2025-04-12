<?php
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Str;



class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users
        $users = User::factory()->count(5)->create();

        // Create 3 campaigns
        $campaigns = Campaign::factory()->count(3)->create();

        // Create donations for each campaign from random users
        foreach ($campaigns as $campaign) {
            Donation::factory()->count(10)->create([
                'campaign_id' => $campaign->id,
                'user_id' => $users->random()->id,
                'status' => 'succeeded',
                'amount' => rand(10, 100),
                'payment_intent_id' => 'pi_' . Str::random(16),
            ]);
        }
    }
}
